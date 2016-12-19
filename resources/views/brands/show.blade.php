@extends('app')

@section('content')
<h1 class="page-header">{{ trans('brands.header.detail') }}: {{ $brand->name }}</h1>
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

    <dt>{{ trans('misc.creator') }}</dt>
    <dd>{{ $creator->name }}</dd>

    <dt>{{ trans('brands.logo') }}</dt>
    <dd><img src="/admin/logo/{{ $brand->id }}" alt="{{ $brand->company_name }}"/></dd>


    <dt></dt>
    <dd>&nbsp;</dd>
</dl>

@if ( $brand->mail_custom_template)
<div class="row">
    <div class="col-sm-offset-2 col-sm-10" style="padding-left: 0;">
        <div class="panel panel-default panel_info">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">{{ trans('brands.mail_template_plain') }}</h3>
            </div>
            <div class="panel-body">
                <pre class="prettyprint">
                    {{ htmlentities($brand->mail_template_plain) }}
                </pre>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-offset-2 col-sm-10" style="padding-left: 0;">
        <div class="panel panel-default panel_info">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">{{ trans('brands.mail_template_html') }}</h3>
            </div>
            <div class="panel-body">
                <pre class="prettyprint">
                    {{ htmlentities($brand->mail_template_html) }}
                </pre>
            </div>
        </div>
    </div>
</div>
@endif

@if ( $brand->ash_custom_template)
<div class="row">
    <div class="col-sm-offset-2 col-sm-10" style="padding-left: 0;">
        <div class="panel panel-default panel_info">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">{{ trans('brands.ash_template') }}</h3>
            </div>
            <div class="panel-body">
                <pre class="prettyprint">
                    {{ htmlentities($brand->ash_template) }}
                </pre>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
