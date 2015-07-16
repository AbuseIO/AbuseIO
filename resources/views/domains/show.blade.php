@extends('app')

@section('content')
<h1 class="page-header">Domain details for: {{ $domain->name }}</h1>
<div class="row">
    <div  class="col-md-3 col-md-offset-9 text-right">
        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.domains.destroy', $domain->id]]) !!}
        {!! link_to_route('admin.domains.edit', 'Edit', [$domain->id], ['class' => 'btn btn-info']) !!}
        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>
</div>
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
{!! link_to(URL::previous(), 'Back', ['class' => 'btn btn-default']) !!}
@endsection
