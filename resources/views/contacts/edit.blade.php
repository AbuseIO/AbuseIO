@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('contacts.headers.edit') }}</h1>
    {!! Form::model($contact, ['method' => 'PATCH', 'route' => ['admin.contacts.update', $contact->id], 'class' => 'form-horizontal']) !!}
    @include('contacts/partials/_form', ['submit_text' => trans('misc.button.save')])
    {!! Form::close() !!}
@endsection
