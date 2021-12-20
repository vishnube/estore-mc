$(document).ready(function () {

    // Initializing the from with default values.
    $('form#emply_add_form').initForm();

    $('form#emply_add_form').on('submit', function (e) {

        // If no task assigned
        if (!tsk_emply_add)
            return;

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        // adding an additional input
        input.push({ name: "mbr_ob", value: 0 });
        input.push({ name: "mbr_cb", value: 0 });

        $(this).postForm(site_url("employees/save"), input, afterEmployeeSave)
    });

});

function beforeEditEmployee(mbr_id) {
    var input = { mbr_id: mbr_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#emply_add_form .sr-form-tag .sr-form-title').html('EDIT EMPLOYEE');

    $('form#emply_add_form').postForm(site_url("employees/before_edit"), input, function (res, form) {

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#emply_add_form .sr-form-tag .sr-form-title').html('EDIT EMPLOYEE');
    }, function (res, form) {
        activateTab('list');
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}