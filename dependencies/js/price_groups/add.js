$(document).ready(function () {

    // Initializing the from with default values.
    $('form#pgp_add_form').initForm();

    $(document).on('click', '.add_pgp', function () {

        // If no task assigned
        if (!tsk_pgp_add)
            return;

        // Initializing the modal with default values.
        $('#pgp_add_modal #pgp_add_form').initForm();

        $('#pgp_add_modal').find('.modal-title').text('ADD PRICE GROUP')

        $('#pgp_add_modal').modal('show');
    });

});




$('form#pgp_add_form').on('submit', function (e) {

    // If no task assigned
    if (!tsk_pgp_add && !tsk_pgp_edit)
        return;

    e.preventDefault();

    // Enabling disabled elements to take its values.
    // Because serializeArray() will not include the values of disabled elements.   
    var disabled = $(this).find(':input:disabled').removeAttr('disabled');

    var input = $(this).serializeArray();

    // Again desabling the desabled elements
    disabled.attr('disabled', 'disabled');

    // adding an additional inputs
    // var mbrtp_id @ employees/index.php
    //input.push({ name: "pgp_fk_member_types", value: mbrtp_id });

    $(this).postForm(site_url("price_groups/save"), input, function () {
        load_price_group_options();

        var pageNo = '';
        var pgp_id = getInputValue(input, 'pgp_id');

        // If the action was Edit
        if (typeof pgp_id != 'undefined' && pgp_id > 0) {
            pageNo = $("#pgp_pagination").curPage();
        }

        // If the action was Add
        else
            pageNo = 0;

        showSuccessToast('Price group saved successfully');
        load_price_groups(pageNo);
    })
});

function afterPgpAddFormReset(frm) {

}

function beforeEditPriceGroup(pgp_id) {
    var input = { pgp_id: pgp_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#pgp_add_modal').find('.modal-title').text('EDIT PRICE GROUP');

    $('#pgp_add_modal').modal('show');

    $('form#pgp_add_form').postForm(site_url("price_groups/before_edit"), input, function (res, form) {

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#pgp_add_modal').find('.modal-title').text('EDIT PRICE GROUP');
    }, function (res, form) {
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}