$(document).ready(function () {


    $(document).on('click', '#add_fmly_cat', function () {

        // Initializing the modal with default values.
        $('#fmly_cat_add_modal #fmly_cat_add_form').initForm();

        $('#fmly_cat_add_modal').find('.modal-title').text('ADD CATEGORY')

        $('#fmly_cat_add_modal').modal('show');
    });


    $('form#fmly_cat_add_form').on('submit', function (e) {

        e.preventDefault();

        var input = $(this).serializeArray();

        // adding an additional inputs
        // var mbrtp_id @ families/index.php
        input.push({ name: "cat_fk_member_types", value: mbrtp_id });

        $(this).postForm(site_url("categories/save"), input, afterFamilyCategorySave)
    });

});

function beforeEditFamilyCategory(cat_id) {
    var input = { cat_id: cat_id };
    $('form#fmly_cat_add_form').postForm(site_url("categories/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#fmly_cat_add_modal').modal('show');
    });
}
