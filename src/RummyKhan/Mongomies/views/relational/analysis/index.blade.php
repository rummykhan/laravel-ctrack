@extends('mongomies::layout')

@section('sidebar')
    <aside class="menu">
        <p class="menu-label">
            Database Analysis
        </p>
        <ul class="menu-list">
            <li><a href="{{ url('/', ['admin', 'mongomies']) }}">Home</a></li>
            <li><a href="{{ url('/', ['admin', 'mongomies', 'relational']) }}" class="is-active">Relational</a></li>
            <li><a href="{{ url('/', ['admin', 'mongomies', 'unique']) }}">Unique</a></li>
        </ul>
    </aside>
@endsection


@section('content')
    <div class="columns">

        <div class="column is-6">
            @include('mongomies::common.analysis-stats', [
                'type' => 'Primary',
                'collection' => $primaryCollection,
                'key' => $primaryKey,
                'relation' =>  $primaryRelation,
                'errors' => $analysis['errors']['primary']
            ])
        </div>
        <div class="column is-6">
            @include('mongomies::common.analysis-stats', [
                'type' => 'Secondary',
                'collection' => $foreignCollection,
                'key' => $foreignKey,
                'relation' =>  $foreignRelation,
                'errors' => $analysis['errors']['foreign']
            ])
        </div>
    </div>
@endsection