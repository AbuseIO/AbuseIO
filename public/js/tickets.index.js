function format (d) {
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

function fnTicketFilter() {
    var table = $('#tickets-table').DataTable();
    var type_id = $("#type_id option:selected").val();
    var class_id = $("#class_id option:selected").val();
    var statuses = $("#statuses option:selected").val();
    table.column(4).search(type_id);
    table.column(5).search(class_id);
    table.column(8).search(statuses).draw();
}

$(document).ready(function() {
    var table = $('#tickets-table').DataTable( {
        processing: true,
        serverSide: true,
        ajax: searchroute,
        columnDefs: [ {
            targets: -1,
            data: null,
            defaultContent: " "
        } ],
        language: {
            url: locale
        },
        columns: [
            { className: 'details-control', orderable: false, defaultContent: '' },
            { data: 'id', name: 'tickets.id' },
            { data: 'ip', name: 'tickets.ip' },
            { data: 'domain', name: 'tickets.domain' },
            { data: 'type_id', name: 'tickets.type_id' },
            { data: 'class_id', name: 'tickets.class_id' },
            { data: 'event_count', name: 'event_count', searchable: false },
            { data: 'notes_count', name: 'notes_count', searchable: false },
            { data: 'status_id', name: 'tickets.status_id' },
            { data: 'actions', orderable: false, searchable: false, class: "text-right" }
        ],
        initComplete: function () {
            $('#type_id').on('change', function () {
                fnTicketFilter();
            });

            $('#class_id').on('change', function () {
                fnTicketFilter();
            });

            $('#statuses').on('change', function () {
                fnTicketFilter();
            });
        }
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
});
