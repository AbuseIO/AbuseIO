@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('tickets.headers.detail') }}: {{ $ticket->id }}</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-8 text-right">
            {!! link_to(URL::previous(), trans('misc.button.back'), ['class' => 'btn btn-default']) !!}
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
                <dd>{{ date('d-m-Y H:i', $ticket->firstEvent[0]->timestamp) }}</dd>

                <dt>{{ trans('tickets.last_seen') }}</dt>
                <dd>{{ date('d-m-Y H:i', $ticket->lastEvent[0]->timestamp) }}</dd>

                <dt>{{ trans('tickets.count') }}</dt>
                <dd>{{ $ticket->events->count() }}</dd>

                <dt>{{ trans('misc.status') }}</dt>
                <dd>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</dd>

                <dt>{{ trans('tickets.created') }}</dt>
                <dd>{{ $ticket->created_at }}</dd>

                <dt>{{ trans('tickets.modified') }}</dt>
                <dd>{{ $ticket->updated_at }}</dd>

                <dt>{{ trans('tickets.reply_status') }}</dt>
                <dd></dd>

                <dt>{{ trans('tickets.ashlink') }}</dt>
                <dd>
                    {!! link_to(
                        "/ash/collect/$ticket->id/" . md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference),
                        'http://' . Request::server('SERVER_NAME') . "/ash/collect/$ticket->id/"
                         . md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference)
                    ) !!}
                </dd>
            </dl>

            <h4>{{ trans('misc.ip') }} {{ trans('misc.contact') }}:</h4>
            <dl class="dl-horizontal">
                <dt>{{ trans('contacts.account') }}</dt>
                <dd>{{ $ticket->ip_contact_account_id }}</dd>

                <dt>{{ trans('contacts.reference') }}</dt>
                <dd>{{ $ticket->ip_contact_reference }}</dd>

                <dt>{{ trans('misc.name') }}</dt>
                <dd>{{ $ticket->ip_contact_name }}</dd>

                <dt>{{ trans('misc.email') }}</dt>
                <dd>{{ $ticket->ip_contact_email }}</dd>

                <dt>{{ trans('contacts.rpchost') }}</dt>
                <dd>{{ $ticket->ip_contact_rpc_host }}</dd>

                <dt>{{ trans('contacts.rpckey') }}</dt>
                <dd>{{ $ticket->ip_contact_rpc_key }}</dd>
            </dl>

            <h4>{{ trans('misc.domain') }} {{ trans('misc.contact') }}:</h4>
            <dl class="dl-horizontal">
                <dt>{{ trans('contacts.account') }}</dt>
                <dd>{{ $ticket->domain_contact_account_id }}</dd>

                <dt>{{ trans('contacts.reference') }}</dt>
                <dd>{{ $ticket->domain_contact_reference }}</dd>

                <dt>{{ trans('misc.name') }}</dt>
                <dd>{{ $ticket->domain_contact_name }}</dd>

                <dt>{{ trans('misc.email') }}</dt>
                <dd>{{ $ticket->domain_contact_email }}</dd>

                <dt>{{ trans('contacts.rpchost') }}</dt>
                <dd>{{ $ticket->domain_contact_rpc_host }}</dd>

                <dt>{{ trans('contacts.rpckey') }}</dt>
                <dd>{{ $ticket->domain_contact_rpc_key }}</dd>
            </dl>
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
                        <td>{{ date('d-m-Y H:i', $event->timestamp) }}</td>
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
        @endif
        </div>
        <div id="communication" class="tab-pane fade">
            @if ( !$ticket->notes->count() )
                <div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('ash.communication.noMessages') }}</div>
            @else
                @foreach ($ticket->notes as $note)
                <div class="row">
                    <div class="col-xs-11 {{ ($note->submitter != 'contact') ? 'col-xs-offset-1' : '' }}">
                        <div class="panel panel-{{ ($note->submitter == 'contact') ? 'default' : 'primary' }}">
                            <div class="panel-heading clearfix">
                                <h3 class="panel-title pull-left">{{ Lang::get('ash.communication.responseFrom') }}: {{ $note->submitter }}</h3>
                                <span class="pull-right"><span class="glyphicon glyphicon-time"></span> {{ $note->created_at }}</span>
                            </div>
                            <div class="panel-body">
                                {{ htmlentities($note->text) }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
            {!! Form::open(['method' => 'put']) !!}
                <div class="row">
                    <div class="col-xs-11 col-xs-offset-1">
                        <div class="form-group">
                            {!! Form::label('text', Lang::get('ash.communication.reply')) !!}
                            {!! Form::textarea('text', null, ['size' => '30x5', 'placeholder' => Lang::get('ash.communication.placeholder_admin'), 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit(Lang::get('ash.communication.submit'), ['class'=>'btn btn-success']) !!}
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}

        </div>
    </div>
@endsection
