@extends('app')

@section('content')

    <div class="container">

        <div class="row">
            <div  class="col-md-9" ><h1 class="page-header">Contacts</h1></div>
            <div  class="col-md-3 pagination">
                {!! link_to_route('admin.contacts.create', 'Create Contact', '', array('class' => 'btn btn-info')) !!}
                {!! link_to_route('admin.export.contacts', 'CSV Export', array('format' => 'csv'), array('class' => 'btn btn-info')) !!}
            </div>
        </div>

        @if ( !$contacts->count() )
            You have no contacts yet
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
                </tr>
                </thead>
                <tbody>

                @foreach( $contacts as $contact )
                    <tr>
                        {!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('admin.contacts.destroy', $contact->id))) !!}
                        <td>{{ $contact->reference }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->rpc_host }}</td>
                        <td>{{ $contact->auto_notify ? 'Automatic' : 'Manual' }}</td>
                        <td>
                            {!! link_to_route('admin.contacts.show', 'Details', array($contact->id), array('class' => 'btn btn-info')) !!}
                            {!! link_to_route('admin.contacts.edit', 'Edit', array($contact->id), array('class' => 'btn btn-info')) !!}
                            {!! Form::submit('Delete', array('class' => 'btn btn-danger')) !!}
                        </td>
                        {!! Form::close() !!}
                    </tr>
                @endforeach
            </table>
        @endif

    </div>

@endsection
