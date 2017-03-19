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
            @include('mongomies::common.selected-collection',[
                'title' => 'Primary',
                'selectors' => [
                    'container' => [
                        'attribute' => 'id',
                        'value' => 'primary-collections-name-container'
                    ],
                    'text' => [
                        'attribute' => 'id',
                        'value' => 'primary-collections-name'
                    ],
                    'remove-btn' => [
                        'attribute' => 'id',
                        'value' => 'primary-collections-remove-btn',
                        'target' => 'primary-collections-panel',
                        'box' => 'primary-collections-name-container',
                    ],
                    'column-name' => [
                        'attribute' => 'id',
                        'value' => 'primary-collections-input-box',
                        'icon' => 'primary-collections-input-icon',
                        'select' => 'primary-collections-select'
                    ]

                ]
            ])
        </div>

        <div class="column is-6">
            @include('mongomies::common.selected-collection',[
                'title' => 'Foreign',
                'selectors' => [
                    'container' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collections-name-container'
                    ],
                    'text' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collections-name'
                    ],
                    'remove-btn' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collections-remove-btn',
                        'target' => 'foreign-collections-panel',
                        'box' => 'foreign-collections-name-container',
                    ],
                    'column-name' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collections-input-box',
                        'icon' => 'foreign-collections-input-icon',
                        'select' => 'foreign-collections-select'
                    ]
                ]
            ])
        </div>

    </div>

    <div class="columns is-hidden" id="start-analysis-btn-container">
        <div class="column is-12 has-text-centered">
            <a class="button is-info is-outlined" id="start-analysis-btn">Start Analysis</a>
        </div>
    </div>

    <div class="columns is-hidden" id="is-loading">
        <div class="column is-12 has-text-centered">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="columns is-hidden" id="error-message-container">
        <div class="column is-12">
            <div class="notification is-danger" id="error-message-text">
                Danger lorem ipsum dolor sit amet, consectetur
                adipiscing elit lorem ipsum dolor sit amet,
                consectetur adipiscing elit
            </div>
        </div>
    </div>

    <div class="columns is-hidden" id="stats-detail">
        <div class="column is-6" id="primary-stats">
        </div>
        <div class="column is-6" id="foreign-stats">
        </div>
    </div>

    <div class="columns is-hidden" id="stats-errors">
        <div class="column is-6" id="primary-errors">

        </div>
        <div class="column is-6" id="foreign-errors">
        </div>
    </div>

    <div class="columns">

        <div class="column is-6">
            @include('mongomies::common.collections-list', [
                'title' => 'Select Primary Collection',
                'collections' => $collections,
                'selectors' => [
                    'panel' => [
                            'attribute' => 'id',
                            'value' => 'primary-collections-panel'
                    ],
                    'input' => [
                        'attribute' => 'id',
                        'value' => 'primary-collection-input'
                    ],
                    'list' => [
                        'attribute' => 'id',
                        'value' => 'primary-collections'
                    ],
                    'remove-btn' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collections-remove-btn',
                        'target' => 'foreign-collections-name'
                    ]
                ]
            ])
        </div>

        <div class="column is-6">
            @include('mongomies::common.collections-list', [
                'title' => 'Select Foreign Collections',
                'collections' => $collections,
                'selectors' => [
                    'panel' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collections-panel'
                    ],
                    'input' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collection-input'
                    ],
                    'list' => [
                        'attribute' => 'id',
                        'value' => 'foreign-collections'
                    ]
                ]
            ])
        </div>

    </div>

@endsection


