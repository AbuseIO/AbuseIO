@extends('app')

@section('content')
    <h1 class="page-header">Analytics</h1>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>Classification</th>
            <th>Tickets</th>
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
