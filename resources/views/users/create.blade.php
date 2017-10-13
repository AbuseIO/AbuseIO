@extends('app')

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
{!! Form::model(new AbuseIO\Models\User, ['route' => 'admin.users.store']) !!}
@include('users/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
    </div>
</div>
@endsection
