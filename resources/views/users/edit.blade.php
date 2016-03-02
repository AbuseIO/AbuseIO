@extends('app')

@section('content')
<h1 class="page-header">{{ trans('users.header.edit') }}</h1>
{!! Form::model($user, ['method' => 'PATCH', 'route' => ['admin.users.update', $user->id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $user->id) !!}
@include('users/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
