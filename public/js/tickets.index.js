function fnTicketFilter() {
    var table = $('#tickets-table').DataTable();
    var type_id = $("#type_id option:selected").val();
    var class_id = $("#class_id option:selected").val();
    var statuses = $("#statuses option:selected").val();
    table.column(3).search(type_id);
    table.column(4).search(class_id);
    table.column(7).search(statuses).draw();
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
});
