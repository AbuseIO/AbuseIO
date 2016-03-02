@extends('app')

@section('content')
<h1 class="page-header">{{ trans('contacts.header.new') }}</h1>
{!! Form::model(new AbuseIO\Models\Contact, ['route' => 'admin.contacts.store', 'class' => 'form-horizontal']) !!}
@include('contacts/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
