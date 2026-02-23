function fnTicketFilter() {
    var table = $('#tickets-table').DataTable();
    var type_id = $("#type_id option:selected").val();
    var class_id = $("#class_id option:selected").val();
    var statuses = $("#statuses option:selected").val();
    var notesFilter = $("#notes_filter option:selected").val();
    table.column(5).search(type_id);
    table.column(6).search(class_id);
    table.column(8).search(notesFilter);
    table.column(9).search(statuses).draw();
}

$(document).ready(function() {

    // default filter options
    var status_filter = { "search" : "OPEN_ESCALATED"};
    var type_id_filter = null;
    var classification_id_filter = null;
    var notes_filter = null;

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
        autoWidth: false,
        ajax: searchroute,
        columnDefs: [
            {
                targets: -1,
                data: null,
                defaultContent: " "
            },
            {
                targets: [3, 4],
                className: 'col-ip-owner',
                width: '120px'
            }
        ],
        "searchCols": [
            null,                // id
            null,                // ip
            null,                // domain
            null,                // ip_contact_reference
            null,                // domain_contact_reference
            type_id_filter,      // type
            classification_id_filter, // class
            null,                // events
            notes_filter,        // notes
            status_filter,       // status
            null,                // updated_at
            null                 // actions
        ],
        language: {
            url: locale
        },
        columns: [
            { data: 'id', name: 'tickets.id' },
            { data: 'ip', name: 'tickets.ip' },
            { data: 'domain', name: 'tickets.domain' },
            { data: 'ip_contact_reference', name: 'tickets.ip_contact_reference', className: 'col-ip-owner' },
            { data: 'domain_contact_reference', name: 'tickets.domain_contact_reference', className: 'col-domain-owner' },
            { data: 'type_id', name: 'tickets.type_id' },
            { data: 'class_id', name: 'tickets.class_id' },
            { data: 'event_count', name: 'event_count', searchable: false },
            { data: 'notes_count', name: 'notes_count', searchable: true },
            { data: 'status_id', name: 'tickets.status_id' },
            { data: 'updated_at', name: 'tickets.updated_at' },
            { data: 'actions', orderable: false, searchable: false, class: "text-end" }
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

            $('#notes_filter').on('change', function () {
                fnTicketFilter();
            });

            // Clicking the unread notes alert sets the Notes selector to Unread and applies filter
            $('#unread-notes-alert').on('click', function (e) {
                e.preventDefault();
                $('#notes_filter').val('UNREAD').trigger('change');
            });

            // set the status filter default on 'OPEN' or the user option if available
            if (user_options != undefined && user_options.ticket_status_filter != undefined) {
                $("#statuses").val(user_options.ticket_status_filter);
            } else {
                $("#statuses").val('OPEN_ESCALATED');
            }

            // set the status filter to the user option if available
            if (user_options != undefined && user_options.ticket_type_filter != undefined) {
                $("#type_id").val(user_options.ticket_type_filter);
            }

            // set the status filter to the user option if available
            if (user_options != undefined && user_options.ticket_classification_filter != undefined) {
                $("#class_id").val(user_options.ticket_classification_filter);
            }

            // default notes filter: All
            $("#notes_filter").val("");

        } });

    // if we have saved the sort order, use it
    if (user_options != undefined && user_options.ticket_sort_order != undefined) {
        table.order([user_options.ticket_sort_order.column, user_options.ticket_sort_order.dir]);
    }

    // Wire per-column search for IP/Domain owner references
    $('#ip_owner_ref').on('keyup change', function () {
        table.column(3).search(this.value).draw();
    });
    $('#domain_owner_ref').on('keyup change', function () {
        table.column(4).search(this.value).draw();
    });
});
