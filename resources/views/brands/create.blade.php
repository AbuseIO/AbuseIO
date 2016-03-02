@extends('app')

@section('content')
<h1 class="page-header">{{ trans('brands.header.new') }}</h1>
{!! Form::model(new AbuseIO\Models\Brand, ['route' => 'admin.brands.store', 'class' => 'form-horizontal', 'files' => true]) !!}
@include('brands/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
