@extends('app')

@section('content')
<h1 class="page-header">{{ trans('domains.header.detail') }}: {{ $domain->name }}</h1>
<div class="row">
    <div  class="col-md-3 col-md-offset-9 text-right">
        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.domains.destroy', $domain->id]]) !!}
        {!! link_to_route('admin.domains.edit', trans('misc.button.edit'), [$domain->id], ['class' => 'btn btn-info']) !!}
        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.database_id') }}</dt>
    <dd>{{ $domain->id }}</dd>

    <dt>{{ trans('domains.domainname') }}</dt>
    <dd>{{ $domain->name }}</dd>

    <dt>{{ trans('misc.contact') }}</dt>
    <dd>{{ $domain->contact->name }} ({{ $domain->contact->reference }})</dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $domain->enabled ? trans('misc.enabled') : trans('misc.disabled') }}</dd>
</dl>
@endsection
