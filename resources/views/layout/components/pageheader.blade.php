<div class="row justify-content-between">
    <div class="col-4">
        <h4 class="display-4">{{ $title }}</h4>
    </div>
    <div class="col-4 text-right">
        @foreach($menu as $item)
        <a href="{{ $item['route'] }}">
            <button type="button" class="btn {{ $item['class'] }} bmd-btn-fab bmd-btn-fab-sm">
                <i class="material-icons">{{ $item['icon'] }}</i>
            </button>
        </a>
        @endforeach
    </div>
</div>