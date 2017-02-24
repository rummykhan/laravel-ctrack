<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mongomies Routes
    |--------------------------------------------------------------------------
    |
    | Here you may specify your routes for xlog, for now there are three basic routes
    | 1. Index where you can start analysis..
    |
    */
    'routes' => [
        'index'                 => '/admin/mongomies',
        'relational'            => '/admin/mongomies/relational',
        'relational-analysis'   => '/admin/mongomies/relational/analysis',
    ],
];