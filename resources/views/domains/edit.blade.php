@extends('app')

@section('content')
<h1 class="page-header">{{ trans('domains.header.edit') }}</h1>
<form method="POST" action="{{ url('admin/domains/' . $domain->id) }}" class="form-horizontal">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <input type="hidden" name="id" value="{{ $domain->id }}" />
    @include('domains/partials/_form', ['submit_text' => trans('misc.button.save'), 'domain' => $domain, 'contact_selection' => $contact_selection, 'selected' => $selected, 'auth_user' => $auth_user])
</form>
@endsection
