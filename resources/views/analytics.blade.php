@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.analytics') }}</h1>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>{{ trans('misc.classification') }}</th>
            <th>{{ trans('misc.tickets') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $classCounts as $classCount )
            <tr>
                <td>{{ $classCount->name }}</td>
                <td>{{ $classCount->tickets }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
