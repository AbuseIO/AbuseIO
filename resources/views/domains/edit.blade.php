@extends('app')

@section('content')
    <h2>Edit Domain</h2>

    {!! Form::model($domain, ['method' => 'PATCH', 'route' => ['admin.domains.update', $domain->id]]) !!}
    @include('domains/partials/_form', ['submit_text' => 'Edit Domain'])
    {!! Form::close() !!}
@endsection