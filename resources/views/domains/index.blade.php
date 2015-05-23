@extends('app')

@section('content')

    <div class="container">

        <div class="row">
            <div  class="col-md-9" ><h1 class="page-header">Domains</h1></div>
            <div  class="col-md-3 pagination">
                {!! link_to_route('admin.domains.create', 'Create Domain', '', array('class' => 'btn btn-info')) !!}
                {!! link_to_route('admin.export.domains', 'CSV Export', array('format' => 'csv'), array('class' => 'btn btn-info')) !!}
            </div>
        </div>

        @if ( !$domains->count() )
            You have no domains yet
        @else
            {!! $domains->render() !!}

            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>Contact</th>
                    <th>Domain name</th>
                </tr>
                </thead>
                <tbody>

                @foreach( $domains as $domain )
                    <tr>
                        {!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('admin.domains.destroy', $domain->id))) !!}
                        <td>{{ $domain->contact->name }} ({{ $domain->contact->reference }})</td>
                        <td>{{ $domain->name }}</td>
                        <td>
                            {!! link_to_route('admin.domains.show', 'Details', array($domain->id), array('class' => 'btn btn-info')) !!}
                            {!! link_to_route('admin.domains.edit', 'Edit', array($domain->id), array('class' => 'btn btn-info')) !!}
                            {!! Form::submit('Delete', array('class' => 'btn btn-danger')) !!}
                        </td>
                        {!! Form::close() !!}
                    </tr>
                @endforeach
            </table>
        @endif

    </div>

@endsection