@section('scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')}
        });

        var PrimaryCollectionsContainer = $('#primary-collections');
        var ForeignCollectionsContainer = $('#foreign-collections');

        var PrimarySearchInput = $('#primary-collection-input');
        var ForeignSearchInput = $('#foreign-collection-input');

        var PrimaryCollectionRemoveBtn = $('#primary-collections-remove-btn');
        var ForeignCollectionRemoveBtn = $('#foreign-collections-remove-btn');

        var PrimaryKeyInputBox = $('#primary-collections-input-box');
        var ForeignKeyInputBox = $('#foreign-collections-input-box');

        var ClearBtn = $('.clear-btn');
        var StartAnalysisBtnContainer = $('#start-analysis-btn-container');
        var StartAnalysisBtn = $('#start-analysis-btn');

        var PrimaryRelationSelect = $('#primary-collections-select');
        var ForeignRelationSelect = $('#foreign-collections-select');

        var Loader = $('#is-loading');

        var ErrorMessageContainer = $('#error-message-container');
        var ErrorMessageText = $('#error-message-text');

        var StatsDetail = $('#stats-detail');
        var PrimaryStats = $('#primary-stats');
        var ForeignStats = $('#foreign-stats');

        var StatsErrors = $('#stats-errors');
        var PrimaryErrors = $('#primary-errors');
        var ForeignErrors = $('#foreign-errors');

        var PrimaryCollections = PrimaryCollectionsContainer.children();
        var ForeignCollections = ForeignCollectionsContainer.children();

        PrimarySearchInput.keyup(searchCollection);
        ForeignSearchInput.keyup(searchCollection);

        PrimaryCollectionsContainer.on('click', 'a', collectionSelected);
        ForeignCollectionsContainer.on('click', 'a', collectionSelected);

        ClearBtn.click(clearCollectionsSearch);

        PrimaryCollectionRemoveBtn.click(removeCollection);
        ForeignCollectionRemoveBtn.click(removeCollection);

        PrimaryKeyInputBox.keyup(inputBoxChange);
        ForeignKeyInputBox.keyup(inputBoxChange);

        PrimaryRelationSelect.change(isReadyForAnalysis);
        ForeignRelationSelect.change(isReadyForAnalysis);

        StartAnalysisBtn.click(startAnalysis);

        function startAnalysis() {

            var _primaryKey = PrimaryKeyInputBox.val();
            var _foreignKey = ForeignKeyInputBox.val();

            var _primaryRelation = PrimaryRelationSelect.find('option:selected').attr('value');
            var _foreignRelation = ForeignRelationSelect.find('option:selected').attr('value');

            var _primaryCollection = $('#primary-collections-name').text().trim();
            var _foreignCollection = $('#foreign-collections-name').text().trim();

            window.location.href = '/admin/mongomies/relational/analysis?' +
                'primaryKey=' + _primaryKey +
                '&foreignKey=' + _foreignKey +
                '&primaryCollection=' + _primaryCollection +
                '&foreignCollection=' + _foreignCollection;
        }

        /* ============================================ */

        /* STATS Display Detail START */

        function displayStats(stats) {
            unHideStatsDetail();
            displayPrimaryStats(stats.primary[0]);
            displayForeignStats(stats.foreign[0]);
        }

        function unHideStatsDetail() {
            StatsDetail.removeClass('is-hidden');
        }

        function emptyPrimaryStats() {
            PrimaryStats.text('');
        }

        function emptyForeignStats() {
            ForeignStats.text('');
        }

        function displayPrimaryStats(stats) {
            PrimaryStats.JSONView(stats);
        }

        function displayForeignStats(stats) {
            ForeignStats.JSONView(stats);
        }

        /* STATS Display Stats Detail END */

        /* ============================================ */

        /* STATS Errors Display / Hide START */

        function displayErrors(errors) {
            unHideStatsErrors();
            clearPrimaryErrors();
            clearForeignErrors();

            displayPrimaryErrors(errors.primary);
            displayForeignErrors(errors.foreign);
        }

        function unHideStatsErrors() {
            StatsErrors.removeClass('is-hidden');
        }

        function clearPrimaryErrors() {
            PrimaryErrors.text('');
        }

        function clearForeignErrors() {
            ForeignErrors.text('');
        }

        function displayPrimaryErrors(errors) {
            var _noKey = errors['no-key'];
            var _duplicateKey = errors['duplicate-key'];
        }

        function displayForeignErrors(errors) {
            var _noKey = errors['no-key'];
            var _duplicateKey = errors['duplicate-key'];
            var _naked = errors['naked'];
        }

        /* STATS Errors Display / Hide END */

        /* ============================================ */

        /* Loader Display / Hide END */

        function showLoader() {
            Loader.removeClass('is-hidden');
        }

        function hideLoader() {
            if (!Loader.hasClass('is-hidden'))
                Loader.addClass('is-hidden');
        }

        /* Loader Display / Hide END */

        /* ============================================ */

        /* Error Message Display / Hide END */

        function displayErrorMessage(message) {
            ErrorMessageContainer.removeClass('is-hidden');
            ErrorMessageText.text(message);
        }

        function hideErrorMessage() {
            if (!ErrorMessageContainer.hasClass('is-hidden'))
                ErrorMessageContainer.addClass('is-hidden');
        }

        /* Error Message Display / Hide END */

        /* ============================================ */

        function unHideAnalysisBtn() {
            StartAnalysisBtnContainer.removeClass('is-hidden');
        }

        function inputBoxChange() {
            var _this = $(this);
            var _value = _this.val();
            var _icon = getIcon(_this);

            hideAnalysisBtn();

            if (_value.trim() === '') {
                _this.removeClass('is-success');
                _icon.addClass('is-hidden');
                return true;
            }

            _this.addClass('is-success');
            _icon.removeClass('is-hidden');

            isReadyForAnalysis();
        }

        function getIcon(source) {
            return $('#' + source.data().icon);
        }

        function collectionSelected(e) {
            e.preventDefault();
            var _this = $(this);
            var _collectionsPanel = getPanel(_this);
            var _collectionName = getCollectionName(_this);

            var _collectionTargetContainer = getNameContainer(_this);
            var _collectionTargetBox = getNameContainerAnchor(_this);

            _collectionsPanel.addClass('is-hidden');

            _collectionTargetContainer.removeClass('is-hidden');
            _collectionTargetBox.text(_collectionName);

            // get columns and add to target container..
        }

        function hideChildren(parent) {
            $.each(parent.children(), function (item, value) {
                $(value).addClass('is-hidden');
            });
        }

        function unHideChildren(parent) {
            $.each(parent.children(), function (item, value) {
                $(value).removeClass('is-hidden');
            });
        }

        function hideAll() {
            hidePrimaryListItems();
            hideForeignListItems();
        }

        function clearSearch() {
            unHidePrimaryListItems();
            unHideForeignListItems();
        }

        /* ============================================ */

        /* Hide List Items START */

        function unHidePrimaryListItems() {
            $.each(PrimaryCollections, function (index, value) {
                $(value).removeClass('is-hidden');
            });
        }

        function unHideForeignListItems() {
            $.each(ForeignCollections, function (index, value) {
                $(value).removeClass('is-hidden');
            });
        }

        function hidePrimaryListItems() {
            $.each(PrimaryCollections, function (index, value) {
                $(value).addClass('is-hidden');
            });
        }

        function hideForeignListItems() {
            $.each(ForeignCollections, function (index, value) {
                $(value).addClass('is-hidden');
            });
        }

        /* Hide List Items END */

        /* ============================================ */

        /* Remove Selected Collection START */

        function searchCollection() {
            var _this = $(this);
            var _input = $(this).val();

            if (_input.trim() === '') {
                clearSearch();
                return true;
            }

            var _items = $('#' + _this.data().target).children();

            $.each(_items, function (index, value) {
                if (!$(value).text().includes(_input)) {
                    $(value).addClass('is-hidden');
                } else {
                    if ($(value).hasClass('is-hidden')) $(value).removeClass('is-hidden');
                }
            });

        }

        function clearCollectionsSearch(e) {
            e.preventDefault();
            var _this = $(this);
            unHideChildren(getCollectionsContainer(_this));
            clearInput(getCollectionNameInput(_this));
        }

        /* Remove Selected Collection START */

        /* ============================================ */

        /* Remove Selected Collection START */

        function removeCollection(e) {
            e.preventDefault();
            var _this = $(this);
            var _collectionContainer = getCollectionsContainer(_this);
            var _collectionNamePanel = getCollectionNamePanel(_this);

            _collectionContainer.removeClass('is-hidden');
            _collectionNamePanel.addClass('is-hidden');

            hideAnalysisBtn();
            hideStatsDetail();
            hideStatsErrors();
        }

        function hideAnalysisBtn() {
            if (!StartAnalysisBtnContainer.hasClass('is-hidden'))
                StartAnalysisBtnContainer.addClass('is-hidden');

            hideErrorMessage();
        }

        function hideStatsDetail() {
            if (!StatsDetail.hasClass('is-hidden')) {
                StatsDetail.addClass('is-hidden');
            }
        }

        function hideStatsErrors() {
            if (!StatsErrors.hasClass('is-hidden')) {
                StatsErrors.addClass('is-hidden');
            }
        }

        /* Remove Selected Collection END */

        /* ============================================ */

        /* General Helpers START */

        function getCollectionsContainer(anchor) {
            return $('#' + anchor.data().target);
        }

        function getCollectionNamePanel(anchor) {
            return $('#' + anchor.data().panel);
        }

        function getNameContainer(anchor) {
            var _targetName = anchor.data().target;
            return $('#' + _targetName + '-name-container');
        }

        function getNameContainerAnchor(anchor) {
            var _targetName = anchor.data().target;
            return $('#' + _targetName + '-name');
        }

        function getPanel(anchor) {
            var _targetName = anchor.data().target;
            return $('#' + _targetName + '-panel');
        }

        function getCollectionNameInput(anchor) {
            return $('#' + anchor.data().input);
        }

        function isReadyForAnalysis() {
            var _primaryKey = PrimaryKeyInputBox.val();
            var _foreignKey = ForeignKeyInputBox.val();
            var _primaryRelation = PrimaryRelationSelect.find('option:selected').attr('value');
            var _foreignRelation = ForeignRelationSelect.find('option:selected').attr('value');

            hideAnalysisBtn();

            if (_primaryKey.trim() === '') {
                return true;
            }

            if (_foreignKey.trim() === '') {
                return true;
            }

            if (_primaryRelation === undefined) {
                return true;
            }

            if (_foreignRelation === undefined) {
                return true;
            }

            if (_primaryRelation.trim() === '') {
                return true;
            }

            if (_foreignRelation.trim() === '') {
                return true;
            }

            unHideAnalysisBtn();
        }

        function clearInput(input) {
            input.val('');
        }

        function getCollectionName(anchor) {
            return anchor.data().collection;
        }

        /* General Helpers END */

        /* ============================================ */

    </script>
@endsection