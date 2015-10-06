@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.contacts') }}</h1>
    <div class="row">
        <div class="col-md-3 col-md-offset-9 text-right">
            {!! link_to_route('admin.contacts.create', trans('misc.button.new_contact'), [ ], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.export.contacts', trans('misc.button.csv_export'), ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
        </div>
    </div>

    @if ( !$contacts->count() )
        <div class="alert alert-info top-buffer">{{ trans('contacts.no_contacts')}}</div>
    @else
        {!! $contacts->render() !!}
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>{{ trans('misc.button.details') }}</th>
                    <th>{{ trans('misc.name') }}</th>
                    <th>{{ trans('misc.email') }}</th>
                    <th>{{ trans('contacts.rpchost') }}</th>
                    <th>{{ trans('contacts.notification') }}</th>
                    <th class="text-right">{{ trans('misc.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $contacts as $contact )
                <tr>
                    <td>{{ $contact->reference }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->rpc_host }}</td>
                    <td>{{ $contact->auto_notify ? trans('misc.automatic') : trans('misc.manual') }}</td>
                    <td class="text-right">
                        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.contacts.destroy', $contact->id]]) !!}
                        {!! link_to_route('admin.contacts.show', trans('misc.button.details'), $contact->id, ['class' => 'btn btn-info btn-xs']) !!}
                        {!! link_to_route('admin.contacts.edit', trans('misc.button.edit'), $contact->id, ['class' => 'btn btn-info btn-xs']) !!}
                        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
