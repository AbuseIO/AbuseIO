$(document).ready(function() {
    // Limit conversion of Bootstrap hidden class to only read/unread button spans
    $('.btnRead span.hidden').removeClass('hidden').hide();

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
                    case 'delete:OK':
                        // Reload the page to reflect server changes; preserve active tab via hash
                        if (history.replaceState) {
                            history.replaceState(null, null, window.location.hash || '#communication');
                        }
                        window.location.reload();
                        break;
                    default:
                        // Fallback: reload to ensure UI stays in sync
                        window.location.reload();
                }
            }
        });
    });

    // Persist active tab via URL hash on load
    var hash = window.location.hash;
    if (hash) {
        var $tab = $('.nav.nav-tabs a[href="' + hash + '"]');
        if (!$tab.length) {
            $tab = $('.nav-tabs a[href="' + hash + '"]');
        }
        if ($tab.length) {
            $tab.tab('show');
        }
    }

    // Keep hash in sync when switching tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr('href');
        if (target) {
            if (history.replaceState) {
                history.replaceState(null, null, target);
            } else {
                window.location.hash = target;
            }
        }
    });
});
