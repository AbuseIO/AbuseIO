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
            url: '/admin/notes/'+ this.value,
            type: type,
            data: {
                'action': action,
                'note': this.value,
                'ticket_id': $('input[name=ticket_id]').val(),
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
});
