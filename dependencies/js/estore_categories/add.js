$(document).ready(function () {


    $(document).on('click', '#add_estr_cat', function () {

        // Initializing the modal with default values.
        $('#estr_cat_add_modal #estr_cat_add_form').initForm();

        $('#estr_cat_add_modal').find('.modal-title').text('ADD CATEGORY')

        $('#estr_cat_add_modal').modal('show');
    });


    $('form#estr_cat_add_form').on('submit', function (e) {

        e.preventDefault();

        var input = $(this).serializeArray();

        // adding an additional inputs
        // var mbrtp_id @ estores/index.php
        input.push({ name: "cat_fk_member_types", value: mbrtp_id });

        $(this).postForm(site_url("categories/save"), input, afterEstoreCategorySave)
    });

});

function beforeEditEstoreCategory(cat_id) {
    var input = { cat_id: cat_id };
    $('form#estr_cat_add_form').postForm(site_url("categories/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#estr_cat_add_modal').modal('show');
    });
}
