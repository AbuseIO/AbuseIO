@extends('app')

@section('content')
<h1 class="page-header">{{ trans('users.header.edit') }}</h1>
<form method="POST" action="{{ route('admin.users.update', ['users' => $user->id]) }}" class="form-horizontal">
@csrf
@method('PATCH')
<input type="hidden" name="id" value="{{ $user->id }}">
@include('users/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
