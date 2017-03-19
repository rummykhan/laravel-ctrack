<div class="card">
    <header class="card-header">
        <div class="card-header-title">
            {{ $collection }} ({{ $key }}) - ({{ $relation }})
        </div>
    </header>
    <div class="card-content">
        <div class="content">
            @if( count($errors['no-key']) > 0 )
                <h1>No Key</h1>
            @endif
            @if( count($errors['duplicate-key']) > 0 )
                <h1>Duplicate Key</h1>
            @endif
            @if( isset($errors['naked']) && count($errors['naked']) > 0 )
                <h4>Naked Records</h4>
                <ul>
                    @foreach($errors['naked'] as $error)
                        <li>
                            {{ $error['_id'] }} - {{ $error[$key] }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <footer class="card-footer">
        <a href="#" class="card-footer-item">Remove</a>
    </footer>
</div>