@extends('app')

@section('extrajs')
<script src="{{ asset('/js/netblocks.js') }}"></script>
@endsection

@section('content')
<h1 class="page-header">{{ trans('netblocks.header.new') }}</h1>
<form method="POST" action="{{ route('admin.netblocks.store') }}" class="form-horizontal">
@csrf
@include('netblocks/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
