<?php

namespace RummyKhan\Mongomies\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller{

    public function index(){
        return view('mongomies::home-page.index');
    }
}
