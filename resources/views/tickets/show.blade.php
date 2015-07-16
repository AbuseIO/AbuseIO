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
                        "/ash/collect/$ticket->id/" . md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference)
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
                            @foreach (json_decode($event->information) as $field => $value)
                                <dt>{{ ucfirst($field) }}</dt>
                                <dd>{{ htmlentities($value) }}</dd>
                            @endforeach
                            </dl>
                        </td>
                        <td><a href='{{ $event->evidences[0]->filename }}'>Download - View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        </div>
        <div id="communication" class="tab-pane fade">
            {!! Form::model(new AbuseIO\Models\Note) !!}
                <div class="form-group">
                    {!! Form::label('text', 'Reply:') !!}
                    {!! Form::textarea('text', null, ['size' => '30x5', 'placeholder' => 'Enter a reply to the customer', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Submit', ['class'=>'btn primary']) !!}
                </div>
            {!! Form::close() !!}

            <h4>Previous communication</h4>
            @if ( !$ticket->notes->count() )
                <div class="alert alert-info">No interaction has been done yet</div>
            @else
                @foreach ($ticket->notes as $note)
                    <div class="panel panel-{{ ($note->submitter == 'contact') ? 'default' : 'primary' }}">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Reponse from: {{ Lang::get('ash.communication.'.$note->submitter) }}</h3>
                            <span class="pull-right">{{ $note->created_at }}</span>
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
