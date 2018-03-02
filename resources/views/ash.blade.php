<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trans('ash.title') }} - {{ trans('ash.ticket') }} {{ $ticket->id }}</title>
    <link href="{{ ashAsset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ ashAsset('/css/flag-icon-min.css') }}" rel="stylesheet">
    <link href="{{ ashAsset('/css/custom.css') }}" rel="stylesheet">
    <script src="{{ ashAsset('/js/jquery.min.js') }}"></script>
    <script src="{{ ashAsset('/js/bootstrap.min.js') }}"></script>
</head>
<body class="ash">
    <div class="container">
        <div class="jumbotron">
            <div class="media">
                <div class="media-left">
                    <img class="img-responsive img-inline" src="/ash/logo/{{ $brand->id }}" alt='{{ $brand->company_name }}' />
                </div>
                <div class="media-body">
                    <h1>{{ trans('ash.title') }}</h1>
                    <h2>{{ $brand->company_name }}</h2>
                </div>
            </div>
        </div>
        <h1 class="page-header">{{ trans('ash.ticket') }} {{ $ticket->id }}</h1>
        <div class="row">
            <div class="col-md-3 col-md-offset-9 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('misc.language') }} <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach(config('app.locales') as $locale => $localeData)
                            <li><a href="/ash/locale/{{$locale}}"><span class="flag-icon flag-icon-{{$localeData[1]}}"></span> {{ $localeData[0] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="panel panel-danger top-buffer">
            <div class="panel-heading">
                {{ trans('ash.intro') }}
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                {{ $brand->introduction_text }}
            </div>
        </div>

        @if ($message)
            <div class="alert alert-info">
                {{ trans('ash.messages.'. $message) }}
            </div>
        @endif

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#basicinfo"><span class="glyphicon glyphicon-file"></span> {{ trans('ash.menu.basic') }}</a></li>
            <li><a data-toggle="tab" href="#events"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('ash.menu.technical') }}</a></li>
            <li><a data-toggle="tab" href="#whatsthis"><span class="glyphicon glyphicon-question-sign"></span> {{ trans('ash.menu.about') }}</a></li>
            <li><a data-toggle="tab" href="#resolved"><span class="glyphicon glyphicon-ok"></span> {{ trans('ash.menu.communication') }}</a></li>
        </ul>
        <div class="tab-content">
            <div id="basicinfo" class="tab-pane fade in active">
                <dl class="dl-horizontal">

                    <dt>{{ trans('ash.basic.ipAddress') }}</dt>
                    <dd>{{ $ticket->ip }}</dd>

                    @if (!empty($ticket->domain))
                        <dt>{{ trans('ash.basic.domainName') }}</dt>
                        <dd>{{ $ticket->domain }}</dd>
                    @endif

                    <dt>{{ trans('ash.basic.class') }}</dt>
                    <dd>{{ trans('classifications.' . $ticket->class_id . '.name') }}</dd>

                    <dt>{{ trans('ash.basic.type') }}</dt>
                    <dd>{{ trans('types.type.' . $ticket->type_id . '.name') }}</dd>

                    <dt>{{ trans('ash.basic.suggest') }}</dt>
                    <dd>{{ trans('types.type.' . $ticket->type_id . '.description') }}</dd>

                    <dt>{{ trans('ash.basic.firstSeen') }}</dt>
                    <dd>{{ $ticket->firstEvent[0]->seen }}</dd>

                    <dt>{{ trans('ash.basic.lastSeen') }}</dt>
                    <dd>{{ $ticket->lastEvent[0]->seen }}</dd>

                    <dt>{{ trans('ash.basic.reportCount') }}</dt>
                    <dd>{{ $ticket->events->count() }}</dd>

                    <dt>{{ trans('ash.basic.ticketStatus') }}</dt>
                    <dd>{{ trans('types.status.abusedesk.' . $ticket->status_id . '.name') }}</dd>

                    <dt>{{ trans('ash.basic.ticketCreated') }}</dt>
                    <dd>{{ $ticket->created_at }}</dd>

                    <dt>{{ trans('ash.basic.ticketModified') }}</dt>
                    <dd>{{ $ticket->updated_at }}</dd>

                    <dt>{{ trans('ash.basic.replyStatus') }}</dt>
                    <dd></dd>

                </dl>
            </div>

            <div id="events" class="tab-pane fade">
                @if ( !$ticket->events->count() )
                    {{ trans('ash.technical.collectError') }}
                @else
                    <table class="table table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>{{ trans('ash.technical.timestamp') }}</th>
                            <th>{{ trans('ash.technical.source') }}</th>
                            <th>{{ trans('ash.technical.information') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($ticket->events('desc')->get() as $event)

                            <tr>
                                <td>{{ $event->seen }}</td>
                                <td>{{ $event->source }}</td>
                                <td>
                                    <dl class="dl-horizontal">
                                        @foreach (json_decode($event->information, true) as $l1field => $l1value)
                                            @if (is_array($l1value))
                                                @foreach ($l1value as $l2field=>$l2value)
                                                    @if (is_array($l2value))
                                                        @foreach ($l2value as $l3field=>$l3value)
                                                            @if (is_array($l3value))
                                                                <dt>{{ ucfirst($l1field) . ' ' . ucfirst($l2field) . ' ' . ucfirst($l3field)}}</dt>
                                                                <dd>This is filtered due to fourth layer nesting</dd>
                                                            @else
                                                                <dt>{{ ucfirst($l1field) . ' ' . ucfirst($l2field) . ' ' . ucfirst($l3field)}}</dt>
                                                                <dd>{{ htmlentities($l3value) }}</dd>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <dt>{{ ucfirst($l1field) . ' ' . ucfirst($l2field) }}</dt>
                                                        <dd>{{ htmlentities($l2value) }}</dd>
                                                    @endif
                                                @endforeach
                                            @else
                                                <dt>{{ ucfirst($l1field) }}</dt>
                                                <dd>{{ htmlentities($l1value) }}</dd>
                                            @endif
                                        @endforeach
                                    </dl>
                                </td>
                            </tr>

                        @endforeach
                    </table>
                @endif
            </div>

            <div id="whatsthis" class="tab-pane fade">
                {!! trans('classifications.' . $ticket->class_id . '.description') !!}
            </div>

            <div id="resolved" class="tab-pane fade">
                @if (config('main.notes.enabled') == true && $ticket->status_id != 2)
                    <p>{{ trans('ash.communication.header') }}</p>
                    <form method="POST" accept-charset="UTF-8">
                        {!! Form::token() !!}
                        <div class="form-group">
                            {!! Form::label('text', trans('ash.communication.reply').':') !!}
                            {!! Form::textarea('text', null, ['size' => '30x5', 'placeholder' => trans('ash.communication.placeholder'), 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('enabled', trans('misc.status').':', ['class' => 'control-label']) !!}
                            {!! Form::select('changeStatus', $allowedChanges, $ticket->cust_status_id, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::submit(trans('ash.communication.submit'), ['class'=>'btn btn-success']) !!}
                        </div>
                    </form>

                    <h4>{{ trans('ash.communication.previousCommunication') }}</h4>
                    @if ( !$ticket->notes->count() )
                        {{ trans('ash.communication.noMessages') }}
                    @else
                        @foreach ($ticket->notes as $note)
                            @if ($note->hidden != true)
                                <div class="row">
                                    <div class="col-xs-11 {{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? '' : 'col-xs-offset-1' }}">
                                        <div class="panel panel-{{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? 'info' : 'primary' }}">
                                            <div class="panel-heading clearfix">
                                                <h3 class="panel-title pull-left">{{ trans('ash.communication.responseFrom') }}: {{ $note->submitter }}</h3>
                                                <span class="pull-right"><span class="glyphicon glyphicon-time"></span> {{ $note->created_at }}</span>
                                            </div>
                                            <div class="panel-body">
                                                {{ htmlentities($note->text) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @else
                    <p>{{ trans('ash.communication.closed') }}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
