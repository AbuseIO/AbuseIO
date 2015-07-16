@extends('app')

@section('content')
    <h1 class="page-header">Netblock details for: {{ $netblock->name }}</h1>
    <div class="row">
        <div  class="col-md-3 col-md-offset-9 text-right">
            {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.netblocks.destroy', $netblock->id]]) !!}
            {!! link_to_route('admin.netblocks.edit', 'Edit', [$netblock->id], ['class' => 'btn btn-info']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        </div>
    </div>
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
    {!! link_to(URL::previous(), 'Back', ['class' => 'btn btn-default']) !!}
@endsection
