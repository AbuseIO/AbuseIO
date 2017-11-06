@extends('app')

@section('content')
    {!! Form::model($auth_user, ['method' => 'PATCH', 'route' => ['admin.profile.update', $auth_user->id], 'class' => 'form-horizontal']) !!}
    {!! Form::hidden('id', $auth_user->id) !!}
    @include('profile/partials/_form', ['submit_text' => trans('misc.button.save')])
    {!! Form::close() !!}
@endsection
