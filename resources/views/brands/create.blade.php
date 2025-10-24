@extends('app')

@section('content')
<h1 class="page-header">{{ trans('brands.header.new') }}</h1>
<form action="{{ route('admin.brands.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
    @csrf
    @include('brands/partials/_form', ['submit_text' => trans('misc.button.save')])
</form>
@endsection
