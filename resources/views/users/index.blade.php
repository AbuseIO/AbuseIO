@extends('app')

@section('content')
    <h1 class="page-header">{{ trans_choice('misc.users', 2) }}</h1>
    <div class="row">
        <div class="col-md-3 col-md-offset-9 text-right">
            {!! link_to_route('admin.users.create', trans('users.button.new_user'), [ ], ['class' => 'btn btn-info']) !!}
        </div>
    </div>

    @if ( !$users->count() )
        <div class="alert alert-info top-buffer"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('users.no_users')}}</div>
    @else
        {!! $users->render() !!}
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>{{ trans('misc.id') }}</th>
                    <th>{{ trans('users.first_name') }}</th>
                    <th>{{ trans('users.last_name') }}</th>
                    <th class="text-right">{{ trans('misc.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $users as $l_user )
                <tr>
                    <td>{{ $l_user->id }}</td>
                    <td>{{ $l_user->first_name }}</td>
                    <td>{{ $l_user->last_name }}</td>
                    <td class="text-right">
                        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.users.destroy', $l_user->id]]) !!}
                        {!! link_to_route('admin.users.show', trans('misc.button.details'), $l_user->id, ['class' => 'btn btn-info btn-xs']) !!}
                        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger btn-xs'.(($l_user->id == 1) ? ' disabled' : '')]) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
