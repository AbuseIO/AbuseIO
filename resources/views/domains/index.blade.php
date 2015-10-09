@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.domains') }}</h1>
    <div class="row">
        <div  class="col-md-3 col-md-offset-9 text-right">
            {!! link_to_route('admin.domains.create', trans('domains.button.new_domain'), [ ], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.export.domains', trans('misc.button.csv_export'), ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
        </div>
    </div>
    @if ( !$domains->count() )
        <div class="alert alert-info top-buffer"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('domains.no_domains') }}</div>
    @else
        {!! $domains->render() !!}
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>{{ trans('misc.contact') }}</th>
                    <th>{{ trans('domains.domainname') }}</th>
                    <th class="text-right">{{ trans('misc.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $domains as $domain )
                <tr>
                    <td>{{ $domain->contact->name }} ({{ $domain->contact->reference }})</td>
                    <td>{{ $domain->name }}</td>
                    <td class="text-right">
                        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.domains.destroy', $domain->id]]) !!}
                        {!! link_to_route('admin.domains.show', trans('misc.button.details'), [$domain->id], ['class' => 'btn btn-info btn-xs']) !!}
                        {!! link_to_route('admin.domains.edit', trans('misc.button.edit'), [$domain->id], ['class' => 'btn btn-info btn-xs']) !!}
                        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
