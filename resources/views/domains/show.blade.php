@extends('app')

@section('content')
<h1 class="page-header">{{ trans('domains.header.detail') }}: {{ isset($domain) ? $domain->name : '' }}</h1>
<div class="row">
    <div  class="col-md-3 offset-md-9 text-end">
        <form class="form-inline" method="POST" action="{{ url('admin/domains/' . $domain->id) }}">
            @csrf
            @method('DELETE')
            <a href="{{ url('admin/domains/' . $domain->id . '/edit') }}" class="btn btn-info">{{ trans('misc.button.edit') }}</a>
            <button type="submit" class="btn btn-danger">{{ trans('misc.button.delete') }}</button>
        </form>
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.database_id') }}</dt>
    <dd>{{ $domain->id }}</dd>

    <dt>{{ trans('domains.domainname') }}</dt>
    <dd>{{ $domain->name }}</dd>

    <dt>{{ trans('misc.contact') }}</dt>
    <dd>{{ isset($domain->contact) ? ($domain->contact->name . ' (' . $domain->contact->reference . ')') : trans('misc.notavailable') }}</dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $domain->enabled ? trans('misc.enabled') : trans('misc.disabled') }}</dd>
</dl>
@endsection
