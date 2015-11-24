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
            <th></th>
            <th>{{ trans('misc.ticket_id') }}</th>
            <th>{{ trans('misc.ip') }}</th>
            <th>{{ trans('misc.domain') }}</th>
            <th>{{ trans('misc.type') }}</th>
            <th>{{ trans('misc.classification') }}</th>
            <th>{{ trans('tickets.count') }}</th>
            <th>{{ trans('misc.status') }}</th>
            <th class="text-right">{{ trans('misc.action') }}</th>
        </tr>
        </thead>
    </table>

@endsection

@section('extrajs')
    <script>
        function format ( d ) {
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" width="90%" align="center">'+
                    '<tr>'+
                        '<td><b><u>IP Contact: </u></b></td>'+
                        '<td><b><u>Domain Contact: </u></b></td>'+
                    '</tr>'+

                    '<tr>'+
                        '<td>'+
                            '<table width="100%">'+
                                '<tr><td><b>Account: </b></td><td>' + d.ip_contact_account_id + '</td></tr>'+
                                '<tr><td><b>Reference: </b></td><td>' + d.ip_contact_reference + '</td></tr>'+
                                '<tr><td><b>Name: </b></td><td>' + d.ip_contact_name + '</td></tr>'+
                            '</table>'+
                        '</td>'+
                        '<td>'+
                            '<table width="100%">'+
                                '<tr><td><b>Account: </b></td><td>' + d.domain_contact_account_id + '</td></tr>'+
                                '<tr><td><b>Reference: </b></td><td>' + d.domain_contact_reference + '</td></tr>'+
                                '<tr><td><b>Name: </b></td><td>' + d.domain_contact_name + '</td></tr>'+
                            '</table>'+
                        '</td>'+
                    '</tr>'+
                    '</table>';
        }

        $(document).ready(function() {
            var table = $('#tickets-table').DataTable( {
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
                    {
                        className:      'details-control',
                        orderable:      false,
                        data:           null,
                        defaultContent: ''
                    },
                    { data: 'id' },
                    { data: 'ip' },
                    { data: 'domain' },
                    { data: 'type_id' },
                    { data: 'class_id' },
                    { data: null },
                    { data: 'status_id' },
                    { data: 'actions', orderable: false, searchable: false, class: "text-right" }
                ]
            } );

            $('#tickets-table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );
        } );

    </script>
@endsection
