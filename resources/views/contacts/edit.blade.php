@extends('app')

@section('content')
<h1 class="page-header">{{ trans('contacts.header.edit') }}</h1>
<form method="POST" action="{{ url('admin/contacts/' . $contact->id) }}" class="form-horizontal">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <input type="hidden" name="id" value="{{ $contact->id }}" />
    @include('contacts/partials/_form', ['submit_text' => trans('misc.button.save'), 'contact' => $contact, 'auth_user' => $auth_user, 'accounts' => $accounts, 'selectedAccount' => $selectedAccount, 'notificationService' => $notificationService])
</form>
@endsection
