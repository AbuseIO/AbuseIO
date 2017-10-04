@extends('app')

@section('content')
{{--<h1 class="page-header">{{ trans('users.header.new') }}</h1>--}}
<div class="panel panel-default">
    <div class="panel-body">
{!! Form::model(new AbuseIO\Models\User, ['route' => 'admin.users.store', 'class' => 'form-horizontal']) !!}
@include('users/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
    </div>
</div>
@endsection
