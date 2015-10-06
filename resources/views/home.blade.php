@extends('app')

@section('content')
    <div class="container">
        <h1 class="page-header">Home</h1>
        <div class="jumbotron">
            <p>{{ trans('misc.abuseio_intro1') }}</p>
            <p>{{ trans('misc.abuseio_intro2') }}</p>
        </div>
    </div>
@endsection
