@extends('app')

@section('content')
<h1 class="page-header">{{ trans('netblocks.header.edit') }}</h1>
<form method="POST" action="{{ url('admin/netblocks/' . $netblock->id) }}" class="form-horizontal">
@csrf
@method('PATCH')
<input type="hidden" name="id" value="{{ $netblock->id }}">
@include('netblocks/partials/_form', ['submit_text' => trans('misc.button.save'), 'netblock' => $netblock, 'contact_selection' => $contact_selection, 'selected' => $selected, 'auth_user' => $auth_user])
</form>
@endsection
