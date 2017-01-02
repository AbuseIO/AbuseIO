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

    // default filter options
    var status_filter = { "search" : "open"};
    var type_id_filter = null;
    var classification_id_filter = null;

    // user option filter options
    if (user_options != undefined && user_options.ticket_status_filter != undefined) {
        status_filter = { "search" : user_options.ticket_status_filter };
    }
    if (user_options != undefined && user_options.ticket_type_filter != undefined) {
        type_id_filter = { "search" : user_options.ticket_type_filter };
    }
    if (user_options != undefined && user_options.ticket_classification_filter != undefined) {
        classification_id_filter = { "search" : user_options.ticket_classification_filter };
    }

    var table = $('#tickets-table').DataTable( {
        processing: true,
        serverSide: true,
        ajax: searchroute,
        columnDefs: [ {
            targets: -1,
            data: null,
            defaultContent: " "
        } ],
        "searchCols": [
            null,
            null,
            null,
            type_id_filter,
            classification_id_filter,
            null,
            null,
            status_filter,
            null
        ],
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

            // set the status filter default on 'OPEN' or the user option if available
            if (user_options != undefined && user_options.ticket_status_filter != undefined) {
                $("#statuses").val(user_options.ticket_status_filter);
            } else {
                $("#statuses").val('OPEN');
            }

            // set the status filter to the user option if available
            if (user_options != undefined && user_options.ticket_type_filter != undefined) {
                $("#type_id").val(user_options.ticket_type_filter);
            }

            // set the status filter to the user option if available
            if (user_options != undefined && user_options.ticket_classification_filter != undefined) {
                $("#class_id").val(user_options.ticket_classification_filter);
            }

        } });

    // if we have saved the sort order, use it
    if (user_options != undefined && user_options.ticket_sort_order != undefined) {
        table.order([user_options.ticket_sort_order.column, user_options.ticket_sort_order.dir]);
    }
});
