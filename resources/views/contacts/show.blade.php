@extends('app')

@section('content')
<h1 class="page-header">{{ $contact->name }}</h1>
<div class="row">
    <div class="col-md-3 col-md-offset-9 text-right">
        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.contacts.destroy', $contact->id]]) !!}
        {!! link_to_route('admin.contacts.edit', trans('misc.button.edit'), $contact->id, ['class' => 'btn btn-info']) !!}
        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.database_id') }}</dt>
    <dd>{{ $contact->id }}</dd>

    <dt>{{ trans_choice('misc.accounts', 1) }}</dt>
    <dd>{{ $contact->account->name }}</dd>

    <dt>{{ trans('contacts.reference') }}</dt>
    <dd>{{ $contact->reference }}</dd>

    <dt>{{ trans('misc.name') }}</dt>
    <dd>{{ $contact->name }}</dd>

    <dt>{{ trans('misc.email') }}</dt>
    <dd>{{ $contact->email }}</dd>

    <dt>{{ trans('contacts.api_host') }}</dt>
    <dd>{{ $contact->api_host }}</dd>

    <dt>{{ trans('contacts.notification') }}</dt>
    <dd>{{ $contact->auto_notify ? trans('misc.automatic') : trans('misc.manual') }}</dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $contact->enabled ? trans('misc.enabled') : trans('misc.disabled') }}</dd>
</dl>

@if ( $contact->netblocks->count() )
<div class="panel panel-info">
    <div class="panel-heading"><h3 class="panel-title">{{ trans('contacts.linked_netblocks') }}</h3></div>
    <table class="table table-striped info">
        <thead>
            <tr>
                <th class="col-sm-1">{{ trans('misc.database_id') }}</th>
                <th class="col-sm-4">{{ trans('netblocks.first_ip') }}</th>
                <th>{{ trans('netblocks.last_ip') }}</th>
            </tr>
        </thead>
        @foreach( $contact->netblocks as $netblock )
        <tr>
            <td>{{ $netblock->id }}</td>
            <td>{{ $netblock->first_ip }}</td>
            <td>{{ $netblock->last_ip }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

@if ( $contact->domains->count() )
<div class="panel panel-info">
    <div class="panel-heading"><h3 class="panel-title">{{ trans('contacts.linked_domains') }}</h3></div>
    <table class="table table-striped info">
        <thead>
            <tr>
                <th class="col-sm-1">{{ trans('misc.database_id') }}</th>
                <th>{{trans('domains.domainname') }}</th>
            </tr>
        </thead>
        @foreach( $contact->domains as $domain )
        <tr>
            <td>{{ $domain->id }}</td>
            <td>{{ $domain->name }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif
@endsection
