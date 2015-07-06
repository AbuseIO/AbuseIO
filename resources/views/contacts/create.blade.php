@extends('app')

@section('content')
    <h1 class="page-header">Create Contact</h1>
    {!! Form::model(new AbuseIO\Models\Contact, ['route' => 'admin.contacts.store', 'class' => 'form-horizontal']) !!}
    @include('contacts/partials/_form', ['submit_text' => 'Create Contact'])
    {!! Form::close() !!}
@stop
