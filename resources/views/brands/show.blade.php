@extends('app')

@section('content')
<h1 class="page-header">{{ trans('brands.headers.detail') }}: {{ $brand->name }}</h1>
<div class="row">
    <div class="col-md-3 col-md-offset-9 text-right">
        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.brands.destroy', $brand->id]]) !!}
        {!! link_to_route('admin.brands.edit', trans('misc.button.edit'), $brand->id, ['class' => 'btn btn-info']) !!}
        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.database_id') }}</dt>
    <dd>{{ $brand->id }}</dd>

    <dt>{{ trans('misc.name') }}</dt>
    <dd>{{ $brand->name }}</dd>

    <dt>{{ trans('misc.company_name') }}</dt>
    <dd>{{ $brand->company_name }}</dd>

    <dt>{{ trans('misc.text') }}</dt>
    <dd>{{ $brand->introduction_text }}</dd>

    <dt>{{ trans('brands.logo') }}</dt>
    <dd><img src="/admin/logo/{{ $brand->id }}" alt="{{ $brand->company_name }}"/></dd>

</dl>

{!! link_to_route('admin.brands.index', trans('misc.button.back'), [], ['class' => 'btn btn-default top-buffer']) !!}
@endsection
