$(document).ready(function () {


    $(document).on('click', '#add_emply_cat', function () {

        // Initializing the modal with default values.
        $('#emply_cat_add_modal #emply_cat_add_form').initForm();

        $('#emply_cat_add_modal').find('.modal-title').text('ADD CATEGORY')

        $('#emply_cat_add_modal').modal('show');
    });


    $('form#emply_cat_add_form').on('submit', function (e) {

        e.preventDefault();

        var input = $(this).serializeArray();

        // adding an additional inputs
        // var mbrtp_id @ employees/index.php
        input.push({ name: "cat_fk_member_types", value: mbrtp_id });

        $(this).postForm(site_url("categories/save"), input, afterEmployeeCategorySave)
    });

});

function beforeEditEmployeeCategory(cat_id) {
    var input = { cat_id: cat_id };
    $('form#emply_cat_add_form').postForm(site_url("categories/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#emply_cat_add_modal').modal('show');
    });
}
