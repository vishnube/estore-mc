$(document).ready(function () {

    // Initializing the from with default values.
    $('form#estr_add_form').initForm();

    $('form#estr_add_form').on('submit', function (e) {

        // If no task assigned
        if (!tsk_estr_add)
            return;

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("estores/save"), input, afterestoreSave)
    });



});

function beforeEditestore(mbr_id) {
    var input = { mbr_id: mbr_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#estr_add_form .sr-form-tag .sr-form-title').html('EDIT ESTORE');
    $('form#estr_add_form').postForm(site_url("estores/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#estr_add_form .sr-form-tag .sr-form-title').html('EDIT ESTORE');
    }, function (res, form) {
        activateTab('list');
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}