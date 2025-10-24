@extends('app')

@section('content')
<h1 class="page-header">{{ trans('users.header.new') }}</h1>
<form method="POST" action="{{ route('admin.users.store') }}" class="form-horizontal">
@csrf
@include('users/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
