@extends('app')

@section('content')
    <h1 class="page-header">Contacts</h1>
    <div class="row">
        <div class="col-md-3 col-md-offset-9 text-right">
            {!! link_to_route('admin.contacts.create', 'Create Contact', [ ], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.export.contacts', 'CSV Export', ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
        </div>
    </div>

    @if ( !$contacts->count() )
        <div class="alert alert-info">You have no contacts yet</div>
    @else
        {!! $contacts->render() !!}
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>RPC Host</th>
                    <th>Notify</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $contacts as $contact )
                <tr>
                    <td>{{ $contact->reference }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->rpc_host }}</td>
                    <td>{{ $contact->auto_notify ? 'Automatic' : 'Manual' }}</td>
                    <td class="text-right">
                        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.contacts.destroy', $contact->id]]) !!}
                        {!! link_to_route('admin.contacts.show', 'Details', $contact->id, ['class' => 'btn btn-info btn-xs']) !!}
                        {!! link_to_route('admin.contacts.edit', 'Edit', $contact->id, ['class' => 'btn btn-info btn-xs']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
