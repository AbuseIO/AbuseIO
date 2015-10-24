@extends('app')

@section('content')
    <h1 class="page-header">{{ trans_choice('misc.profile', 2) }}</h1>
    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['admin.profile.update', $user->id], 'class' => 'form-horizontal']) !!}
    @include('profile/partials/_form', ['submit_text' => trans('misc.button.save')])
    {!! Form::close() !!}
@endsection
