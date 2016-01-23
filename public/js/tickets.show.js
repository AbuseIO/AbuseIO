$(document).ready(function() {
    // Flip everything hidden using Bootstrap to hidden by jQuery.
    $('.hidden').removeClass('hidden').hide();

    // If you click one of the Flip buttons
    $('.btnFlip').click(function () {
        if ($(this).hasClass('btnHide')) {
            action = 'hide';
            type = 'patch';
        }
        if ($(this).hasClass('btnRead')) {
            action = 'view';
            type = 'patch';
        }
        if ($(this).hasClass('btnDelete')) {
            action = 'delete';
            type = 'delete';
        }

        // Execute this ajax request
        $.ajax({
            url: '/admin/notes/1',
            type: type,
            data: {
                'action': action,
                'note': this.value,
                '_token': $('input[name=_token]').val()
            },
            context: this,
            success: function(data) {
                switch(data) {
                    case 'flip:OK':
                        // Switch button colors
                        $(this).toggleClass('btn-success btn-warning');

                        // Switch button text
                        $(this).find('span').each(function() {
                            $(this).toggle();
                        });

                        // If 'hidden/visible' was clicked, we need to toggle
                        // the 'panel-hidden' class on the element.
                        if ($(this).hasClass('btnHide')) {
                            $(this).closest('.ticket-note').toggleClass('panel-hidden');
                        }

                        // If 'read/unread' was clicked, we need to toggle
                        // the 'panel-info' class on the element.
                        if ($(this).hasClass('btnRead')) {
                            $(this).closest('.ticket-note').toggleClass('panel-info');
                        }
                        break;
                    case 'delete:OK':
                        if ($(this).hasClass('btnDelete')) {
                            $(this).closest('.ticket-note').hide();
                        }
                        break;
                }
            }
        });
    });

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
            { data: 'id', name: 'tickets.id' },
            { data: 'ip', name: 'tickets.ip' },
            { data: 'domain', name: 'tickets.domain' },
            { data: 'type_id', name: 'tickets.type_id' },
            { data: 'class_id', name: 'tickets.class_id' },
            { data: 'event_count', name: 'event_count', searchable: false },
            { data: 'notes_count', name: 'notes_count', searchable: false },
            { data: 'status_id', name: 'tickets.status_id' },
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
    
    $('#tickets-table').DataTable( {
        initComplete: function () {
            // Grab the selects and add a search event onChange

            alert('hoi');

            /*this.api().columns().every( function () {
                var column = this;

                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            });*/
        }
    });
});
