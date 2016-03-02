@extends('app')

@section('content')
<h1 class="page-header">{{ trans('accounts.header.edit') }}</h1>
{!! Form::model($account, ['method' => 'PATCH', 'route' => ['admin.accounts.update', $account->id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $account->id) !!}
@include('accounts/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
