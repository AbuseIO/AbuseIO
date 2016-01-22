@extends('app')

@section('extrajs')
    <script src="{{ asset('/js/tickets.js') }}"></script>
@stop

@section('content')
    <h1 class="page-header">{{ trans('tickets.headers.detail') }}: {{ $ticket->id }}</h1>
    <div class="row">
        <div class="col-md-8 col-md-offset-4 text-right">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! trans('tickets.button.update_contact') !!} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li{!! ($ticket->ip_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.update', trans('misc.ip').' '.trans('misc.contact'), [$ticket->id, 'ip']) !!}</li>
                    <li{!! ($ticket->domain_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.update', trans('misc.domain').' '.trans('misc.contact'), [$ticket->id, 'domain']) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li{!! ($ticket->ip_contact_reference == 'UNDEF' || $ticket->domain_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.update', trans('misc.both'), [$ticket->id]) !!}</li>
                </ul>
            </div>
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ trans('tickets.button.send_notification') }} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li{!! ($ticket->ip_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.notify', trans('misc.ip').' '.trans('misc.contact'), [$ticket->id, 'ip']) !!}</li>
                    <li{!! ($ticket->domain_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.notify', trans('misc.domain').' '.trans('misc.contact'), [$ticket->id, 'domain']) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li{!! ($ticket->ip_contact_reference == 'UNDEF' || $ticket->domain_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.notify', trans('misc.both'), [$ticket->id]) !!}</li>
                </ul>
            </div>
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ trans('tickets.ticket') }} {{ trans('misc.status') }} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">Set new status to:</li>
                    <li{!! ($ticket->status_id == 1) ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.status', trans('tickets.open'), [$ticket->id, 'open']) !!}</li>
                    <li{!! ($ticket->status_id == 2) ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.status', trans('tickets.closed'), [$ticket->id, 'closed']) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li{!! ($ticket->status_id == 3) ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.status', trans('tickets.escalated'), [$ticket->id, 'escalated']) !!}</li>
                    <li{!! ($ticket->status_id == 4) ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.status', trans('tickets.ignored'), [$ticket->id, 'ignored']) !!}</li>
                    <li{!! ($ticket->status_id == 5) ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.status', trans('tickets.resolved'), [$ticket->id, 'resolved']) !!}</li>
                </ul>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#info"><span class="glyphicon glyphicon-file"></span> {{ trans('tickets.information') }}</a></li>
        <li><a data-toggle="tab" href="#events"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('tickets.events') }}</a></li>
        <li><a data-toggle="tab" href="#communication"><span class="glyphicon glyphicon-envelope"></span> {{ trans('tickets.communication') }}</a></li>
    </ul>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <dl class="dl-horizontal">
                <dt>{{ trans('misc.ip_address') }}</dt>
                <dd>{{ $ticket->ip }}</dd>

                @if (!empty($ticket->domain))
                    <dt>Domain name</dt>
                    <dd>{{ $ticket->domain }}</dd>
                @endif

                <dt>{{ trans('misc.classification') }}</dt>
                <dd>{{ Lang::get('classifications.' . $ticket->class_id . '.name') }}</dd>

                <dt>{{ trans('misc.type') }}</dt>
                <dd>{{ Lang::get('types.type.' . $ticket->type_id . '.name') }}</dd>

                <dt>{{ trans('tickets.action_req') }}</dt>
                <dd>{{ Lang::get('types.type.' . $ticket->type_id . '.description') }}</dd>

                <dt>{{ trans('tickets.first_seen') }}</dt>
                <dd>{{ $ticket->firstEvent[0]->seen }}</dd>

                <dt>{{ trans('tickets.last_seen') }}</dt>
                <dd>{{ $ticket->lastEvent[0]->seen }}</dd>

                <dt>{{ trans('tickets.events') }}</dt>
                <dd>{{ $ticket->events->count() }}</dd>

                <dt>{{ trans('misc.status') }}</dt>
                <dd><span class="label label-{{ $ticket_class }}">{{ trans('types.status.' . $ticket->status_id . '.name') }}</span></dd>

                <dt>{{ trans('tickets.created') }}</dt>
                <dd>{{ $ticket->created_at }}</dd>

                <dt>{{ trans('tickets.modified') }}</dt>
                <dd>{{ $ticket->updated_at }}</dd>

                <dt>{{ trans('tickets.last_notification') }}</dt>
                @if ($ticket->last_notify_count == 0)
                <dd>Never</dd>
                @else
                <dd>{{ $ticket->last_notified }} (event: {{ $ticket->last_notify_count }})</dd>
                @endif

                <dt>{{ trans('tickets.total_notifications') }} {{ trans('misc.ip') }}</dt>
                <dd>{{ $ticket->ip_contact_notified_count }}</dd>

                <dt>{{ trans('tickets.total_notifications') }} {{ trans('misc.domain') }}</dt>
                <dd>{{ $ticket->domain_contact_notified_count }}</dd>

                <dt>{{ trans('tickets.reply_status') }}</dt>
                <dd></dd>

                @if ($ticket->ip_contact_reference != 'UNDEF')
                <dt>{{ trans('tickets.ashlink') }} {{ trans('misc.ip')}}</dt>
                <dd>
                    {!! link_to(
                        "/ash/collect/$ticket->id/" . md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference),
                        'http://' . Request::server('SERVER_NAME') . "/ash/collect/$ticket->id/"
                         . md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference)
                    ) !!}
                </dd>
                @endif

                @if ($ticket->domain_contact_reference != 'UNDEF')
                <dt>{{ trans('tickets.ashlink') }} {{ trans('misc.domain')}}</dt>
                <dd>
                    {!! link_to(
                        "/ash/collect/$ticket->id/" . md5($ticket->id . $ticket->domain . $ticket->domain_contact_reference),
                        'http://' . Request::server('SERVER_NAME') . "/ash/collect/$ticket->id/"
                         . md5($ticket->id . $ticket->domain . $ticket->domain_contact_reference)
                    ) !!}
                </dd>
                @endif
            </dl>

            @if ($ticket->ip_contact_reference != 'UNDEF')
            <h4>{{ trans('misc.ip') }} {{ trans('misc.contact') }}:</h4>
            <dl class="dl-horizontal">
                <dt>{{ trans('contacts.reference') }}</dt>
                <dd>{{ $ticket->ip_contact_reference }}</dd>

                <dt>{{ trans('misc.name') }}</dt>
                <dd>{{ $ticket->ip_contact_name }}</dd>

                <dt>{{ trans('misc.email') }}</dt>
                <dd>{{ $ticket->ip_contact_email }}</dd>

                <dt>{{ trans('contacts.api_host') }}</dt>
                <dd>{{ $ticket->ip_contact_api_host }}</dd>
            </dl>
            @endif

            @if ($ticket->domain_contact_reference != 'UNDEF')
            <h4>{{ trans('misc.domain') }} {{ trans('misc.contact') }}:</h4>
            <dl class="dl-horizontal">
                <dt>{{ trans('contacts.reference') }}</dt>
                <dd>{{ $ticket->domain_contact_reference }}</dd>

                <dt>{{ trans('misc.name') }}</dt>
                <dd>{{ $ticket->domain_contact_name }}</dd>

                <dt>{{ trans('misc.email') }}</dt>
                <dd>{{ $ticket->domain_contact_email }}</dd>

                <dt>{{ trans('contacts.api_host') }}</dt>
                <dd>{{ $ticket->domain_contact_api_host }}</dd>
            </dl>
            @endif

        </div>
        <div id="events" class="tab-pane fade">
        @if ( !$ticket->events->count() )
            <div class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> No events found</div>
        @else
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th>{{ trans('tickets.timestamp') }}</th>
                        <th>{{ trans('tickets.source') }}</th>
                        <th>{{ trans('tickets.information') }}</th>
                        <th>{{ trans('tickets.evidence') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($ticket->events as $event)
                    <tr>
                        <td>{{ $event->seen }}</td>
                        <td>{{ $event->source }}</td>
                        <td>
                            <dl class="dl-horizontal">
                            @if (!is_array(json_decode($event->information, true)))
                                <dt>Parser Error</dt>
                                <dd>The parser not provider valid event data. Contact the administrator for the evidence related to this event</dd>
                            @else
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
                            @endif
                            </dl>
                        </td>
                        <td><a href='{{ Request::url() }}/evidence/{{ $event->evidences[0]->id }}/download'>{{ trans('ash.communication.download') }}</a> - <a href="{{ Request::url() }}/evidence/{{ $event->evidences[0]->id }}/view">{{ trans('ash.communication.view') }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        </div>
        <div id="communication" class="tab-pane fade">
            @if ( !$ticket->notes->count() )
                <div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('ash.communication.noMessages') }}</div>
            @else
                @foreach ($ticket->notes as $note)
                <div class="row">
                    <div class="col-xs-11 {{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? '' : 'col-xs-offset-1' }}">
                        <div class="ticket-note panel ticket-hover-group panel-default{{ $note->hidden == true ? ' panel-hidden' : '' }}{{ $note->viewed == false ? ' panel-info' : '' }}">
                            <div class="panel-heading clearfix">
                                <h3 class="panel-title pull-left{{ ($note->viewed == true) ? ' text-muted' : '' }}">{{ trans('ash.communication.responseFrom') }}: {{ $note->submitter }}</h3>
                                <span class="pull-left">&nbsp;</span>
                                <div class="pull-left ticket-hover-toggle invisible">
                                    <button type="button" class="btn btnFlip btnHide btn-xs btn-{{ ($note->hidden == true) ? 'warning' : 'success' }}" value="{{ $note->id }}">
                                        <span {!! ($note->hidden == true) ? '' : 'class="hidden"' !!}>{{ trans('misc.button.hidden') }}</span><span {!! ($note->hidden == true) ? 'class="hidden"' : '' !!}>{{ trans('misc.button.visible') }}</span>
                                    </button>
                                    <button type="button" class="btn btnFlip btnRead btn-xs btn-{{ ($note->viewed == true) ? 'success' : 'warning' }}" value="{{ $note->id }}">
                                        <span {!! ($note->viewed == true) ? '' : 'class="hidden"' !!} >{{ trans('misc.button.read') }}</span><span {!! ($note->viewed == true) ? 'class="hidden"' : '' !!}>{{ trans('misc.button.unread') }}</span>
                                    </button>
                                    <button type="button" class="btn btnFlip btnDelete btn-xs btn-danger" value="{{ $note->id }}">Delete</button>
                                </div>

                                <span class="pull-right{{ ($note->viewed == true) ? ' text-muted' : '' }}"><span class="glyphicon glyphicon-time"></span> {{ $note->created_at }}</span>
                            </div>
                            <div class="panel-body{{ ($note->viewed == true) ? ' text-muted' : '' }}">
                                {{ htmlentities($note->text) }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
                <div class="row">
                    <div class="col-xs-11 col-xs-offset-1">
                        {!! Form::model(new AbuseIO\Models\Note, ['route' => 'admin.notes.store', 'class' => 'form-horizontal']) !!}
                        {!! Form::hidden('ticket_id', $ticket->id) !!}
                        {!! Form::label('text', trans('ash.communication.reply')) !!}
                        {!! Form::textarea('text', null, ['size' => '30x5', 'placeholder' => trans('ash.communication.placeholder_admin'), 'class' => 'form-control']) !!}
                        <div class="checkbox"><label>{!! Form::checkbox('hidden') !!} {!! trans('misc.button.hidden') !!}</label></div>
                        {!! Form::submit(trans('ash.communication.submit'), ['class'=>'btn btn-success']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
        </div>
    </div>
@endsection
