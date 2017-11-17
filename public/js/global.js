/**
 * Contains Javascript code that should be executed when the document is ready.
 */
$(document).ready(function() {
    // Initialize Material Design
    $('body').bootstrapMaterialDesign();

    /**
     * #confirm is a modal used for all action that need a confirmation.
     * Any confirm needs a title, message, confirm name (action) and route.
     */
    $('#confirm')
        .on('show.bs.modal', function(e) {
            var data = $(e.relatedTarget).data();
            $(this).find('.modal-title').text(data.title);
            $(this).find('.modal-body').text(data.message);
            $(this).find('.btnConfirm').text(data.confirm);
            $(this).find('.btnConfirm').addClass(data.confirmClass);

            var actionMethod = null;
            switch(data.action) {
                case 'enable':
                case 'disable':
                    actionMethod = 'PATCH';
                    break;
                case 'delete':
                    actionMethod = 'DELETE';
                    break;
            }

            $('.btnConfirm', this).data('route', data.route);
            $('.btnConfirm', this).data('method', actionMethod);
            $('.btnConfirm', this).data('callback', data.callback);
        })

        // When the model is hidden we need to reset it for a next use.
        .on('hidden.bs.modal', function () {
            $(this).closeConfirm();
        })

        .on('click', '.btnConfirm', function () {
            var confirmReq = $.ajax({
                url: $(this).data('route'),
                type: $(this).data('method'),
                data: null,
                dataType: 'json'
            });

            confirmReq.done( function (response) {
                $(this).closeConfirm();
                console.log(response);
                var cbFunction = $('#confirm').find('.btnConfirm').data('callback');
                if (typeof $(this)[cbFunction] === 'function') {
                    $(this)[cbFunction](response);
                }
                $(this).notify(response.message);
            });

            confirmReq.fail( function ( response ) {
                console.log(response);
                var errors = response.responseJSON;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                $(this).snackbar('<ul>'+errorsHtml+'</ul>')
            })
        });

});

$.fn.closeConfirm = function (){
    var confirm = $('#confirm');
    confirm.find('.modal-title').text('_title_');
    confirm.find('.modal-body').text('_message_');
    confirm.find('.btnConfirm').text('_confirm_');
    confirm.find('.btnConfirm').prop('class', 'btn btn-default btnConfirm');
}

/**
 * Helper function to completely reset a form.
 */
$.fn.resetForm = function(id) {
    var form = $('#'+id).find('form')[0];
    form.reset();
    form.action = null;
    form.method = null;
    $('#'+id).find('#id').val(null);
    return this;
};

$.fn.notify = function(message) {
    $.snackbar({
        content: '<div><i class="material-icons">settings_applications</i>&nbsp;'+message+'</div>',
        htmlAllowed: true
    });
    return this;
}

// Prepare any Ajax requests we want to make
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
