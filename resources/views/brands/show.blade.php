@extends('app')

@section('content')
<h1 class="page-header">{{ trans('brands.header.detail') }}: {{ $brand->name }}</h1>
<div class="row">
    <div class="col-md-3 offset-md-9 text-end">
        <form class="form-inline" method="POST" action="{{ route('admin.brands.destroy', $brand->id) }}">
            @csrf
            @method('DELETE')
            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-info">{{ trans('misc.button.edit') }}</a>
            <button type="submit" class="btn btn-danger">{{ trans('misc.button.delete') }}</button>
        </form>
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

    <dt>{{ trans('misc.creator') }}</dt>
    <dd>{{ $creator->name }}</dd>

    <dt>{{ trans('brands.logo') }}</dt>
    <dd><img src="/admin/logo/{{ $brand->id }}" alt="{{ $brand->company_name }}"/></dd>


    <dt></dt>
    <dd>&nbsp;</dd>
</dl>

@if ( $brand->mail_custom_template)
<div class="row">
    <div class="offset-sm-2 col-sm-10" style="padding-left: 0;">
        <div class="card">
            <div class="card-header clearfix">
                <h3 class="card-title float-start">{{ trans('brands.mail_template_plain') }}</h3>
            </div>
            <div class="card-body">
                <pre class="prettyprint">
                    {!! htmlspecialchars(html_entity_decode($brand->mail_template_plain)) !!}
                </pre>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="offset-sm-2 col-sm-10" style="padding-left: 0;">
        <div class="card">
            <div class="card-header clearfix">
                <h3 class="card-title float-start">{{ trans('brands.mail_template_html') }}</h3>
            </div>
            <div class="card-body">
                <pre class="prettyprint">
                    {!! htmlspecialchars(html_entity_decode($brand->mail_template_html)) !!}
                </pre>
            </div>
        </div>
    </div>
</div>
@endif

@if ( $brand->ash_custom_template)
<div class="row">
    <div class="offset-sm-2 col-sm-10" style="padding-left: 0;">
        <div class="card">
            <div class="card-header clearfix">
                <h3 class="card-title float-start">{{ trans('brands.ash_template') }}</h3>
            </div>
            <div class="card-body">
                <pre class="prettyprint">
                    {!! htmlspecialchars(html_entity_decode($brand->ash_template)) !!}
                </pre>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
