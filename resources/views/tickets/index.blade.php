@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.tickets') }}</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-8 text-right">
            {!! link_to_route('admin.tickets.create', trans('tickets.button.new_ticket'), [], ['class' => 'btn btn-info']) !!}
            {!! link_to_route('admin.tickets.export', trans('misc.button.csv_export'), ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
        </div>
    </div>

    <table class="table table-striped table-condensed top-buffer" id="tickets-table">
        <thead>
        <tr>
            <th>{{ trans('misc.ticket_id') }}</th>
            <th>{{ trans('misc.ip') }}</th>
            <th>{{ trans('misc.contact') }}</th>
            <th>{{ trans('misc.type') }}</th>
            <th>{{ trans('misc.classification') }}</th>
            <th>{{ trans('tickets.first_seen') }}</th>
            <th>{{ trans('tickets.last_seen') }}</th>
            <th>{{ trans('tickets.count') }}</th>
            <th>{{ trans('misc.status') }}</th>
            <th class="text-right">{{ trans('misc.action') }}</th>
        </tr>
        </thead>
    </table>

@endsection

@section('extrajs')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#tickets-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.tickets.search') .'/query/' !!}',
                columnDefs: [ {
                    targets: -1,
                    data: null,
                    defaultContent: " "
                } ],
                language: {
                    url: '{{ asset("/i18n/$auth_user->locale.json") }}'
                },
                columns: [
                    { data: 'id', name: 'ticket td' },
                    { data: 'ip', name: 'ip' },
                    { data: null, name: 'ip contact' },
                    { data: 'type_id', name: 'type' },
                    { data: 'class_id', name: 'class' },
                    { data: null, name: 'first seen' },
                    { data: null, name: 'last seen' },
                    { data: null, name: 'event count' },
                    { data: 'status_id', name: 'status' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, class: "text-right" },
                ]
            });
        });
    </script>
@endsection