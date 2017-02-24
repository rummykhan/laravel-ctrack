<?php

Route::group([ 'namespace' => 'RummyKhan\Mongomies\Http\Controllers', 'middleware' => ['web']], function(){

    Route::get( config('mongomies.routes.index'),               'HomeController@index');
    Route::get( config('mongomies.routes.relational'),          'RelationalController@index');
    Route::post( config('mongomies.routes.relational-analysis'), 'RelationalController@startAnalysis');

});