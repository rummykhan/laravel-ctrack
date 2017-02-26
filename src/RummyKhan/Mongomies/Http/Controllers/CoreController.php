<?php

namespace RummyKhan\Mongomies\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class CoreController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function _sendResponse($message, $status=200, $data=[]){
        $response = [];
        $response['message'] = $message;
        if( count($data) ){
            $response['data'] = $data;
        }

        if( \Illuminate\Support\Facades\Request::expectsJson() ){
            return response()->json($response, $status);
        }

        return response($response, $status);
    }

    protected function _isCollectionExists($collection, $key){
        return DB::collection($collection)->where([$key => ['$exists' => true]])->count() > 0;
    }

    protected function _getStats($collection){
        $values = [];

        foreach ( DB::getMongoDB()->command(['collStats' => $collection]) as $value ){
            $values[] = $value;
        }

        return $values;
    }

    protected function _getWithNoKey($collection, $primaryKey){
        $cursor = DB::collection($collection)
            ->where([$primaryKey => ['$exists' => false]])
            ->orWhere([ $primaryKey => null ])
            ->get();
        $records = [];

        foreach ($cursor as $record){
            $records[] = $record;
        }

        return $records;
    }

    protected function _getDuplicatePrimaryKey($collection, $primaryKey){
        $db = DB::getMongoDB();

        $cursor = $db->$collection->aggregate([
            ['$group' => ['_id' => '$'.$primaryKey, 'count' => ['$sum' => 1]]],
            ['$match' => ['count' => ['$gt' => 1]]]
        ]);
        $aggregated = [];

        foreach ($cursor as $record){
            $aggregated[] = $record;
        }

        $records = [];

        foreach ($aggregated as $record){
            $record[] = DB::collection($collection)->where([$primaryKey => $record['_id']])->first();
        }

        return $records;
    }


}
