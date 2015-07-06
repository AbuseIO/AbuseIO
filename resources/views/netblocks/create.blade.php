@extends('app')

@section('content')
    <h1 class="page-header">Create Netblock</h1>
    {!! Form::model(new AbuseIO\Models\Netblock, ['route' => 'admin.netblocks.store', 'class' => 'form-horizontal']) !!}
    @include('netblocks/partials/_form', ['submit_text' => 'Create Netblock'])
    {!! Form::close() !!}
@endsection
