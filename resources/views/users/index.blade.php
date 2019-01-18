@extends('app')

@section('content')
    {{-- Draw the title and menu on the right side --}}
    @include('layout.components.pageheader', [
        'title' => trans('users.title.index'),
        'menu' => [
            [
                'type' => 'modal',
                'route' => route('admin.users.store'),
                'action' => 'new',
                'method' => 'post',
                'title' => trans('users.title.new'),
                'targetmodal' => '#user',
                'class' => 'btn-primary',
                'icon' => 'add',
            ]
        ]
    ])
    @if ( !$users->count() )
        <div class="alert alert-primary top-buffer"><span class="fa fa-exclamation-circle"></span> {{ trans('users.message.none_found')}}</div>
    @else
        <div class="row top-buffer">
            @foreach($users as $user)
                @include('users.components.card')
            @endforeach
        </div>
    @endif
    {{-- Modals --}}
    @include('users.components.modal')
    @include('layout.components.modals.confirm', [
        'id' => 'confirm',
        'title' => '_title_',
        'message' => '_message_',
        'confirm' => '_confirm_',
        'confirm_class' => 'btn-default',
        'route' => '_route_'
    ])
@endsection

@section('extrajs')
    <script type="text/javascript" src="{{ asset('/js/users.js') }}"></script>
@stop
