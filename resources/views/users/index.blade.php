@extends('app')

@section('content')
@include('users/partials/_search')
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row container-fluid">
            <div class="pull-left"><h3>Users</h3></div>
            <div class="pull-right">
                {!! link_to_route('admin.users.create', trans('users.button.new_user'), [ ], ['class' => 'btn btn-raised btn-info']) !!}
            </div>
        </div>
        @if ( !$users->count() )
        <div class="alert alert-info top-buffer"><span class="fa fa-exclamation-circle"></span> {{ trans('users.no_users')}}</div>
        @else
        {!!
        $users->columns([
            'id' => trans('misc.id'),
            'first_name' => trans('users.first_name'),
            'last_name' => trans('users.last_name'),
            'account_id' => trans_choice('misc.account', 1),
            'action' => trans('misc.action'),
        ])
        ->means('account_id', 'account')
        ->modify('first_name', function($user) {
            return link_to_route('admin.users.show', $user->first_name, [$user->id]);
        })
        ->modify('last_name', function($user) {
            return link_to_route('admin.users.show', $user->last_name, [$user->id]);
        })
        ->modify('account_id', function($account) {
            return link_to_route('admin.accounts.show', $account->name, [$account->id]);
        })
        ->sortable(['id', 'first_name', 'last_name'])
        ->render()
         !!}
        {!! $users->appends(['field' => Input::get('field'), 'sort' => Input::get('sort')])->render() !!}
        @endif
    </div>
</div>
@endsection
