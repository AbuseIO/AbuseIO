@extends('app')

@section('content')
    <h1 class="page-header">Domains</h1>
    <div class="row">
        <div  class="col-md-3 col-md-offset-9 text-right">
            {!! link_to_route('admin.domains.create', 'Create Domain', [ ], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.export.domains', 'CSV Export', ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
        </div>
    </div>
    @if ( !$domains->count() )
    <div class="alert alert-info">You have no domains yet</div>
    @else
    {!! $domains->render() !!}
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th>Contact</th>
                <th>Domain name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $domains as $domain )
            <tr>
                <td>{{ $domain->contact->name }} ({{ $domain->contact->reference }})</td>
                <td>{{ $domain->name }}</td>
                <td>
                    {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.domains.destroy', $domain->id]]) !!}
                    {!! link_to_route('admin.domains.show', 'Details', [$domain->id], ['class' => 'btn btn-info']) !!}
                    {!! link_to_route('admin.domains.edit', 'Edit', [$domain->id], ['class' => 'btn btn-info']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@stop
