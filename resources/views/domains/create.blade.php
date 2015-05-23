@extends('app')

@section('content')
    <h2>Create Domain</h2>

    {!! Form::model(new AbuseIO\Models\Domain, ['route' => ['admin.domains.store']]) !!}
    @include('domains/partials/_form', ['submit_text' => 'Create Domain'])
    {!! Form::close() !!}
@endsection