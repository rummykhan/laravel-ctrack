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

    protected function getCollections(){
        $db = DB::getMongoDB();
        $cursor = $db->listCollections();
        $collections = [];
        foreach ($cursor as $collection){
            $collections[] = $collection->getName();
        }
        return $collections;
    }

    public function startAnalysis(RelationalAnalysisRequest $request){
        $primaryCollection = $request->get('primaryCollection');
        $foriengCollection = $request->get('foreignCollection');

        $primaryKey = $request->get('primaryKey');
        $foreignKey = $request->get('foreignKey');

        $primaryRelation = $request->get('primaryRelation');
        $foreignRelation = $request->get('foreignRelation');

        if( $primaryRelation == 'many' && $primaryRelation === $foreignRelation ){
            return $this->_sendResponse('Many to Many relation is not supported yet.', 422, $request->all());
        }

        if( !$this->_isCollectionExists($primaryCollection, $primaryKey) ){
            return $this->_sendResponse("Primary Collection [ {$primaryCollection} ] with Key [ {$primaryKey} ] doesn't exists or have 0 records.", 422, $request->all());
        }

        if( !$this->_isCollectionExists($foriengCollection, $foreignKey) ){
            return $this->_sendResponse("Foreign Collection named [ {$foriengCollection} ] with Key [ {$foreignKey} ] doesn't exists or have 0 records.", 422, $request->all());
        }

        if ( $primaryRelation == 'one' && $foreignRelation == 'one'  ){
            return $this->analyzeOneToOneRelation($primaryCollection, $primaryKey, $foriengCollection, $foreignKey);
        }

        return $this->_sendResponse('Got it', 200, $request->all());
    }

    private function analyzeOneToOneRelation($primaryCollection, $primaryKey, $foriengCollection, $foreignKey){
        $stats = [
            'primary' => $this->_getStats( $primaryCollection ),
            'foreign' => $this->_getStats( $foriengCollection )
        ];

        $primaryKeys = DB::collection($primaryCollection)->where([$primaryKey => ['$ne' => null]])->pluck($primaryKey);

        // Check how many has no primary key.
        // Check how many has duplicate primary key.
        $errors = [
            'primary' => [
                'no-key' => $this->_getWithNoKey($primaryCollection, $primaryKey),
                'duplicate-key' => $this->_getDuplicatePrimaryKey($primaryCollection, $primaryKey),
            ],
            'foreign' => [
                'no-key' => $this->_getWithNoKey( $foriengCollection, $foreignKey ),
                'duplicate-key' => $this->_getDuplicatePrimaryKey( $foriengCollection, $foreignKey ),
                'naked' => DB::collection($foriengCollection)->whereNotIn($foreignKey, $primaryKeys)->get()
            ]
        ];

        $warnings = [];

        return ['stats' => $stats, 'errors' => $errors];
    }




}
