<?php

namespace RummyKhan\Mongomies\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RummyKhan\Mongomies\Http\Requests\RelationalAnalysisRequest;

class RelationalController extends CoreController
{
    public function index()
    {
        $collections = $this->getCollections();
        return view('mongomies::relational.index', compact('collections'));
    }

    protected function getCollections()
    {
        $db = DB::getMongoDB();
        $cursor = $db->listCollections();
        $collections = [];
        foreach ($cursor as $collection) {
            $collections[] = $collection->getName();
        }
        return $collections;
    }

    public function startAnalysis(Request $request)
    {
        $primaryCollection = $request->get('primaryCollection');
        $foreignCollection = $request->get('foreignCollection');

        $primaryKey = $request->get('primaryKey');
        $foreignKey = $request->get('foreignKey');

        $primaryRelation = $request->get('primaryRelation');
        $foreignRelation = $request->get('foreignRelation');

        if ($primaryRelation == 'many' && $primaryRelation === $foreignRelation) {
            dd('Many to Many relation is not supported yet.');
            return redirect()->back()->with('error', 'Many to Many relation is not supported yet.');
        }

        if (!$this->_isCollectionExists($primaryCollection, $primaryKey)) {
            dd("Primary Collection [ {$primaryCollection} ] with Key [ {$primaryKey} ] doesn't exists or have 0 records.");
            return redirect()->back()->with('error', "Primary Collection [ {$primaryCollection} ] with Key [ {$primaryKey} ] doesn't exists or have 0 records.");
        }

        if (!$this->_isCollectionExists($foreignCollection, $foreignKey)) {
            dd("Foreign Collection named [ {$foreignCollection} ] with Key [ {$foreignKey} ] doesn't exists or have 0 records.");
            return redirect()->back()->with('error', "Foreign Collection named [ {$foreignCollection} ] with Key [ {$foreignKey} ] doesn't exists or have 0 records.");
        }

        if ($primaryRelation == 'one' && $foreignRelation == 'one') {
            $analysis = $this->analyzeOneToOneRelation($primaryCollection, $primaryKey, $foreignCollection, $foreignKey);
        } else {
            $analysis = $this->analyzeOneToManyRelation($primaryCollection, $primaryKey, $foreignCollection, $foreignKey);
        }

        return view('mongomies::relational.analysis.index', compact('analysis',
            'primaryCollection',
            'primaryKey',
            'primaryRelation',
            'foreignCollection',
            'foreignKey',
            'foreignRelation'
        ));
    }

    private function analyzeOneToOneRelation($primaryCollection, $primaryKey, $foriengCollection, $foreignKey)
    {
        $stats = [
            'primary' => $this->_getStats($primaryCollection),
            'foreign' => $this->_getStats($foriengCollection)
        ];

        $primaryKeys = DB::collection($primaryCollection)->where([$primaryKey => ['$ne' => null]])->pluck($primaryKey);

        // Check how many has no primary key.
        // Check how many has duplicate primary key.
        $errors = [
            'primary' => [
                'no-key' => $this->_getWithNoKey($primaryCollection, $primaryKey),
                'duplicate-key' => $this->_getDuplicatePrimaryKey($primaryCollection, $primaryKey)
            ],
            'foreign' => [
                'no-key' => $this->_getWithNoKey($foriengCollection, $foreignKey),
                'duplicate-key' => $this->_getDuplicatePrimaryKey($foriengCollection, $foreignKey),
                'naked' => DB::collection($foriengCollection)->whereNotIn($foreignKey, $primaryKeys)->get()
            ]
        ];

        $warnings = [];

        return ['stats' => $stats, 'errors' => $errors, 'warnings' => $warnings];
    }

    private function analyzeOneToManyRelation($primaryCollection, $primaryKey, $foriengCollection, $foreignKey)
    {
        $stats = [
            'primary' => $this->_getStats($primaryCollection),
            'foreign' => $this->_getStats($foriengCollection)
        ];

        $primaryKeys = DB::collection($primaryCollection)->where([$primaryKey => ['$ne' => null]])->pluck($primaryKey);

        // Check how many has no primary key.
        // Check how many has duplicate primary key.
        $errors = [
            'primary' => [
                'no-key' => $this->_getWithNoKey($primaryCollection, $primaryKey),
                'duplicate-key' => $this->_getDuplicatePrimaryKey($primaryCollection, $primaryKey)
            ],
            'foreign' => [
                'no-key' => $this->_getWithNoKey($foriengCollection, $foreignKey),
                'duplicate-key' => $this->_getDuplicatePrimaryKey($foriengCollection, $foreignKey),
                'naked' => DB::collection($foriengCollection)->whereNotIn($foreignKey, $primaryKeys)->get()
            ]
        ];

        // check if document have two same fields

        $warnings = [];

        return ['stats' => $stats, 'errors' => $errors, 'warnings' => $warnings];
    }


}
