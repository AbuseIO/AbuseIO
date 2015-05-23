@extends('app')

@section('content')
    <h2>Edit Contact</h2>

    {!! Form::model($contact, ['method' => 'PATCH', 'route' => ['admin.contacts.update', $contact->id]]) !!}
    @include('contacts/partials/_form', ['submit_text' => 'Edit Contact'])
    {!! Form::close() !!}
@endsection