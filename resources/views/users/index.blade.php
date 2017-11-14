@extends('app')

@section('content')
    {{-- Draw the title and menu on the right side --}}
    @include('layout.components.pageheader', [
        'title' => uctrans('users.user', 2),
        'menu' => [
            [
                'type' => 'modal',
                'route' => route('admin.users.create'),
                'action' => 'new',
                'method' => 'post',
                'title' => uctrans('users.header.new'),
                'targetmodal' => '#user',
                'class' => 'btn-primary',
                'icon' => 'add',
            ]
        ]
    ])
    @if ( !$users->count() )
        <div class="alert alert-primary top-buffer"><span class="fa fa-exclamation-circle"></span> {{ uctrans('users.no_users')}}</div>
    @else
        <div class="row top-buffer">
            @foreach($users as $user)
                @include('layout.components.usercard')
            @endforeach
        </div>
    @endif
    {{-- Modals --}}
    @include('layout.components.modals.user')
    @include('layout.components.modals.delete', ['route' => 'admin.users.destroy' ])
    @include('layout.components.modals.confirm', [
        'id' => 'confirm',
        'title' => '_title_',
        'message' => '_message_',
        'confirm' => '_confirm_',
        'confirm_class' => 'btn-default',
        'route' => '_route_'
    ])

    {{--@include('layout.components.modals.confirm', [--}}
        {{--'id' => 'confirmDisable',--}}
        {{--'title' => uctrans('misc.disable'),--}}
        {{--'route' => route('admin.users.disable'),--}}
        {{--'confirm' => uctrans('misc.disable'),--}}
        {{--'message' => trans('misc.sentence.confirm', ['action' => trans('misc.disable')])--}}
    {{--])--}}
    {{--@include('layout.components.modals.confirm', [--}}
        {{--'id' => 'confirmEnable',--}}
        {{--'title' => uctrans('misc.enable'),--}}
        {{--'route' => route('admin.users.enable'),--}}
        {{--'confirm' => uctrans('misc.enable'),--}}
        {{--'message' => trans('misc.sentence.confirm', ['action' => trans('misc.enable')])--}}
    {{--])--}}
@endsection

@section('extrajs')
    <script type="text/javascript" src="{{ asset('/js/users.js') }}"></script>
@stop
