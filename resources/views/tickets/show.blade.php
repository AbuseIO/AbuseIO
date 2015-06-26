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
            <dd>{{ $ticket->firstEvent[0]->timestamp }}</dd>

            <dt>{{ Lang::get('ash.basic.lastSeen') }}</dt>
            <dd>{{ $ticket->lastEvent[0]->timestamp }}</dd>

            <dt>{{ Lang::get('ash.basic.reportCount') }}</dt>
            <dd>{{ $ticket->events->count() }}</dd>

            <dt>{{ Lang::get('ash.basic.ticketStatus') }}</dt>
            <dd>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</dd>

            <dt>{{ Lang::get('ash.basic.replyStatus') }}</dt>
            <dd></dd>
        </dl>

        <h2>Events</h2>

        @if ( !$ticket->events->count() )
            {{ Lang::get('ash.technical.collectError') }}
        @else
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>{{ Lang::get('ash.technical.timestamp') }}</th>
                    <th>{{ Lang::get('ash.technical.source') }}</th>
                    <th>{{ Lang::get('ash.technical.information') }}</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($ticket->events as $event)

                    <tr>
                        <td>{{ $event->timestamp }}</td>
                        <td>{{ $event->source }}</td>
                        <td>
                            <dl class="dl-horizontal">
                                @foreach (json_decode($event->information) as $field => $value)

                                    <dt>{{ ucfirst($field) }}</dt>
                                    <dd>{{ htmlentities($value) }}</dd>

                                @endforeach
                            </dl>
                        </td>
                    </tr>

                @endforeach
            </table>
        @endif

        <h2>Notes</h2>

        {!! Form::model(new AbuseIO\Models\Note) !!}
        <div class="form-group">
            {!! Form::label('text', Lang::get('ash.communication.reply').':') !!}
            {!! Form::textarea('text', null, ['size' => '30x5', 'placeholder' => Lang::get('ash.communication.placeholder'), 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit(Lang::get('ash.communication.submit'), ['class'=>'btn primary']) !!}
        </div>
        {!! Form::close() !!}

        <h4>{{ Lang::get('ash.communication.previousCommunication') }}</h4>
        @if ( !$ticket->notes->count() )
            {{ Lang::get('ash.communication.noMessages') }}
        @else
            @foreach ($ticket->notes as $note)

                <div class="panel panel-{{ ($note->submitter == 'contact') ? 'default' : 'primary' }}">

                    <div class="panel-heading"><h3 class="panel-title"> {{ Lang::get('ash.communication.responseFrom') }} {{ Lang::get('ash.communication.'.$note->submitter) }}
                        </h3><span class="pull-right">{{ $note->created_at }}</span>
                    </div>

                    <div class="panel-body">
                        {{ htmlentities($note->text) }}
                    </div>

                </div>
            @endforeach
        @endif
    </div>

    </div>
@endsection
