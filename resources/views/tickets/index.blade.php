@extends('app')

@section('content')
    <h1 class="page-header">Tickets</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-8 text-right">
            [ Pulldown VIEW ]<!-- This is a preset view select for some default reports, like open tickets, or escalations -->
            {!! link_to_route('admin.tickets.create', 'Create Ticket', [], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.export.tickets', 'CSV Export', ['format' => 'csv'], ['class' => 'btn btn-info']) !!}  
        </div>
    </div>
    @if ( !$tickets->count() )
    <div class="alert alert-info">You have no tickets yet</div>
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
<!-- $ticket->firstEvent[0]->timestamp -->
                        <td>{{ date('d-m-Y H:i', $ticket->firstEvent[0]->timestamp) }}</td>
<!-- $ticket->lastEvent[0]->timestamp -->
                        <td>{{ date('d-m-Y H:i', $ticket->lastEvent[0]->timestamp) }}</td>
                        <td>{{ $ticket->events->count() }}</td>
                        <td>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</td>
                        <td>
                            {!! link_to_route('admin.tickets.show', 'Details', [$ticket->id], ['class' => 'btn btn-info']) !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

    </div>

@endsection
