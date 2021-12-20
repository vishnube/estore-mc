$(document).ready(function () {


    $(document).on('click', '#add_cmp', function () {

        // Initializing the modal with default values.
        $('#cmp_add_modal #cmp_add_form').initForm();

        $('#cmp_add_modal').find('.modal-title').text('ADD COMPANY')

        $('#cmp_add_modal').modal('show');
    });


    $('form#cmp_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("companies/save"), input, afterCompaniesave)
    });

});

function beforeEdit_cmp(cmp_id) {
    var input = { cmp_id: cmp_id };
    $('form#cmp_add_form').postForm(site_url("companies/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#cmp_add_modal').modal('show');
    });
}
