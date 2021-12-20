$(document).ready(function () {


    $(document).on('click', '#add_gdn', function () {

        // Initializing the modal with default values.
        $('#gdn_add_modal #gdn_add_form').initForm();

        $('#gdn_add_modal').find('.modal-title').text('ADD GODOWN')

        $('#gdn_add_modal').modal('show');
    });


    $('form#gdn_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("godowns/save"), input, afterGodownSave)
    });

});

function beforeEdit_gdn(gdn_id) {
    var input = { gdn_id: gdn_id };
    $('form#gdn_add_form').postForm(site_url("godowns/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#gdn_add_modal').modal('show');
    });
}
