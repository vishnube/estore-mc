$(document).ready(function () {

    $(document).on('click', '#add_stct', function () {

        // Initializing the modal with default values.
        $('#stct_add_modal #stct_add_form').initForm();

        $('#stct_add_modal').find('.modal-title').text('ADD SETTINGS CATEGORY')

        $('#stct_add_modal').modal('show');
    });


    $('form#stct_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("settings_categories/save"), input, afterSettingsCategorySave)
    });

});

function beforeEdit_stct(stct_id) {
    var input = { stct_id: stct_id };
    $('form#stct_add_form').postForm(site_url("settings_categories/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#stct_add_modal').modal('show');
        $('#stct_add_modal').find('.modal-title').text('EDIT SETTINGS CATEGORY')
    });
}
