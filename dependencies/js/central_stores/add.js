$(document).ready(function () {

    // Initializing the from with default values.
    $('form#cstr_add_form').initForm();

    $('form#cstr_add_form').on('submit', function (e) {

        // If no task assigned
        if (!tsk_cstr_add)
            return;

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var form_data = new FormData();

        // Getting form input values in object format
        var input = objectifyForm($(this).serializeArray());

        // Adding object values in to form_data
        for (var key in input) {
            form_data.append(key, input[key]);
        }

        // Adding uploaded licence details
        var cstr_lic = $("#cstr_lic").prop("files")[0];
        form_data.append("cstr_lic", ifDef(cstr_lic, cstr_lic, ''));

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("central_stores/save"), form_data, afterCentralStoreSave, function (r, form) {
            showValidationErrors(r.v_error, form);
            showOtherErrors(r.o_error);

            if ($.isEmptyObject(r.v_error))
                return;

            // cstr_lic validation
            if (typeof r.v_error.cstr_lic_error != 'undefined')
                form.find('#cstr_lic').closest('.sr-input-group').append(r.v_error.cstr_lic_error);

        }, true, true, true);


    });

    $('#cstr_add_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();
        $(this).closest('form').find('.tlk_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });

    $('#cstr_add_form .dst_option').change(function () {
        $(this).closest('form').find('.tlk_option').noOption();

        var t = $(this).closest('form').find('.tlk_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('taluks/get_options', {
            tlk_fk_districts: d
        }, t, t.closest('div'));
    });

});




function aftercstrAddFormReset(form) {
    form.find('#cstr_add_form .dst_option').noOption();
    form.find('#cstr_add_form .tlk_option').noOption();

    // File Uploader
    bsCustomFileInput.init();
    form.find('.custom-file-label').html('');
}



function resetFile(obj) {
    $(obj).closest('.input-group').find('.custom-file-input').val('');
    $(obj).closest('.input-group').find('.custom-file-label').html('')
}

function beforeEditcentralStore(mbr_id) {
    var input = { mbr_id: mbr_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#cstr_add_form .sr-form-tag .sr-form-title').html('EDIT CENTRAL STORE');
    $('form#cstr_add_form').postForm(site_url("central_stores/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#cstr_add_form .sr-form-tag .sr-form-title').html('EDIT CENTRAL STORE');
        form.find('.stt_option').val(res.stt_id);
        form.find('.dst_option').html(res.dst_option);
        form.find('.tlk_option').html(res.tlk_option);
    }, function (res, form) {
        activateTab('list');
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}