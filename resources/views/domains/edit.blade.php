@extends('app')

@section('content')
    <h1 class="page-header">Edit Domain</h1>

    {!! Form::model($domain, ['method' => 'PATCH', 'route' => ['admin.domains.update', $domain->id], 'class' => 'form-horizontal']) !!}
    @include('domains/partials/_form', ['submit_text' => 'Edit Domain'])
    {!! Form::close() !!}
@endsection
