@extends('app')

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
{!! Form::model($user, ['method' => 'PATCH', 'route' => ['admin.users.update', $user->id]]) !!}
{!! Form::hidden('id', $user->id) !!}
@include('users/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
    </div>
</div>
@endsection
