@extends('app')

@section('content')
    <h2>Create Contact</h2>

    {!! Form::model(new AbuseIO\Models\Contact, ['route' => ['admin.contacts.store']]) !!}
    @include('contacts/partials/_form', ['submit_text' => 'Create Contact'])
    {!! Form::close() !!}
@endsection