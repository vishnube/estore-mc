$(document).ready(function () {


    $(document).on('click', '#add_cstr_cat', function () {

        // Initializing the modal with default values.
        $('#cstr_cat_add_modal #cstr_cat_add_form').initForm();

        $('#cstr_cat_add_modal').find('.modal-title').text('ADD CATEGORY')

        $('#cstr_cat_add_modal').modal('show');
    });


    $('form#cstr_cat_add_form').on('submit', function (e) {

        e.preventDefault();

        var input = $(this).serializeArray();

        // adding an additional inputs
        // var mbrtp_id @ central_stores/index.php
        input.push({ name: "cat_fk_member_types", value: mbrtp_id });

        $(this).postForm(site_url("categories/save"), input, afterCentralStoreCategorySave)
    });

});

function beforeEditCentralStoreCategory(cat_id) {
    var input = { cat_id: cat_id };
    $('form#cstr_cat_add_form').postForm(site_url("categories/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#cstr_cat_add_modal').modal('show');
    });
}
