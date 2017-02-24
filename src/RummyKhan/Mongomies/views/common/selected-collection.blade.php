<div class="card is-hidden" {{ $selectors['container']['attribute'] }} = {{ $selectors['container']['value'] }}>
<header class="card-header">
    <div class="card-header-title">
        {{ $title }} Collection: &nbsp;
            <div class="button is-info is-outlined" {{ $selectors['text']['attribute'] }} = {{ $selectors['text']['value'] }}></span>
    </div>
</header>
<div class="card-content">
    <div class="content">
        <label class="label">{{ $title }} Key Column</label>
        <p class="control has-icon has-icon-right">
            <input class="input" id="{{ $selectors['column-name']['value'] }}" data-icon="{{ $selectors['column-name']['icon'] }}" type="text" placeholder="{{ $title }} key column">
            <span class="icon is-small is-hidden" id="{{ $selectors['column-name']['icon'] }}">
                <i class="fa fa-check"></i>
            </span>
        </p>
        <p class="control">
          <span class="select">
            <select id="{{ $selectors['column-name']['select'] }}">
              <option>Select relationship</option>
              <option value="one">One</option>
              <option value="many">Many</option>
            </select>
          </span>
        </p>
    </div>
</div>
<footer class="card-footer">
    <a href="#"
       class="card-footer-item"
    {{ $selectors['remove-btn']['attribute'] }} = {{ $selectors['remove-btn']['value'] }}
    data-target="{{ $selectors['remove-btn']['target'] }}"
    data-panel="{{ $selectors['remove-btn']['box'] }}"
    >Remove</a>
</footer>
</div>