$(document).ready(function () {


    $(document).on('click', '#add_brnd', function () {

        // Initializing the modal with default values.
        $('#brnd_add_modal #brnd_add_form').initForm();

        $('#brnd_add_modal').find('.modal-title').text('ADD BRAND')

        $('#brnd_add_modal').modal('show');
    });


    $('form#brnd_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("brands/save"), input, afterBrandsave)
    });

});

function beforeEdit_brnd(brnd_id) {
    var input = { brnd_id: brnd_id };
    $('form#brnd_add_form').postForm(site_url("brands/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#brnd_add_modal').modal('show');
    });
}
