<div class="row justify-content-between">
    <div class="col-4">
        <h4 class="display-4">{{ $title }}</h4>
    </div>
    <div class="col-4 text-right">
        @foreach($menu as $item)
            {{--This creates a button that opens a modal--}}
            @if ($item['type'] == 'modal')
                <button type="button" class="btn {{ $item['class'] }} bmd-btn-fab bmd-btn-fab-sm"
                        data-toggle="modal" data-target="{{ $item['targetmodal'] }}"
                        data-action="{{ $item['action'] }}" data-form-method="{{ $item['method'] }}"
                        data-form-action="{{ $item['route'] }}" data-title="{{ $item['title'] }}">
                    <i class="material-icons">{{ $item['icon'] }}</i>
                </button>
                {{--This creates a button with a link--}}
            @else
                <a href="{{ $item['route'] }}">
                    <button type="button" class="btn {{ $item['class'] }} bmd-btn-fab bmd-btn-fab-sm">
                        <i class="material-icons">{{ $item['icon'] }}</i>
                    </button></a>
            @endif
        @endforeach
    </div>
</div>

