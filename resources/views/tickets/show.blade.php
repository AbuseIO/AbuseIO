@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('tickets.headers.detail') }}: {{ $ticket->id }}</h1>
    <div class="row">
        <div class="col-md-8 col-md-offset-4 text-right">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! trans('tickets.button.update_customer') !!} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li{!! ($ticket->ip_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.update.ip', trans('misc.ip').' '.trans('misc.contact'), [$ticket->id]) !!}</li>
                    <li{!! ($ticket->domain_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.update.domain', trans('misc.domain').' '.trans('misc.contact'), [$ticket->id]) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li>{!! link_to_route('admin.tickets.update.both', trans('misc.both'), [$ticket->id]) !!}</li>
                </ul>
            </div>
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ trans('tickets.button.send_notification') }} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li{!! ($ticket->ip_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.ip', trans('misc.ip').' '.trans('misc.contact'), [$ticket->id]) !!}</li>
                    <li{!! ($ticket->domain_contact_reference == 'UNDEF') ? ' class="disabled"' : '' !!}>{!! link_to_route('admin.tickets.domain', trans('misc.domain').' '.trans('misc.contact'), [$ticket->id]) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li>{!! link_to_route('admin.tickets.both', trans('misc.both'), [$ticket->id]) !!}</li>
                </ul>
            </div>
            {!! link_to_route('admin.tickets.status.solved', trans('tickets.button.resolved'), [$ticket->id], ['class' => 'btn btn-success']) !!}
            {!! link_to_route('admin.tickets.status.close', trans('misc.button.close'), [$ticket->id], ['class' => 'btn btn-warning']) !!}
            {!! link_to_route('admin.tickets.status.ignore', trans('tickets.button.ignore'), [$ticket->id], ['class' => 'btn btn-danger']) !!}
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#info"><span class="glyphicon glyphicon-file"></span> {{ trans('tickets.information') }}</a></li>
        <li><a data-toggle="tab" href="#events"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('tickets.incidents') }}</a></li>
        <li><a data-toggle="tab" href="#communication"><span class="glyphicon glyphicon-envelope"></span> {{ trans('tickets.communication') }}</a></li>
    </ul>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <dl class="dl-horizontal">
                <dt>{{ trans('misc.ip_address') }}</dt>
                <dd>{{ $ticket->ip }}</dd>

                @if (gethostbyaddr($ticket->ip) !== false)
                    <dt>{{ trans('misc.revdns') }}</dt>
                    <dd>{{ gethostbyaddr($ticket->ip) }}</dd>
                @endif

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

                <dt>{{ trans('tickets.count') }}</dt>
                <dd>{{ $ticket->events->count() }}</dd>

                <dt>{{ trans('misc.status') }}</dt>
                <dd>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</dd>

                <dt>{{ trans('tickets.created') }}</dt>
                <dd>{{ $ticket->created_at }}</dd>

                <dt>{{ trans('tickets.modified') }}</dt>
                <dd>{{ $ticket->updated_at }}</dd>

                <dt>{{ trans('tickets.last_notification') }}</dt>
                @if (!$ticket->last_notify_count == 0)
                <dd>Never</dd>
                @else
                <dd>{{ $ticket->last_notified }} (event: {{ $ticket->last_notify_count }})</dd>
                @endif

                <dt>{{ trans('tickets.total_notifications') }}</dt>
                <dd>{{ $ticket->notified_count }}</dd>

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
                <dt>{{ trans('misc.database_id') }}</dt>
                <dd>{{ $ticket->ip_contact_account_id }}</dd>

                <dt>{{ trans('contacts.reference') }}</dt>
                <dd>{{ $ticket->ip_contact_reference }}</dd>

                <dt>{{ trans('misc.name') }}</dt>
                <dd>{{ $ticket->ip_contact_name }}</dd>

                <dt>{{ trans('misc.email') }}</dt>
                <dd>{{ $ticket->ip_contact_email }}</dd>

                <dt>{{ trans('contacts.api_host') }}</dt>
                <dd>{{ $ticket->ip_contact_api_host }}</dd>

                <dt>{{ trans('contacts.api_key') }}</dt>
                <dd>{{ $ticket->ip_contact_api_key }}</dd>
            </dl>
            @endif

            @if ($ticket->domain_contact_reference != 'UNDEF')
            <h4>{{ trans('misc.domain') }} {{ trans('misc.contact') }}:</h4>
            <dl class="dl-horizontal">
                <dt>{{ trans('misc.database_id') }}</dt>
                <dd>{{ $ticket->domain_contact_account_id }}</dd>

                <dt>{{ trans('contacts.reference') }}</dt>
                <dd>{{ $ticket->domain_contact_reference }}</dd>

                <dt>{{ trans('misc.name') }}</dt>
                <dd>{{ $ticket->domain_contact_name }}</dd>

                <dt>{{ trans('misc.email') }}</dt>
                <dd>{{ $ticket->domain_contact_email }}</dd>

                <dt>{{ trans('contacts.api_host') }}</dt>
                <dd>{{ $ticket->domain_contact_api_host }}</dd>

                <dt>{{ trans('contacts.api_key') }}</dt>
                <dd>{{ $ticket->domain_contact_api_key }}</dd>
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
                        <td><a href='{{ Request::url() }}/evidence/{{ $event->evidences[0]->filename }}'>{{ trans('ash.communication.download') }}</a> - <a href="#">{{ trans('ash.communication.view') }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! Form::open(['method' => 'post', 'files' => true]) !!}
            <div class="form-group">
                {!! Form::label('text', trans('tickets.add_evidence')) !!}
                {!! Form::file('image') !!}
            </div>
            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
            {!! Form::close() !!}
        @endif
        </div>
        <div id="communication" class="tab-pane fade">
            @if ( !$ticket->notes->count() )
                <div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('ash.communication.noMessages') }}</div>
            @else
                @foreach ($ticket->notes as $note)
                <div class="row">
                    <div class="col-xs-11 {{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? '' : 'col-xs-offset-1' }}">
                        <div class="panel panel-{{ (stripos($note->submitter, trans('ash.communication.abusedesk')) !== false) ? 'info' : 'primary' }}">
                            <div class="panel-heading clearfix">
                                <h3 class="panel-title pull-left">{{ trans('ash.communication.responseFrom') }}: {{ $note->submitter }}</h3>

                                <span class="pull-right"><span class="glyphicon glyphicon-time"></span> {{ $note->created_at }}</span>

                                @if ($note->hidden == true)
                                    <span style='color:red;margin-right:1em' class="pull-right"><span class="glyphicon glyphicon-eye-close"></span> <a href="" title="Click to mark as visible again">{{ trans('misc.button.hidden') }}</a></span>
                                @else
                                    <span style='color:green;margin-right:1em' class="pull-right"><span class="glyphicon glyphicon-eye-open"></span> <a href="" title="Click to hide this note">{{ trans('misc.button.visible') }}</a></span>
                                @endif

                                @if ($note->viewed == true)
                                    <span style='color:green;margin-right:1em' class="pull-right"><span class="glyphicon glyphicon-ok-circle"></span> <a href="" title="Click to mark as unread again">{{ trans('misc.button.read') }}</a></span>
                                @else
                                    <span style='color:darkorange;margin-right:1em' class="pull-right"><span class="glyphicon glyphicon-ban-circle"></span> <a href="" title="Click to mark as read">{{ trans('misc.button.unread') }}</a></span>
                                @endif

                                @if (config('main.notes.deletable') === true)
                                    <span style='color:red;margin-right:1em' class="pull-right"><span class="glyphicon glyphicon-remove-circle"></span> <a href="" title="Click to delete this now">{{ trans('misc.button.delete') }}</a></span>
                                @endif
                            </div>
                            <div class="panel-body">
                                {{ htmlentities($note->text) }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
            {!! Form::model(new AbuseIO\Models\Note, ['route' => 'admin.notes.store', 'class' => 'form-horizontal']) !!}
                {!! Form::hidden('ticket_id', $ticket->id) !!}
                <div class="row">
                    <div class="col-xs-11 col-xs-offset-1">
                        <div class="form-group">
                            {!! Form::label('text', trans('ash.communication.reply')) !!}
                            {!! Form::textarea('text', null, ['size' => '30x5', 'placeholder' => trans('ash.communication.placeholder_admin'), 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::checkbox('hidden') !!} {!! trans('misc.button.hidden') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit(trans('ash.communication.submit'), ['class'=>'btn btn-success']) !!}
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        {!! link_to(URL::previous(), trans('misc.button.back'), ['class' => 'btn btn-default top-buffer']) !!}
    </div>
@endsection
