@extends('app')

@section('content')
    <h1 class="page-header">Edit Netblock</h1>
    {!! Form::model($netblock, ['method' => 'PATCH', 'route' => ['admin.netblocks.update', $netblock->id], 'class' => 'form-horizontal']) !!}
    @include('netblocks/partials/_form', ['submit_text' => 'Edit Netblock'])
    {!! Form::close() !!}
@endsection
