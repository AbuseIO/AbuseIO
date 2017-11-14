/*
 * User related
 */
$(document).ready(function() {

    var userModal = $('#user');
    var userForm = $('#userForm');

    userModal
        // When opening the user modal, set some data and/or fetch user data.
        .on('show.bs.modal', function (e) {
            // When the user modal is shown, figure out if it's new or edit user.
            var data = $(e.relatedTarget).data();

            switch (data.action) {
                case 'new':
                    //$('#dropdown-menu').find('select').dropdown();
                    break;
                case 'edit':
                    // Fetch current user data with Ajax call and fill form
                    $.get("http://localhost:8000/admin/users/" + data.recordId, function (response) {

                        // Fill the normal input fields, no selects, checkboxes and no dropdown fakeinput elements
                        userModal.find(':input').not(':checkbox :button, select, .fakeinput').each(function() {
                            if (response.hasOwnProperty(this.id)) {
                                $(this).val(response[this.id]);
                            }
                        });

                        // Check or uncheck checkboxes
                        userModal.find(':checkbox').each(function() {
                            if (response.hasOwnProperty(this.id)) {
                                $(this).prop('checked', response[this.id]);
                            }
                        });

                        // Select saved values.
                        userModal.find('select').each(function() {
                            if (response.hasOwnProperty(this.id)) {
                                if ($.type(response[this.id]) === 'array') {
                                    // This is a multi select, treat differently.
                                    $(this).val($.map(response[this.id], function(a) {return a['id']})).change()
                                } else {
                                    $(this).val(response[this.id]).change();
                                }
                            }
                        });

                        //$('#dropdown-menu').find('select').dropdown();
                    });
                    break;
            }

            // Set modal title, form action and method.
            userForm.find('.modal-title').first().text(data.title);
            userForm.find('[name="id"]').val(data.recordId);

            $('#userForm', this).prop('action', data.formAction);
            $('#userForm', this).prop('method', data.formMethod);

        })

        // When closing the user model, clear the form fields.
        .on('hide.bs.modal', function () {
            $(this).resetForm(this.id);
        })

        // When "Save" button is clicked.
        .on('click', '.btn-success', function () {
            $.ajax({
                url: userForm.attr('action'),
                type: userForm.attr('method'),
                data: userForm.serialize(),
                dataType: 'json',
                success: function (response)
                {
                    // User update was a success; close Modal and update card and notify user.
                    userModal.modal('hide');
                    $(this).usercardUpdate(response);
                    $(this).notify(response.message)
                },
                error: function (response)
                {
                    // Something went wrong. Collect errors and notify user.
                    console.log(response);
                    var errors = response.responseJSON;
                    var errorsHtml= '';
                    $.each( errors, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    $(this).notify('<ul>'+errorsHtml+'</ul>')
                }
            });
        });

    // Create fancy dropdowns from all select elements.
    //$('#dropdown-menu').find('select').dropdown();

    $.fn.usercardUpdate = function(json) {
        var userData = json.user;
        var cardFields = ['first_name', 'last_name', 'disabled', 'locale', 'account_id', 'roles'];
        var id = userData.id;

        $(cardFields).each(function(key, field) {
            if (userData.hasOwnProperty(field)) {
                var currentField = $('#'+field+'_'+id);
                switch(field) {
                    case 'disabled':
                        if (userData.disabled === true) {
                            $('#card_'+id).find('.card-header')
                                .addClass('bg-secondary')
                                .removeClass('bg-blue');
                                $('#btnEnable_'+id).show();
                                $('#btnDisable_'+id).hide();
                        } else {
                            $('#card_'+id).find('.card-header')
                                .removeClass('bg-secondary')
                                .addClass('bg-blue');
                            $('#btnEnable_'+id).hide();
                            $('#btnDisable_'+id).show();
                        }
                        break;
                    case 'account_id':
                        currentField.text(userData.account.name);
                        break;
                    case 'roles':
                        currentField.empty();
                        if (userData.roles.length === 0) {
                            currentField.append('<span class="badge badge-secondary">'+t_none+'</span>');
                            break;
                        }
                        $(userData.roles).each(function() {
                            currentField.append('<span class="badge badge-primary ml-1">'+this.name+'</span>');
                        });
                        break;
                    default:
                        currentField.text(userData[field]);
                }
            }
        });
    }
});