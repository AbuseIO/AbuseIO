@extends('app')

@section('content')
<h1 class="page-header">{{ trans('brands.header.edit') }}</h1>
<form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <input type="hidden" name="id" value="{{ $brand->id }}">
    @include('brands/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
