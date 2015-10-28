@extends('app')

@section('content')
    <h1 class="page-header">{{ trans_choice('misc.brands', 2) }}</h1>
<div class="row">
    <div class="col-md-3 col-md-offset-9 text-right">
        {!! link_to_route('admin.brands.create', trans('brands.button.new_brand'), [ ], ['class' => 'btn btn-info']) !!}
    </div>
</div>

@if ( !$brands->count() )
<div class="alert alert-info top-buffer"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('brands.no_brands')}}</div>
@else
{!! $brands->render() !!}
<table class="table table-striped table-condensed">
    <thead>
    <tr>
        <th>{{ trans('misc.name') }}</th>
        <th>{{ trans('misc.company_name') }}</th>
        <th>{{ trans('misc.text') }}</th>
        <th>{{ trans('brands.logo') }}</th>
        <th class="text-right">{{ trans('misc.action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $brands as $brand )
    <tr>
        <td>{{ $brand->name }}</td>
        <td>{{ $brand->company_name }}</td>
        <td>{{ $brand->introduction_text }}</td>
        <td><img src="/admin/logo/{{ $brand->id }}" height="24"/></td>
        <td class="text-right">
            {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.brands.destroy', $brand->id]]) !!}
            {!! link_to_route('admin.brands.show', trans('misc.button.details'), $brand->id, ['class' => 'btn btn-info btn-xs']) !!}
            {!! link_to_route('admin.brands.edit', trans('misc.button.edit'), $brand->id, ['class' => 'btn btn-info btn-xs']) !!}
            {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger btn-xs'.(($brand->id == 1) ? ' disabled' : '')]) !!}
            {!! Form::close() !!}
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endif
@endsection
