<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Lang::get('ash.title') }} {{ $ticket->id }}</title>
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>

</head>
<body>
<div class="header_wrapper">
    <div class="container header"><img class="img-responsive" src="{{ asset('/images/logo_150.png') }}" alt='AbuseIO' /></div>
</div>
<div class="container">
    <h2>{{ Lang::get('ash.title') }} {{ $ticket->id }}</h2>
    <div class="panel panel-danger">
        <div class="panel-heading">
            {{ Lang::get('ash.intro') }}
        </div>
    </div>

    @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('messageType') }}">
            <span class="glyphicon glyphicon-{{ Session::get('messageIcon') }}"></span>
            {{ Lang::get('ash.messages.'. Session::get('message')) }}
        </div>
    @endif

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#basicinfo"><span class="glyphicon glyphicon-file"></span> {{ Lang::get('ash.menu.basic') }}</a></li>
        <li><a data-toggle="tab" href="#events"><span class="glyphicon glyphicon-list-alt"></span> {{ Lang::get('ash.menu.technical') }}</a></li>
        <li><a data-toggle="tab" href="#whatsthis"><span class="glyphicon glyphicon-question-sign"></span> {{ Lang::get('ash.menu.about') }}</a></li>
        <li><a data-toggle="tab" href="#resolved"><span class="glyphicon glyphicon-ok"></span> {{ Lang::get('ash.menu.communication') }}</a></li>
    </ul>
    <div class="tab-content">
        <div id="basicinfo" class="tab-pane fade in active">
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
                <dd>{{ date('d-m-Y H:i', $ticket->firstEvent[0]->timestamp) }}</dd>

                <dt>{{ Lang::get('ash.basic.lastSeen') }}</dt>
                <dd>{{ date('d-m-Y H:i', $ticket->lastEvent[0]->timestamp) }}</dd>

                <dt>{{ Lang::get('ash.basic.reportCount') }}</dt>
                <dd>{{ $ticket->events->count() }}</dd>

                <dt>{{ Lang::get('ash.basic.ticketStatus') }}</dt>
                <dd>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</dd>

                <dt>{{ Lang::get('ash.basic.ticketCreated') }}</dt>
                <dd>{{ $ticket->created_at }}</dd>

                <dt>{{ Lang::get('ash.basic.ticketModified') }}</dt>
                <dd>{{ $ticket->updated_at }}</dd>

                <dt>{{ Lang::get('ash.basic.replyStatus') }}</dt>
                <dd></dd>

            </dl>
        </div>

        <div id="events" class="tab-pane fade">
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
                            <td>{{ date('d-m-Y H:i', $event->timestamp) }}</td>
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
        </div>

        <div id="whatsthis" class="tab-pane fade">
            {!! Lang::get('classifications.' . $ticket->class_id . '.description') !!}
        </div>

        <div id="resolved" class="tab-pane fade">
            <p>{{ Lang::get('ash.communication.header') }}</p>

            {!! Form::model(['method' => 'put']) !!}
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

                        <div class="panel-heading">
                            <div class="pull-left">
                                <h3 class="panel-title">{{ Lang::get('ash.communication.responseFrom') }} {{ Lang::get('ash.communication.'.$note->submitter) }}</h3>
                            </div>
                            <div class="pull-right">
                                <span>{{ $note->created_at }}</span>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            {{ htmlentities($note->text) }}
                        </div>

                    </div>
                @endforeach
            @endif
        </div>

    </div>

</div>

</body>
</html>
