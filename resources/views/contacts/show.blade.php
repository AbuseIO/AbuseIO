@extends('app')

@section('content')

    <div class="container">
        <h1 class="page-header">Contact details for: {{ $contact->name }}</h1>

        <div class="row">
            <div class="col-md-3 col-md-offset-9 text-right">
                {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.contacts.destroy', $contact->id]]) !!}
                {!! link_to_route('admin.contacts.edit', 'Edit', $contact->id, ['class' => 'btn btn-info']) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <dl class="dl-horizontal">
            <dt>Database ID</dt>
            <dd>{{ $contact->id }}</dd>

            <dt>Reference</dt>
            <dd>{{ $contact->reference }}</dd>

            <dt>Name</dt>
            <dd>{{ $contact->name }}</dd>

            <dt>E-mail addresses</dt>
            <dd>{{ $contact->email }}</dd>

            <dt>RPC Hosts</dt>
            <dd>{{ $contact->rpc_host }}</dd>

            <dt>RPC Key</dt>
            <dd>{{ $contact->rpc_key }}</dd>

            <dt>Notifications</dt>
            <dd>{{ $contact->auto_notify ? 'Automatic' : 'Manual' }}</dd>

            <dt>Status</dt>
            <dd>{{ $contact->enabled ? 'Enabled' : 'Disabled' }}</dd>
        </dl>

        @if ( $contact->netblocks->count() )
            <h2 class="page-header">Linked netblocks</h2>
            @foreach( $contact->netblocks as $netblock )
                <div class="row">
                    <div class="col-md-2">Block ID: {{ $netblock->id }}</div>
                    <div class="col-md-2">{{ ICF::inet_itop($netblock->first_ip) }}</div>
                    <div class="col-md-2">{{ ICF::inet_itop($netblock->last_ip) }}</div>
                </div>
            @endforeach
        @endif

        @if ( $contact->domains->count() )
            <h2 class="page-header">Linked domains</h2>
            @foreach( $contact->domains as $domain )
                <div class="row">
                    <div class="col-md-2">Domain ID: {{ $domain->id }}</div>
                    <div class="col-md-2">{{ $domain->name }}</div>
                </div>
            @endforeach
        @endif

    </div>

@endsection
