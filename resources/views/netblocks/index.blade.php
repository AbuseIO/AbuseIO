@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.netblocks') }}</h1>
    <div class="row">
        <div  class="col-md-3 col-md-offset-9 text-right">
            {!! link_to_route('admin.netblocks.create', trans('netblocks.button.new_netblock'), [], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.export.netblocks', trans('misc.button.csv_export'), ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
        </div>
    </div>
    @if ( !$netblocks->count() )
        <div class="alert alert-info top-buffer"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('netblocks.no_netblocks')}}</div>
    @else
        {!! $netblocks->render() !!}
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>{{ trans('misc.contact') }}</th>
                    <th>{{ trans('netblocks.first_ip') }}</th>
                    <th>{{ trans('netblocks.last_ip') }}</th>
                    <th class="text-right">{{ trans('misc.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $netblocks as $netblock )
                <tr>
                    <td>{{ $netblock->contact->name }} ({{ $netblock->contact->reference }})</td>
                    <td>{{ ICF::inetItop($netblock->first_ip) }}</td>
                    <td>{{ ICF::inetItop($netblock->last_ip) }}</td>
                    <td class="text-right">
                        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.netblocks.destroy', $netblock->id]]) !!}
                        {!! link_to_route('admin.netblocks.show', trans('misc.button.details'), [$netblock->id], ['class' => 'btn btn-info btn-xs']) !!}
                        {!! link_to_route('admin.netblocks.edit', trans('misc.button.edit'), [$netblock->id], ['class' => 'btn btn-info btn-xs']) !!}
                        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
