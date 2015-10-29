@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('users.headers.edit') }}</h1>
    {!! Form::model($luser, ['method' => 'PATCH', 'route' => ['admin.users.update', $luser->id], 'class' => 'form-horizontal']) !!}
    {!! Form::hidden('id', $luser->id) !!}
    @include('users/partials/_form', ['submit_text' => trans('misc.button.save')])
    {!! Form::close() !!}
@endsection
