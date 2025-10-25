@extends('app')

@section('content')
<h1 class="page-header">{{ trans_choice('misc.profile', 2) }}</h1>

<form method="POST" action="{{ route('admin.profile.update', $auth_user->id) }}" class="form-horizontal">
    @csrf
    <input type="hidden" name="_method" value="PATCH">
    <input type="hidden" name="id" value="{{ $auth_user->id }}">

    @include('profile/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
