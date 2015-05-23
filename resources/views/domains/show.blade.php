@extends('app')

@section('content')

    <div class="container">
        <h1 class="page-header">Domain details for: {{ $domain->name }}</h1>

        <dl class="dl-horizontal">
            <dt>Database ID</dt>
            <dd>{{ $domain->id }}</dd>

            <dt>Domain name</dt>
            <dd>{{ $domain->name }}</dd>

            <dt>Contact</dt>
            <dd>{{ $domain->contact->name }} ({{ $domain->contact->reference }})</dd>

            <dt>Status</dt>
            <dd>{{ $domain->enabled ? 'Enabled' : 'Disabled' }}</dd>
        </dl>
    </div>

@endsection
