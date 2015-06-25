@extends('app')

@section('content')

    <div class="container">
        <h1 class="page-header">Netblock details for: {{ $netblock->name }}</h1>

        <dl class="dl-horizontal">
            <dt>Database ID</dt>
            <dd>{{ $netblock->id }}</dd>

            <dt>First IP address</dt>
            <dd>{{ ICF::inet_itop($netblock->first_ip) }}</dd>

            <dt>Last IP address</dt>
            <dd>{{ ICF::inet_itop($netblock->last_ip) }}</dd>

            <dt>Contact</dt>
            <dd>{{ $netblock->contact->name }} ({{ $netblock->contact->reference }})</dd>

            <dt>Description</dt>
            <dd>{{ $netblock->description }}</dd>

            <dt>Status</dt>
            <dd>{{ $netblock->enabled ? 'Enabled' : 'Disabled' }}</dd>
        </dl>
    </div>

@endsection
