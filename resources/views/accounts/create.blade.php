@extends('app')

@section('content')
<h1 class="page-header">{{ trans('accounts.header.new') }}</h1>
{!! Form::model(new AbuseIO\Models\Account, ['route' => 'admin.accounts.store', 'class' => 'form-horizontal']) !!}
@include('accounts/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
