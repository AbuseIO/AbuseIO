@extends('app')

@section('content')

    <div class="container">
        <h1 class="page-header">Ticket {{ $ticket->id }} details</h1>

        <dl class="dl-horizontal">
            <dt>{{ Lang::get('ash.basic.ip') }}</dt>
            <dd>{{ $ticket->ip }}</dd>

            @if (gethostbyaddr($ticket->ip) !== false)
                <dt>{{ Lang::get('ash.basic.ptr') }}</dt>
                <dd>{{ gethostbyaddr($ticket->ip) }}</dd>
            @endif

            @if (!empty($ticket->domain))
                <dt>{{ Lang::get('ash.basic.domain') }}</dt>
                <dd>{{ $ticket->domain }}</dd>
            @endif

            <dt>{{ Lang::get('ash.basic.class') }}</dt>
            <dd>{{ Lang::get('classifications.' . $ticket->class_id . '.name') }}</dd>

            <dt>{{ Lang::get('ash.basic.type') }}</dt>
            <dd>{{ Lang::get('types.type.' . $ticket->type_id . '.name') }}</dd>

            <dt>{{ Lang::get('ash.basic.suggest') }}</dt>
            <dd>{{ Lang::get('types.type.' . $ticket->type_id . '.description') }}</dd>

            <dt>{{ Lang::get('ash.basic.firstSeen') }}</dt>
            <dd>{{ $ticket->first_seen }}</dd>

            <dt>{{ Lang::get('ash.basic.lastSeen') }}</dt>
            <dd>{{ $ticket->last_seen }}</dd>

            <dt>{{ Lang::get('ash.basic.reportCount') }}</dt>
            <dd>{{ $ticket->report_count }}</dd>

            <dt>{{ Lang::get('ash.basic.ticketStatus') }}</dt>
            <dd>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</dd>

            <dt>{{ Lang::get('ash.basic.replyStatus') }}</dt>
            <dd></dd>
        </dl>

        <h2>Events</h2>
        table with link to evidence

        <h2>Notes</h2>

    </div>
@endsection
