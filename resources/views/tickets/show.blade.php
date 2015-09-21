@extends('app')

@section('content')
    <h1 class="page-header">Ticket {{ $ticket->id }} details</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-8 text-right">
            {!! link_to(URL::previous(), 'Back', ['class' => 'btn btn-default']) !!}
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#info"><span class="glyphicon glyphicon-file"></span> Information</a></li>
        <li><a data-toggle="tab" href="#events"><span class="glyphicon glyphicon-list-alt"></span> Events</a></li>
        <li><a data-toggle="tab" href="#communication"><span class="glyphicon glyphicon-envelope"></span> Communication</a></li>
    </ul>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <dl class="dl-horizontal">
                <dt>IP address</dt>
                <dd>{{ $ticket->ip }}</dd>

                @if (gethostbyaddr($ticket->ip) !== false)
                    <dt>Reverse DNS</dt>
                    <dd>{{ gethostbyaddr($ticket->ip) }}</dd>
                @endif

                @if (!empty($ticket->domain))
                    <dt>Domain name</dt>
                    <dd>{{ $ticket->domain }}</dd>
                @endif

                <dt>Classification</dt>
                <dd>{{ Lang::get('classifications.' . $ticket->class_id . '.name') }}</dd>

                <dt>Type</dt>
                <dd>{{ Lang::get('types.type.' . $ticket->type_id . '.name') }}</dd>

                <dt>Action required</dt>
                <dd>{{ Lang::get('types.type.' . $ticket->type_id . '.description') }}</dd>

                <dt>First seen</dt>
                <dd>{{ date('d-m-Y H:i', $ticket->firstEvent[0]->timestamp) }}</dd>

                <dt>Last seen</dt>
                <dd>{{ date('d-m-Y H:i', $ticket->lastEvent[0]->timestamp) }}</dd>

                <dt>Event count</dt>
                <dd>{{ $ticket->events->count() }}</dd>

                <dt>Ticket status</dt>
                <dd>{{ Lang::get('types.status.' . $ticket->status_id . '.name') }}</dd>

                <dt>Ticket created</dt>
                <dd>{{ $ticket->created_at }}</dd>

                <dt>Ticket last modified</dt>
                <dd>{{ $ticket->updated_at }}</dd>

                <dt>Reply status</dt>
                <dd></dd>

                <dt>ASH Link</dt>
                <dd>
                    {!! link_to(
                        "/ash/collect/$ticket->id/" . md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference),
                        'http://' . Request::server('SERVER_NAME') . "/ash/collect/$ticket->id/"
                         . md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference)
                    ) !!}
                </dd>
            </dl>

            <h4>IP contact:</h4>
            <dl class="dl-horizontal">
                <dt>Reference</dt>
                <dd>{{ $ticket->ip_contact_reference }}</dd>

                <dt>Name</dt>
                <dd>{{ $ticket->ip_contact_name }}</dd>

                <dt>E-mail address</dt>
                <dd>{{ $ticket->ip_contact_email }}</dd>

                <dt>RPC Host</dt>
                <dd>{{ $ticket->ip_contact_rpc_host }}</dd>

                <dt>RPC Key</dt>
                <dd>{{ $ticket->ip_contact_rpc_key }}</dd>
            </dl>

            @if ( $ticket->domain_contact_reference )
            <h4>Domain contact:</h4>
            <dl class="dl-horizontal">
                <dt>Reference</dt>
                <dd>{{ $ticket->domain_contact_reference }}</dd>

                <dt>Name</dt>
                <dd>{{ $ticket->domain_contact_name }}</dd>

                <dt>E-mail address</dt>
                <dd>{{ $ticket->domain_contact_email }}</dd>

                <dt>RPC Host</dt>
                <dd>{{ $ticket->domain_contact_rpc_host }}</dd>

                <dt>RPC Key</dt>
                <dd>{{ $ticket->domain_contact_rpc_key }}</dd>
            </dl>
            @endif
        </div>
        <div id="events" class="tab-pane fade">
        @if ( !$ticket->events->count() )
            <div class="alert alert-danger">No events found</div>
        @else
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Source</th>
                        <th>Information</th>
                        <th>Evidence</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($ticket->events as $event)
                    <tr>
                        <td>{{ date('d-m-Y H:i', $event->timestamp) }}</td>
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
                        <td><a href='{{ Request::url() }}/evidence/{{ $event->evidences[0]->filename }}'>{{ Lang::get('ash.communication.download') }}</a> - <a href="#">{{ Lang::get('ash.communication.view') }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        </div>
        <div id="communication" class="tab-pane fade">
            @if ( !$ticket->notes->count() )
                <div class="alert alert-info">{{ Lang::get('ash.communication.noMessages') }}</div>
            @else
                @foreach ($ticket->notes as $note)
                <div class="row">
                    <div class="col-xs-11 {{ ($note->submitter != 'contact') ? 'col-xs-offset-1' : '' }}">
                        <div class="panel panel-{{ ($note->submitter == 'contact') ? 'default' : 'primary' }}">
                            <div class="panel-heading clearfix">
                                <h3 class="panel-title pull-left">{{ Lang::get('ash.communication.responseFrom') }}: {{ Lang::get('ash.communication.'.$note->submitter) }}</h3>
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
