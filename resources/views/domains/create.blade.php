@extends('app')

@section('content')
<h1 class="page-header">{{ trans('domains.header.new') }}</h1>
{!! Form::model(new AbuseIO\Models\Domain, ['route' => 'admin.domains.store', 'class' => 'form-horizontal']) !!}
@include('domains/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
