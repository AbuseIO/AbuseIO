@extends('app')

@section('content')
<h1 class="page-header">{{ trans('netblocks.header.edit') }}</h1>
<form method="POST" action="{{ route('admin.netblocks.update', $netblock->id) }}" class="form-horizontal">
@csrf
@method('PATCH')
<input type="hidden" name="id" value="{{ $netblock->id }}">
@include('netblocks/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
