$(document).ready(function () {

    // Initializing the from with default values.
    $('form#prty_add_form').initForm();

    $('form#prty_add_form').on('submit', function (e) {

        // If no task assigned
        if (!tsk_prty_add)
            return;

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("parties/save"), input, afterPartySave, function (r, form, input) {
            showValidationErrors(r.v_error, form);
            showOtherErrors(r.o_error);

            if ($.isEmptyObject(r.v_error))
                return;

            // Validating GST
            $.each(r.v_error, function (index, value) {
                if (index.indexOf('gst') != -1) {

                    // Taking index of the field
                    var temp = index.match(/\[(.*)\]/);
                    var i = temp[1];

                    var field = index.substr(0, index.indexOf('['));
                    form.find('.' + field).eq(i).parent().append(value);
                }
            });
        });
    });

});

function afterprtyAddFormReset() {
    // Showing GST-add container.    
    $('.dv-add-gst').show();
    $('.dv-add-gst .tbl-add-gst').initMovementTable(false);
}

function beforeEditParty(mbr_id) {
    var input = { mbr_id: mbr_id };

    // Hiding GST-add container when EDITING
    $('.dv-add-gst').hide();

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#prty_add_form .sr-form-tag .sr-form-title').html('EDIT PARTY');

    $('form#prty_add_form').postForm(site_url("parties/before_edit"), input, function (res, form) {

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#prty_add_form .sr-form-tag .sr-form-title').html('EDIT PARTY');

        // Hiding GST-add container when EDITING
        $('.dv-add-gst').hide();

    }, function (res, form) {
        activateTab('list');
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}