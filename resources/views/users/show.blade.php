@extends('app')

@section('content')
<h1 class="page-header">{{ trans('users.headers.detail') }}: {{ $user->first_name }} {{ $user->last_name }}</h1>
<div class="row">
    <div class="col-md-3 col-md-offset-9 text-right">
        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.users.destroy', $user->id]]) !!}
        {!! link_to_route('admin.users.edit', trans('misc.button.edit'), $user->id, ['class' => 'btn btn-info']) !!}
        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger'.(($user->id == 1) ? ' disabled' : '')]) !!}
        {!! Form::close() !!}
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.id') }}</dt>
    <dd>{{ $user->id }}</dd>

    <dt>{{ trans('misc.name') }}</dt>
    <dd>{{ $user->first_name }} {{ $user->last_name }}</dd>

    <dt>{{ trans('misc.email') }}</dt>
    <dd><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></dd>

    <dt>{{ trans('users.linked_account') }}</dt>
    <dd>{{ $account->name }}</dd>
</dl>

{!! link_to_route('admin.users.index', trans('misc.button.back'), [], ['class' => 'btn btn-default top-buffer']) !!}
@endsection
