@extends('app')

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row container-fluid">
            <div class="pull-right">
                {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.users.destroy', $user->id]]) !!}
                {!! link_to_route('admin.users.edit', trans('misc.button.edit'), $user->id, ['class' => 'btn btn-info']) !!}
                @if ($user->id != 1)
                    @if ( $user->disabled )
                        {!! link_to_route('admin.users.enable', trans('misc.button.enable'), $user->id, ['class' => 'btn btn-success']) !!}
                    @else
                        {!! link_to_route('admin.users.disable', trans('misc.button.disable'), $user->id, ['class' => 'btn btn-warning']) !!}
                    @endif
                    {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger']) !!}
                @endif
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

            <dt>{{ trans_choice('misc.language', 1) }}</dt>
            <dd>{{ $language }}</dd>

            <dt>{{ trans('misc.status') }}</dt>
            <dd>{{ $user->disabled ? trans('misc.disabled') : trans('misc.enabled') }}</dd>

            <dt>{{ trans('users.linked_account') }}</dt>
            <dd>{{ $account->name }}</dd>

            <dt>{{ trans_choice('misc.roles', sizeof($roles)) }}</dt>
            <dd>
                @foreach ($roles as $role)
                <span class="label label-default">{{ $role->name }}</span>
                @endforeach
            </dd>
        </dl>
    </div>
</div>
@endsection