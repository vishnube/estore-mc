$(document).ready(function () {

    $(document).on('click', '#add_stky', function () {

        // Initializing the modal with default values.
        $('#stky_add_modal #stky_add_form').initForm();

        $('#stky_add_modal').find('.modal-title').text('ADD SETTINGS KEY')

        $('#stky_add_modal').modal('show');
    });


    $('form#stky_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("settings_keys/save"), input, afterSettingsKeysSave)
    });

});

function beforeEdit_stky(stky_id) {
    var input = { stky_id: stky_id };
    $('form#stky_add_form').postForm(site_url("settings_keys/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#stky_add_modal').modal('show');
        $('#stky_add_modal').find('.modal-title').text('EDIT SETTINGS KEY')
    });
}
