@extends('app')

@section('content')
<h1 class="page-header">{{ trans_choice('misc.profile', 2) }}</h1>
{!! Form::model($auth_user, ['method' => 'PATCH', 'route' => ['admin.profile.update', $auth_user->id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $auth_user->id) !!}
@include('profile/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
