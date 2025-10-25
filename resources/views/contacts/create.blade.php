@extends('app')

@section('content')
<h1 class="page-header">{{ trans('contacts.header.new') }}</h1>
<form method="POST" action="{{ route('admin.contacts.store') }}" class="form-horizontal">
    {{ csrf_field() }}
    @include('contacts/partials/_form', ['submit_text' => trans('misc.button.save'), 'contact' => null])
</form>
@endsection
