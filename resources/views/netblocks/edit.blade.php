@extends('app')

@section('content')
    <h2>Edit Netblock</h2>

    {!! Form::model($netblock, ['method' => 'PATCH', 'route' => ['admin.netblocks.update', $netblock->id]]) !!}
    @include('netblocks/partials/_form', ['submit_text' => 'Edit Netblock'])
    {!! Form::close() !!}
@endsection