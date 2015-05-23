@extends('app')

@section('content')
    <h2>Create Netblock</h2>

    {!! Form::model(new AbuseIO\Models\Netblock, ['route' => ['admin.netblocks.store']]) !!}
    @include('netblocks/partials/_form', ['submit_text' => 'Create Netblock'])
    {!! Form::close() !!}
@endsection