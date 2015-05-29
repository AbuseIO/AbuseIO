@extends('app')

@section('content')

    <div class="container">

        <div class="row">
            <div  class="col-md-9" ><h1 class="page-header">Tickets</h1></div>
            <div  class="col-md-3 pagination">
                {!! link_to_route('admin.tickets.create', 'Create Ticket', '', array('class' => 'btn btn-info')) !!}
                {!! link_to_route('admin.export.tickets', 'CSV Export', array('format' => 'csv'), array('class' => 'btn btn-info')) !!}
            </div>
        </div>

        @if ( !$tickets->count() )
            You have no tickets yet
        @else
            {!! $tickets->render() !!}

            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>Ticket</th>
                    <th>IP</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Classification</th>
                    <th>First Seen</th>
                    <th>Last Seen</th>
                    <th>Count</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach( $tickets as $ticket )
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->ip }}</td>
                        <td>{{ $ticket->ip_contact_name }} ({{ $ticket->ip_contact_reference }})</td>
                        <td>{{ Lang::get('types.type.' . $ticket->type_id . '.name') }}</td>
                        <td>{{ Lang::get('classifications.' . $ticket->class_id . '.name') }}</td>
                        <td>{{ $ticket->first_seen }}</td>
                        <td>{{ $ticket->last_seen }}</td>
                        <td></td>
                        <td>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</td>
                        <td>
                            {!! link_to_route('admin.tickets.show', 'Details', array($ticket->id), array('class' => 'btn btn-info')) !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

    </div>

@endsection
