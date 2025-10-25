@extends('app')

@section('content')
<h1 class="page-header">{{ trans('domains.header.new') }}</h1>
<form method="POST" action="{{ route('admin.domains.store') }}" class="form-horizontal">
    {{ csrf_field() }}
    @include('domains/partials/_form', ['submit_text' => trans('misc.button.save'), 'selected' => old('contact_id')])
</form>
@endsection
