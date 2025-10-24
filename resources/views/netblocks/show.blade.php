@extends('app')

@section('content')
<h1 class="page-header">{{ trans('netblocks.header.detail') }}:</h1>
<div class="row">
    <div  class="col-md-3 col-md-offset-9 text-right">
        <form class="form-inline" method="POST" action="{{ route('admin.netblocks.destroy', $netblock->id) }}">
            @csrf
            @method('DELETE')
            <a href="{{ route('admin.netblocks.edit', $netblock->id) }}" class="btn btn-info">{{ trans('misc.button.edit') }}</a>
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
    <dd>{{ $netblock->contact->name }} ({{ $netblock->contact->reference }})</dd>

    <dt>{{ trans('misc.description') }}</dt>
    <dd>{{ $netblock->description }}</dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $netblock->enabled ? trans('misc.enabled') : trans('misc.disabled') }}</dd>
</dl>
@endsection
