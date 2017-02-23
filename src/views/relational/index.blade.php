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
            @include('mongomies::common.collections-list', [
                'title' => 'Select Primary Collection',
                'collections' => $collections,
                'selectors' => [
                    'input' => [
                        'attribute' => 'id',
                        'value' => 'primary-collection'
                    ],
                    'list' => [
                        'attribute' => 'id',
                        'value' => 'primary-list'
                    ]
                ]
            ])
        </div>

        <div class="column is-6">
            @include('mongomies::common.collections-list', [
                'title' => 'Select Foreign Collections',
                'collections' => $collections,
                'selectors' => [
                    'input' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collection'
                    ],
                    'list' => [
                        'attribute' => 'id',
                        'value' => 'foreign-list'
                    ]
                ]
            ])
        </div>

    </div>

@endsection


@section('scripts')
	<script type="text/javascript">

        var PrimaryCollections = $('#primary-list').children();
        var ForeignCollections = $('#foreign-list').children();

        var PrimarySearchInput = $('#primary-collection');
        var ForeignSearchInput = $('#foreign-collection');

        PrimarySearchInput.keyup(searchCollection);
        ForeignSearchInput.keyup(searchCollection);

        function searchCollection(){
            var _this = $(this);
            var _input = $(this).val();

            if( _input.trim() === '' ){
                clearSearch();
                return true;
            }

            var _items = $('#'+_this.data().target).children();

            $.each(_items, function(index, value){
                if( !$(value).text().includes(_input) ){
                    $(value).addClass('is-hidden');
                }else{
                    if( $(value).hasClass('is-hidden')) $(value).removeClass('is-hidden');
                }
            });

        }

        function clearSearch(){
            unHidePrimaryListItems();
            unHidePrimaryListItems();
        }

        function unHidePrimaryListItems(){
            $.each(PrimaryCollections, function(index, value){
                $(value).removeClass('is-hidden');
            });
        }

        function unHideForeignListItems(){
            $.each(ForeignCollections, function(index, value){
                $(value).removeClass('is-hidden');
            });
        }

    </script>
@endsection