@extends('app')

@section('content')
    <h1 class="page-header">Create Domain</h1>
    {!! Form::model(new AbuseIO\Models\Domain, ['route' => 'admin.domains.store', 'class' => 'form-horizontal']) !!}
    @include('domains/partials/_form', ['submit_text' => 'Create Domain'])
    {!! Form::close() !!}
@endsection
