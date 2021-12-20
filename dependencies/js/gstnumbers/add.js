$(document).ready(function () {

    // Initializing the from with default values.
    $('form#gst_add_form').initForm();

    $(document).on('click', '.add_gst', function () {

        var mbr_id = $(this).closest('tr').find('.mbr_id').val();
        var mbr_name = $(this).closest('tr').find('.mbr_name').val();
        $('form#gst_add_form .gst-back-btn').hide();

        // Initializing the modal with default values.
        $('#gst_add_modal #gst_add_form').initForm();

        $('#gst_add_modal').find('.modal-title').text('ADD GST DETAILS')
        $('#gst_add_modal').find('#mbr_name').html(mbr_name);
        $('#gst_add_modal').find('#gst_fk_members').val(mbr_id);
        $('#gst_add_modal').modal('show');
    });

});

$('form#gst_add_form .gst-back-btn').on('click', function () {
    var mbr_id = $('form#gst_add_form #gst_fk_members').val();
    if (!mbr_id)
        return;
    $('#gst_add_modal').modal('hide');
    show_gst_details($("#tbl_mbr .mbr_id[value=" + mbr_id + "]").closest('td').find('.mbr_id').val());
});


$('form#gst_add_form').on('submit', function (e) {

    e.preventDefault();

    // Enabling disabled elements to take its values.
    // Because serializeArray() will not include the values of disabled elements.   
    var disabled = $(this).find(':input:disabled').removeAttr('disabled');

    var input = $(this).serializeArray();

    // Again desabling the desabled elements
    disabled.attr('disabled', 'disabled');

    // afterGstSave() @ index.js file of parties/employees/vehicles ect.
    $(this).postForm(site_url("gstnumbers/save"), input, afterGstSave, function (res, form) {
        showValidationErrors(res.v_error, form);

        if (typeof res.o_error != 'undefined' && res.o_error) {
            Swal.fire('Oops!', res.o_error, 'error');
            return false;
        }
    });
});

function beforeEditMemberMember(gst_id) {
    var input = { gst_id: gst_id }; // mbr_id of gst

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#gst_add_modal').find('.modal-title').text('EDIT MEMBER');

    $('#gst_add_modal').modal('show');

    $('form#gst_add_form').postForm(site_url("gstnumbers/before_edit"), input, function (res, form) {

        // hiding Show details modal
        $('#gstnumber_details_modal').modal('hide');

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#gst_add_modal').find('.modal-title').text('EDIT MEMBER');
    }, function (res, form) {
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}