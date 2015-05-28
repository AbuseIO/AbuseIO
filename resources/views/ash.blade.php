<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ASH - Ticket {{ $ticket->id }}</title>
    <link rel="stylesheet" href="/css/ash/bootstrap.min.css">
    <link rel="stylesheet" href="/css/ash/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/ash/custom.css" />
    <script src="/js/ash/jquery.min.js"></script>
    <script src="/js/ash/bootstrap.min.js"></script>
</head>
<body>
<div class="header_wrapper"><div class="container header"><img class="img-responsive" src="images/logo.svg" alt=''/></div></div>
<div class="container">
    <h2>ASH - Ticket {{ $ticket->id }}</h2>
    <div class="panel panel-danger">
        <div class="panel-heading">
            You are seeing this page because we have detected suspicious activities from your IP address, Domain name or E-Mail address.<br/>
            On this page you will find all the information about these activities and the underlying problem.
        </div>
    </div>

    @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('messageType') }}">
            <span class="glyphicon glyphicon-{{ Session::get('messageIcon') }}"></span>
            {{ Session::get('message') }}
        </div>
    @endif

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#basicinfo"><span class="glyphicon glyphicon-file"></span> Basic Information</a></li>
        <li><a data-toggle="tab" href="#events"><span class="glyphicon glyphicon-list-alt"></span> Technical Details</a></li>
        <li><a data-toggle="tab" href="#whatsthis"><span class="glyphicon glyphicon-question-sign"></span> What is this?</a></li>
        <li><a data-toggle="tab" href="#resolved"><span class="glyphicon glyphicon-ok"></span> Questions / Resolved!</a></li>
    </ul>
    <div class="tab-content">
        <div id="basicinfo" class="tab-pane fade in active">
            <dl class="dl-horizontal">

                <dt>IP address</dt>
                <dd>{{ $ticket->ip }}</dd>

                @if (gethostbyaddr($ticket->ip) !== false)
                    <dt>Reverse DNS</dt>
                    <dd>{{ gethostbyaddr($ticket->ip) }}</dd>
                @endif

                @if (!empty($ticket->domain))
                    <dt>Domain</dt>
                    <dd>{{ $ticket->domain }}</dd>
                @endif

                <dt>Classification</dt>
                <dd>{{ $ticket->class_id }}</dd>

                <dt>Type</dt>
                <dd>{{ $ticket->type_id }}</dd>

                <dt>First Seen</dt>
                <dd>{{ $ticket->first_seen }}</dd>

                <dt>Last Seen</dt>
                <dd>{{ $ticket->last_seen }}</dd>

                <dt>Report count</dt>
                <dd>{{ $ticket->report_count }}</dd>

                <dt>Ticket status</dt>
                <dd>{{ $ticket->status_id }}</dd>

                <dt>Reply status</dt>
                <dd></dd>

            </dl>
        </div>

        <div id="events" class="tab-pane fade">
            @if ( !$events->count() )
                An error occurred while collecting event information
            @else
                <table class="table table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Seen</th>
                        <th>Source</th>
                        <th>Event information</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($events as $event)

                        <tr>
                            <td>{{ $event->timestamp }}</td>
                            <td>{{ $event->source }}</td>
                            <td>
                                @foreach (json_decode($event->information) as $field => $value)

                                    <dl class="dl-horizontal">
                                        <dt>{{ ucfirst($field) }}</dt>
                                        <dd>{{ htmlentities($value) }}</dd>
                                    </dl>

                                @endforeach
                            </td>
                        </tr>

                    @endforeach
                </table>
            @endif
        </div>

        <div id="whatsthis" class="tab-pane fade">
            $infotext;
        </div>

        <div id="resolved" class="tab-pane fade">
            <p>You can use the below form to reply to this ticket or implemented solution and close the ticket.</p>

            {!! $disabled = true //Form::model(new AbuseIO\Models\Note, ['route' => ['ash.collect.store']]) !!}
            <div class="form-group">
                {!! Form::label('text', 'Reply:') !!}
                {!! Form::text('text') !!}
            </div>
            <div class="form-group">
                {!! Form::submit('Submit', ['class'=>'btn primary']) !!}
            </div>
            {!! Form::close() !!}

            <h4>Previous communication</h4>
            @if ( !$notes->count() )
                No interaction has been done yet
            @else
                @foreach ($notes as $note)

                    <div class="panel panel-$panel_type">

                        <div class="panel-heading"><h3 class="panel-title">Response from {{ $note->submitter }}
                            </h3><span class="pull-right">{{ $note->timestamp }}</span>
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
