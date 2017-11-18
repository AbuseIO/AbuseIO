@extends('app')

@section('extrajs')
<script>
    var searchroute = '{!! route('admin.tickets.search') .'/query/' !!}';
    var locale = '{{ asset("/i18n/$auth_user->locale.json") }}';
    var user_options = jQuery.parseJSON('{!! $user_options !!}');
</script>
<script src="{{ asset('/js/tickets.index.js') }}"></script>
@stop

@section('content')
<h1 class="page-header">{{ trans('misc.tickets') }}</h1>
<div class="row">
    <div class="col-md-4 col-md-offset-8 text-right">
        {!! link_to_route('admin.incidents.create', trans('tickets.button.new_event'), [], ['class' => 'btn btn-info']) !!}
        {!! link_to_route('admin.tickets.export', trans('misc.button.csv_export'), ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
    </div>
</div>
<table class="table table-striped table-condensed top-buffer" id="tickets-table">
    <thead>
        <tr>
            <th>{{ trans('misc.ticket_id') }}</th>
            <th>{{ trans('misc.ip') }}</th>
            <th>{{ trans('misc.domain') }}</th>
            <th>{{ trans('misc.type') }}</th>
            <th>{{ trans('misc.classification') }}</th>
            <th>{{ trans('tickets.events') }}</th>
            <th>{{ trans('tickets.notes') }}</th>
            <th>{{ trans('misc.status') }}</th>
            <th class="text-right">{{ trans('misc.action') }}</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>{!! Form::select('type_id', $types, null, ['placeholder' => '', 'id' => 'type_id', 'class' => 'form-control']) !!}</td>
            <td>{!! Form::select('class_id', $classes, null, ['placeholder' => '', 'id' => 'class_id', 'class' => 'form-control']) !!}</td>
            <td></td>
            <td></td>
            <td>{!! Form::select('statuses', $statuses, null, ['placeholder' => '', 'id' => 'statuses', 'class' => 'form-control']) !!}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
@endsection
