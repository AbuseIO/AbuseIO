@extends('app')

@section('content')
<h1 class="page-header">{{ trans('accounts.header.edit') }}</h1>
<form method="POST" action="{{ route('admin.accounts.update', ['accounts' => $account->id]) }}" class="form-horizontal">
@csrf
@method('PATCH')
<input type="hidden" name="id" value="{{ $account->id }}">
@include('accounts/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
