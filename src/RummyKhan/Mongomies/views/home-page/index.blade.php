@extends('mongomies::layout')

@section('sidebar')
    <aside class="menu">
        <p class="menu-label">
            Analysis
        </p>
        <ul class="menu-list">
            <li><a href="{{ url('/', ['admin', 'mongomies']) }}" class="is-active">Home</a></li>
            <li><a href="{{ url('/', ['admin', 'mongomies', 'relational']) }}">Relational</a></li>
            <li><a href="{{ url('/', ['admin', 'mongomies', 'relational']) }}">Unique</a></li>
        </ul>
    </aside>
@endsection


@section('content')
    <h1>Mongomies</h1>
    <p>Find Anomalies in your mongodb data.</p>
    <h2>Relational Analysis</h2>
    <p>Find anomalies between two/multiple collections having a primary key/ foreign key relationship.</p>
    <h2>Unique Analysis</h2>
    <p>This will help you in finding anomalies based on single collection specific column.</p>
@endsection