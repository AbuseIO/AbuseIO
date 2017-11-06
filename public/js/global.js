/**
 * Contains Javascript code that should be executed when the document is ready.
 */
$(document).ready(function() {
    // Initialize Material Design
    $('body').bootstrapMaterialDesign();

    if (msgSnack) {
        $.snackbar({
            content: '<div class="valign-center"><i class="material-icons">settings_applications</i>&nbsp;' + msgSnack + '</div>',
            htmlAllowed: true
        });
    }

    // Make the call to delete the record, close the modal and remove the card from the page
    $('#confirmDelete').on('click', '.btn-danger', function(e) {
        var recordId = $(this).data('recordId');
        var targetRoute = $(this).data('targetRoute');
        $.delete(
            targetRoute,
            null,
            function() {
                $('#confirmDelete').modal('hide');
                $('#card_'+recordId).remove();
            },
            'json'
        );
    });

    //Set the correct targetUrl in the modal
    $('#confirmDelete').on('shown.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();

        $('.btn-danger', this).data('recordId', data.recordId);
        $('.btn-danger', this).data('targetRoute', data.targetRoute);

        // var action = $('#deleteform').attr('action');
        //
        // $('#deleteform').attr('action', action+'/'+data.actionRoute);
        //console.log($('.btn-warning', this).data());
    });

    $('.search-label').click(function(e) {
        $('.search_box').addClass('open');
        // $('#search_query').focus();
    });

    $('.search_box').focusout(function(e) {
        $('.search_box').removeClass('open');
    });
});

/**
 * Helper function to clear saved values from a search form.
 */
function clearSearchForm()
{
    $('#search-form :input').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
    $('#search-form select').val(-1);
    $('#search-form :checkbox, :radio').prop('checked', false);
}

// Prepare any ajax requests we want to make
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Create jQuery DELETE/PUT function
jQuery.each( [ "put", "delete" ], function( i, method ) {
    jQuery[ method ] = function( url, data, callback, type ) {
        if ( jQuery.isFunction( data ) ) {
            type = type || callback;
            callback = data;
            data = undefined;
        }

        return jQuery.ajax({
            url: url,
            type: method,
            dataType: type,
            data: data,
            success: callback
        });
    };
});
