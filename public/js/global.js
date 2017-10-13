/**
 * Contains Javascript code that should be executed when the page is completely loaded.
 */
$(document).ready(function() {
    $(window).load(function() {
        // Initialize Material Design
        $.material.init();

        if (msgSnack) {
            $.snackbar({
                content: '<div class="valign-center"><i class="material-icons">settings_applications</i>&nbsp;' + msgSnack + '</div>',
                htmlAllowed: true
            });
        }
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
