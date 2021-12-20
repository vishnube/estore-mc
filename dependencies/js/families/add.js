$(document).ready(function () {

    $('#fmly_add_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();
        $(this).closest('form').find('.tlk_option').noOption();
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.wrd_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });

    $('#fmly_add_form .dst_option').change(function () {
        $(this).closest('form').find('.tlk_option').noOption();
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.wrd_option').noOption();

        var t = $(this).closest('form').find('.tlk_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('taluks/get_options', {
            tlk_fk_districts: d
        }, t, t.closest('div'));
    });

    $('#fmly_add_form .tlk_option').change(function () {
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.wrd_option').noOption();
        var t = $(this).closest('form').find('.ars_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('areas/get_options', {
            tlk_id: d
        }, t, t.closest('div'));
    });

    $('#fmly_add_form .ars_option').change(function () {
        $(this).closest('form').find('.wrd_option').noOption();
        var t = $(this).closest('form').find('.wrd_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('wards/get_options2', {
            ars_id: d
        }, t, t.closest('div'));
    });



    // Initializing the from with default values.
    $('form#fmly_add_form').initForm();

    $('form#fmly_add_form').on('submit', function (e) {

        // If no task assigned
        if (!tsk_fmly_add)
            return;

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("families/save"), input, afterFamilySave, function (r, form, input) {
            showValidationErrors(r.v_error, form);
            showOtherErrors(r.o_error);

            if ($.isEmptyObject(r.v_error))
                return;

            // Validating Family Members
            $.each(r.v_error, function (index, value) {
                if (index.indexOf('fmlm') != -1) {

                    // Taking index of the field
                    var temp = index.match(/\[(.*)\]/);
                    var i = temp[1];

                    var field = index.substr(0, index.indexOf('['));
                    form.find('.' + field).eq(i).parent().append(value);
                }
            });
        })
    });

});


function afterfmlyAddFormReset() {
    $('#fmly_add_form .dst_option').noOption();
    $('#fmly_add_form .tlk_option').noOption();
    $('#fmly_add_form .ars_option').noOption();
    $('#fmly_add_form .wrd_option').noOption();

    // Showing FamilyMember-add container.    
    $('.dv-add-fmlm').show();
    $('.dv-add-fmlm .tbl-add-fmlm').initMovementTable(false);
}

function on_new_fmlm_add_row_created(container, lastRow, newRow) {
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    $('[data-mask]').inputmask()
}



function beforeEditFamily(mbr_id) {
    var input = { mbr_id: mbr_id };

    $('#fmly_add_form #mbr_id').val(mbr_id);

    // Hiding FamilyMember-add container when EDITING
    $('.dv-add-fmlm').hide();

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#fmly_add_form .sr-form-tag .sr-form-title').html('EDIT FAMILY');

    $('form#fmly_add_form').postForm(site_url("families/before_edit"), input, function (res, form) {

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#fmly_add_form .sr-form-tag .sr-form-title').html('EDIT FAMILY');
        form.find('.stt_option').val(res.stt_id);
        form.find('.dst_option').html(res.dst_option);
        form.find('.tlk_option').html(res.tlk_option);
        form.find('.ars_option').html(res.ars_option);
        form.find('.wrd_option').html(res.wrd_option);

        // Hiding FamilyMember-add container when EDITING
        $('.dv-add-fmlm').hide();

    }, function (res, form) {
        activateTab('list');
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}