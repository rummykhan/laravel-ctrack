<nav class="panel" {{ $selectors['panel']['attribute'].'='.$selectors['panel']['value']}}>

    <div class="panel-heading">
        <a href="#"
           class="clear-btn button is-primary is-outlined is-small"
           data-target="{{ $selectors['list']['value'] }}"
           data-input="{{ $selectors['input']['value'] }}"
        >Clear</a> {{ $title }}
    </div>

    <div class="panel-block">
        <p class="control has-icon">
            <input class="input is-small" type="text" placeholder="Search" data-target="{{ $selectors['list']['value'] }}" {{ $selectors['input']['attribute'].'='.$selectors['input']['value']}} >
            <span class="icon is-small"> <i class="fa fa-search"></i></span>
        </p>
    </div>
    <div {{ $selectors['list']['attribute'].'='.$selectors['list']['value']}}>
        @foreach($collections as $collection)
            <a class="panel-block" href="#" data-collection="{{ $collection }}" data-target="{{ $selectors['list']['value'] }}">
            <span class="panel-icon">
              <i class="fa fa-table" aria-hidden="true"></i>
            </span>
                {{ $collection }}
            </a>
        @endforeach
    </div>
</nav>