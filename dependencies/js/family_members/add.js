$(document).ready(function () {

    // Initializing the from with default values.
    $('form#fmlm_add_form').initForm();

    $(document).on('click', '.add_fmlm', function () {

        var fmly_id = $(this).closest('tr').find('.fmly_id').val();
        var fmly_name = $(this).closest('tr').find('.mbr_name').val();

        $('form#fmlm_add_form .fmlm-back-btn').hide();

        // If no task assigned
        if (!tsk_fmly_add)
            return;

        // Initializing the modal with default values.
        $('#fmlm_add_modal #fmlm_add_form').initForm();

        $('#fmlm_add_modal').find('.modal-title').text('ADD FAMILY MEMBER')
        $('#fmlm_add_modal').find('#fmly_name').html("'" + fmly_name + "' FAMILY");
        $('#fmlm_add_modal').find('#fmlm_fk_families').val(fmly_id);
        $('#fmlm_add_modal').modal('show');
    });

});

$('form#fmlm_add_form .fmlm-back-btn').on('click', function () {
    var fmly_id = $('form#fmlm_add_form #fmlm_fk_families').val();
    if (!fmly_id)
        return;
    $('#fmlm_add_modal').modal('hide');
    show_fmlm_details($("#tbl_fmly .fmly_id[value=" + fmly_id + "]").closest('td').find('.mbr_id').val());
});


$('form#fmlm_add_form').on('submit', function (e) {

    // If no task assigned
    if (!tsk_fmly_add && !tsk_fmly_edit)
        return;

    e.preventDefault();

    // Enabling disabled elements to take its values.
    // Because serializeArray() will not include the values of disabled elements.   
    var disabled = $(this).find(':input:disabled').removeAttr('disabled');

    var input = $(this).serializeArray();

    // Again desabling the desabled elements
    disabled.attr('disabled', 'disabled');

    $(this).postForm(site_url("family_members/save"), input, function (res, form, input) {
        var fmly_id = getInputValue(input, 'fmlm_fk_families');
        var mbr_id = getInputValue(input, 'mbr_id'); // mbr_id of fmlm

        // If the action was ADD, Reseting the memeber count
        if (typeof mbr_id != 'undefined' && !mbr_id) {
            reset_fmlm_count(fmly_id, '+');
        }

        $('#fmlm_add_modal').modal('hide');
        showSuccessToast('Member saved successfully');
        show_fmlm_details($("#tbl_fmly .fmly_id[value=" + fmly_id + "]").closest('td').find('.mbr_id').val());
    })
});

function beforeEditFamilyMember(mbr_id) {
    var input = { mbr_id: mbr_id }; // mbr_id of fmlm

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#fmlm_add_modal').find('.modal-title').text('EDIT MEMBER');

    $('#fmlm_add_modal').modal('show');

    $('form#fmlm_add_form').postForm(site_url("family_members/before_edit"), input, function (res, form) {

        // hiding Show details modal
        $('#family_member_details_modal').modal('hide');

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#fmlm_add_modal').find('.modal-title').text('EDIT MEMBER');
    }, function (res, form) {
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}