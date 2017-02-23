<?php

namespace RummyKhan\Mongomies\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RelationalController extends Controller
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
}
