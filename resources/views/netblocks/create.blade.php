@extends('app')

@section('extrajs')
<script src="{{ asset('/js/netblocks.js') }}"></script>
@endsection

@section('content')
<h1 class="page-header">{{ trans('netblocks.header.new') }}</h1>
{!! Form::open(['route' => 'admin.netblocks.store', 'class' => 'form-horizontal']) !!}
@include('netblocks/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
