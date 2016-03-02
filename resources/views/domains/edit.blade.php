@extends('app')

@section('content')
<h1 class="page-header">{{ trans('domains.header.edit') }}</h1>

{!! Form::model($domain, ['method' => 'PATCH', 'route' => ['admin.domains.update', $domain->id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $domain->id) !!}
@include('domains/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
