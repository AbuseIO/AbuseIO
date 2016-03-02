@extends('app')

@section('content')
<h1 class="page-header">{{ trans('contacts.header.edit') }}</h1>
{!! Form::model($contact, ['method' => 'PATCH', 'route' => ['admin.contacts.update', $contact->id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $contact->id) !!}
@include('contacts/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
