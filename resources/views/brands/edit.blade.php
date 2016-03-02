@extends('app')

@section('content')
<h1 class="page-header">{{ trans('brands.header.edit') }}</h1>
{!! Form::model($brand, ['method' => 'PATCH', 'route' => ['admin.brands.update', $brand->id], 'class' => 'form-horizontal', 'files' => true]) !!}
{!! Form::hidden('id', $brand->id) !!}
@include('brands/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
