@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.tickets') }}</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-8 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('misc.button.filters') }} <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                    <li>{!! link_to('admin/tickets', trans('tickets.button.all_tickets')) !!}</li>
                    <li>{!! link_to('admin/tickets/status/open', trans('tickets.button.open_tickets')) !!}</li>
                    <li>{!! link_to('admin/tickets/status/closed', trans('tickets.button.closed_tickets')) !!}</li>
                </ul>
            </div>
            {!! link_to_route('admin.tickets.create', trans('tickets.button.new_ticket'), [], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.export.tickets', trans('misc.button.csv_export'), ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
        </div>
    </div>
    @if ( !$tickets->count() )
        <div class="alert alert-info top-buffer"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('tickets.no_tickets') }}</div>
    @else
        {!! $tickets->render() !!}
        <table class="table table-striped table-condensed top-buffer">
            <thead>
                <tr>
                    <th>{{ trans('misc.ticket_id') }}</th>
                    <th>{{ trans('misc.ip') }}</th>
                    <th>{{ trans('misc.contact') }}</th>
                    <th>{{ trans('misc.type') }}</th>
                    <th>{{ trans('misc.classification') }}</th>
                    <th>{{ trans('tickets.first_seen') }}</th>
                    <th>{{ trans('tickets.last_seen') }}</th>
                    <th>{{ trans('tickets.count') }}</th>
                    <th>{{ trans('misc.status') }}</th>
                    <th class="text-right">{{ trans('misc.action') }}</th>
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
                        {!! link_to_route('admin.tickets.show', trans('misc.button.details'), [$ticket->id], ['class' => 'btn btn-info btn-xs']) !!}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    </div>
@endsection
