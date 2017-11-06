@extends('app')

@section('content')
<div class="card-body">
    <h4 class="card-title">{{ $user->fullName() }}</h4>
    <div class="container-fluid">
        <div class="row justify-content-end">
            <div class="dropdown">
                <button type="button" class="btn btn-primary bmd-btn-icon dropdown-toggle" id="userOptionsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userOptionsMenu">
                    {!! link_to_route('admin.users.edit', trans('misc.button.edit'), $user->id, ['class' => 'dropdown-item']) !!}
                    @if ( $user->disabled )
                        {!! link_to_route('admin.users.enable', trans('misc.button.enable'), $user->id, ['class' => 'dropdown-item']) !!}
                    @else
                        {!! link_to_route('admin.users.disable', trans('misc.button.disable'), $user->id, ['class' => 'dropdown-item']) !!}
                    @endif
                    <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-object-type="users" data-record-id="{{$user->id}}" data-target="#confirmDelete">
                        {{  trans('misc.button.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <p class="card-text">
        <ul class="list-group">
            <a class="list-group-item">
                <div class="bmd-list-group-col">
                    <p class="list-group-item-heading">{{ trans('misc.id') }}</p>
                    <p class="list-group-item-text">{{ $user->id }}</p>
                </div>
            </a>
            <a class="list-group-item">
                <div class="bmd-list-group-col">
                    <p class="list-group-item-heading">{{ trans('misc.name') }}</p>
                    <p class="list-group-item-text">{{ $user->fullName() }}</p>
                </div>
            </a>
            <a class="list-group-item">
                <div class="bmd-list-group-col">
                    <p class="list-group-item-heading">{{ trans('misc.email') }}</p>
                    <p class="list-group-item-text">{{ $user->email }}</p>
                </div>
            </a>
            <a class="list-group-item">
                <div class="bmd-list-group-col">
                    <p class="list-group-item-heading">{{ trans_choice('misc.language', 1) }}</p>
                    <p class="list-group-item-text">{{ $language }}</p>
                </div>
            </a>
            <a class="list-group-item">
                <div class="bmd-list-group-col">
                    <p class="list-group-item-heading">{{ trans('misc.status') }}</p>
                    <p class="list-group-item-text">{{ $user->disabled ? trans('misc.disabled') : trans('misc.enabled') }}</p>
                </div>
            </a>
            <a class="list-group-item">
                <div class="bmd-list-group-col">
                    <p class="list-group-item-heading">{{ trans('users.linked_account') }}</p>
                    <p class="list-group-item-text">{{ $account->name }}</p>
                </div>
            </a>
            <a class="list-group-item">
                <div class="bmd-list-group-col">
                    <p class="list-group-item-heading">{{ trans_choice('misc.role', sizeof($roles)) }}</p>
                    <p class="list-group-item-text">
                        @foreach ($roles as $role)
                            <span class="label label-default">{{ $role->name }}</span>
                        @endforeach
                    </p>
                </div>
            </a>
        </ul>
    </p>
</div>
@include('layout.components.modals.confirmdelete', ['route' => 'admin.users.destroy', 'id' => $user->id ])
@endsection