@extends('app')

@section('content')
    <h1 class="page-header">{{ trans_choice('misc.accounts', 2) }}</h1>
    <div class="row">
        <div class="col-md-3 col-md-offset-9 text-right">
            {!! link_to_route('admin.accounts.create', trans('accounts.button.new_account'), [ ], ['class' => 'btn btn-info']) !!}
        </div>
    </div>

    @if ( !$accounts->count() )
        <div class="alert alert-info top-buffer"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('accounts.no_accounts')}}</div>
    @else
        {!! $accounts->render() !!}
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>{{ trans('misc.name') }}</th>
                    <th>{{ trans('misc.description') }}</th>
                    <th class="text-right">{{ trans('misc.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $accounts as $account )
                <tr>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->description }}</td>
                    <td class="text-right">
                        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.accounts.destroy', $account->id]]) !!}
                        {!! link_to_route('admin.accounts.show', trans('misc.button.details'), $account->id, ['class' => 'btn btn-info btn-xs']) !!}
                        {!! link_to_route('admin.accounts.edit', trans('misc.button.edit'), $account->id, ['class' => 'btn btn-info btn-xs']) !!}
                        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger btn-xs'.(($account->id == 1) ? ' disabled' : '')]) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
