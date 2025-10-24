@extends('app')

@section('content')
<h1 class="page-header">{{ trans('accounts.header.new') }}</h1>
<form method="POST" action="{{ route('admin.accounts.store') }}" class="form-horizontal">
@csrf
@include('accounts/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
