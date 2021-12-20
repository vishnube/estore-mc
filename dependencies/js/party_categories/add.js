$(document).ready(function () {


    $(document).on('click', '#add_prty_cat', function () {

        // Initializing the modal with default values.
        $('#prty_cat_add_modal #prty_cat_add_form').initForm();

        $('#prty_cat_add_modal').find('.modal-title').text('ADD CATEGORY')

        $('#prty_cat_add_modal').modal('show');
    });


    $('form#prty_cat_add_form').on('submit', function (e) {

        e.preventDefault();

        var input = $(this).serializeArray();

        // adding an additional inputs
        // var mbrtp_id @ parties/index.php
        input.push({ name: "cat_fk_member_types", value: mbrtp_id });

        $(this).postForm(site_url("categories/save"), input, afterPartyCategorySave)
    });

});

function beforeEditPartyCategory(cat_id) {
    var input = { cat_id: cat_id };
    $('form#prty_cat_add_form').postForm(site_url("categories/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#prty_cat_add_modal').modal('show');
    });
}
