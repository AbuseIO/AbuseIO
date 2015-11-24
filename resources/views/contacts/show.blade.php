@extends('app')

@section('content')
<h1 class="page-header">{{ trans('contacts.headers.detail') }}: {{ $contact->name }}</h1>
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

    <dt>{{ trans('contacts.reference') }}</dt>
    <dd>{{ $contact->reference }}</dd>

    <dt>{{ trans('misc.name') }}</dt>
    <dd>{{ $contact->name }}</dd>

    <dt>{{ trans('misc.email') }}</dt>
    <dd>{{ $contact->email }}</dd>

    <dt>{{ trans('contacts.rpchost') }}</dt>
    <dd>{{ $contact->rpc_host }}</dd>

    <dt>{{ trans('contacts.rpckey') }}</dt>
    <dd>{{ $contact->rpc_key }}</dd>

    <dt>{{ trans('contacts.notification') }}</dt>
    <dd>{{ $contact->auto_notify ? trans('misc.automatic') : trans('misc.manual') }}</dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $contact->enabled ? trans('misc.enabled') : trans('misc.disabled') }}</dd>
</dl>

@if ( $contact->netblocks->count() )
<h3 class="page-header">{{ trans('contacts.linked_netblocks') }}</h3>
    @foreach( $contact->netblocks as $netblock )
    <div class="row">
        <div class="col-md-2">{{ trans('contacts.netblock_id') }}: {{ $netblock->id }}</div>
        <div class="col-md-2">{{ $netblock->first_ip }}</div>
        <div class="col-md-2">{{ $netblock->last_ip }}</div>
    </div>
    @endforeach
@endif

@if ( $contact->domains->count() )
<h3 class="page-header">{{ trans('contacts.linked_domains') }}</h3>
    @foreach( $contact->domains as $domain )
    <div class="row">
        <div class="col-md-2">{{ trans('contacts.domain_id') }}: {{ $domain->id }}</div>
        <div class="col-md-2">{{ $domain->name }}</div>
    </div>
    @endforeach
@endif
{!! link_to_route('admin.contacts.index', trans('misc.button.back'), [], ['class' => 'btn btn-default top-buffer']) !!}
@endsection
