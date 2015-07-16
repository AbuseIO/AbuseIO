@extends('app')

@section('content')
    <h1 class="page-header">Tickets</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-8 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Views <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                    <li>{!! link_to('admin/tickets', 'All tickets') !!}</li>
                    <li>{!! link_to('admin/tickets/status/open', 'Open tickets') !!}</li>
                    <li>{!! link_to('admin/tickets/status/closed', 'Closed tickets') !!}</li>
                </ul>
            </div>
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
                    <th class="text-right">Action</th>
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
                    <td>{{ date('d-m-Y H:i', $ticket->firstEvent[0]->timestamp) }}</td>
                    <td>{{ date('d-m-Y H:i', $ticket->lastEvent[0]->timestamp) }}</td>
                    <td>{{ $ticket->events->count() }}</td>
                    <td>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</td>
                    <td class="text-right">
                        {!! link_to_route('admin.tickets.show', 'Details', [$ticket->id], ['class' => 'btn btn-info btn-xs']) !!}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    </div>
@endsection
