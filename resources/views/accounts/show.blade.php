@extends('app')

@section('content')
<h1 class="page-header">{{ trans('accounts.headers.detail') }}: {{ $account->name }}</h1>
<div class="row">
    <div class="col-md-3 col-md-offset-9 text-right">
        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.accounts.destroy', $account->id]]) !!}
        {!! link_to_route('admin.accounts.edit', trans('misc.button.edit'), $account->id, ['class' => 'btn btn-info']) !!}
        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger'.(($account->id == 1) ? ' disabled' : '')]) !!}
        {!! Form::close() !!}
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.id') }}</dt>
    <dd>{{ $account->id }}</dd>

    <dt>{{ trans('misc.name') }}</dt>
    <dd>{{ $account->name }}</dd>

    <dt>{{ trans('misc.description') }}</dt>
    <dd>{{ $account->description }}</dd>
</dl>

@if ( $account->users->count() )
<h3 class="page-header">{{ trans('accounts.linked_users') }}: {{ $account->users->count() }}</h3>
    @foreach( $account->users as $user )
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th>{{ trans('misc.id') }}</th>
                <th>{{ trans('misc.name') }}</th>
                <th class="text-right">{{ trans('misc.action') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td class="text-right">
                    {!! link_to_route('admin.users.show', trans('misc.button.details'), $user->id, ['class' => 'btn btn-info btn-xs']) !!}
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
@endif
{!! link_to_route('admin.accounts.index', trans('misc.button.back'), [], ['class' => 'btn btn-default top-buffer']) !!}
@endsection
