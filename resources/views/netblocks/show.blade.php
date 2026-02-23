@extends('app')

@section('content')
<h1 class="page-header">{{ trans('netblocks.header.detail') }}:</h1>
<div class="row">
    <div  class="col-md-3 offset-md-9 text-end">
        <form class="form-inline" method="POST" action="{{ url('admin/netblocks/' . $netblock->id) }}">
            @csrf
            @method('DELETE')
            <a href="{{ url('admin/netblocks/' . $netblock->id . '/edit') }}" class="btn btn-info">{{ trans('misc.button.edit') }}</a>
            <button type="submit" class="btn btn-danger">{{ trans('misc.button.delete') }}</button>
        </form>
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.database_id') }}</dt>
    <dd>{{ $netblock->id }}</dd>

    <dt>{{ trans('netblocks.first_ip') }}</dt>
    <dd>{{ $netblock->first_ip }}</dd>

    <dt>{{ trans('netblocks.last_ip') }}</dt>
    <dd>{{ $netblock->last_ip }}</dd>

    <dt>{{ trans('misc.contact') }}</dt>
    <dd>{{ isset($netblock->contact) ? ($netblock->contact->name . ' (' . $netblock->contact->reference . ')') : trans('misc.notavailable') }}</dd>

    <dt>{{ trans('misc.description') }}</dt>
    <dd>{{ $netblock->description }}</dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $netblock->enabled ? trans('misc.enabled') : trans('misc.disabled') }}</dd>
</dl>
@endsection
