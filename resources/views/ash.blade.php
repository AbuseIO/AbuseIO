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
    <script src="{{ ashAsset('/js/bootstrap.bundle.min.js') }}"></script>
</head>
<body class="ash">
    <div class="container">
    <div class="jumbotron">
            <div class="media">
                <div class="media-left">
                    <img class="img-responsive img-inline" src="/ash/logo/{{ $brand->id }}" alt='{{ $brand->company_name }}' />
                </div>
                <div class="media-body">
                    <h1 class="display-4">{{ trans('ash.title') }}</h1>
                    <h2 class="h3">{{ $brand->company_name }}</h2>
                </div>
            </div>
        </div>
        <h1 class="page-header">{{ trans('ash.ticket') }} {{ $ticket->id }}</h1>
        <div class="row">
            <div class="col-md-3 offset-md-9 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('misc.language') }} <i class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu">
                        @foreach(config('app.locales') as $locale => $localeData)
                            <li><a class="dropdown-item" href="/ash/locale/{{$locale}}"><span class="flag-icon flag-icon-{{$localeData[1]}}"></span> {{ $localeData[0] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="card border-danger top-buffer">
            <div class="card-header bg-danger text-white">
                {{ trans('ash.intro') }}
            </div>
        </div>
        <div class="card top-buffer">
            <div class="card-header">
                {{ $brand->introduction_text }}
            </div>
        </div>

        @if ($message)
            <div class="alert alert-info">
                {{ trans('ash.messages.'. $message) }}
            </div>
        @endif

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#basicinfo"><i class="fa fa-file"></i> {{ trans('ash.menu.basic') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#events"><i class="fa fa-list-alt"></i> {{ trans('ash.menu.technical') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#whatsthis"><i class="fa fa-question-circle"></i> {{ trans('ash.menu.about') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#resolved"><i class="fa fa-check"></i> {{ trans('ash.menu.communication') }}</a></li>
        </ul>
        <div class="tab-content">
            <div id="basicinfo" class="tab-pane fade show active">
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
                    @php($firstEvent = $ticket->events('asc')->first())
                    <dd>{{ $firstEvent ? $firstEvent->seen : trans('misc.never') }}</dd>

                    <dt>{{ trans('ash.basic.lastSeen') }}</dt>
                    @php($lastEvent = $ticket->events('desc')->first())
                    <dd>{{ $lastEvent ? $lastEvent->seen : trans('misc.never') }}</dd>

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
                    <table class="table table-striped table-sm">
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
                    <form method="POST" action="{{ route('ash.addnote', [$ticket->id, $token]) }}" accept-charset="UTF-8">
                        @csrf
                        <div class="form-group">
                            <label for="text">{{ trans('ash.communication.reply') }}:</label>
                            <textarea name="text" id="text" rows="5" cols="30" placeholder="{{ trans('ash.communication.placeholder') }}" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="changeStatus" class="control-label">{{ trans('misc.status') }}:</label>
                            <select name="changeStatus" id="changeStatus" class="form-control">
                                @foreach($allowedChanges as $key => $value)
                                    <option value="{{ $key }}" {{ $ticket->contact_status_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{ trans('ash.communication.submit') }}</button>
                        </div>
                    </form>

                    <h4>{{ trans('ash.communication.previousCommunication') }}</h4>
                    @if ( !$ticket->notes->count() )
                        {{ trans('ash.communication.noMessages') }}
                    @else
                        @foreach ($ticket->notes as $note)
                            @if ($note->hidden != true)
                                <div class="row">
                                    <div class="col-11 {{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? '' : 'offset-1' }}">
                                        <div class="card border-{{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? 'info' : 'primary' }}">
                                            <div class="card-header clearfix {{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? 'bg-info text-white' : 'bg-primary text-white' }}">
                                                <h3 class="card-title float-start">{{ trans('ash.communication.responseFrom') }}: {{ $note->submitter }}</h3>
                                                <span class="float-end"><i class="fa fa-clock-o"></i> {{ $note->created_at }}</span>
                                            </div>
                                            <div class="card-body">
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
    <script>
        // Activate tab from URL fragment on load (e.g., #resolved)
        (function() {
            var hash = window.location.hash;
            if (hash) {
                var $tab = $('.nav.nav-tabs a[href="' + hash + '"]');
                if (!$tab.length) {
                    $tab = $('.nav-tabs a[href="' + hash + '"]');
                }
                if ($tab.length && typeof $tab.tab === 'function') {
                    $tab.tab('show');
                }
            }

            // Keep fragment synced when switching tabs
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target) {
                    if (history.replaceState) {
                        history.replaceState(null, null, target);
                    } else {
                        window.location.hash = target;
                    }
                }
            });
        })();
    </script>
</body>
</html>
