$(document).ready(function () {
    $('#wrd_add_modal #wrd_add_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();
        $(this).closest('form').find('.tlk_option').noOption();
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.estr_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });

    $('#wrd_add_modal #wrd_add_form .dst_option').change(function () {
        $(this).closest('form').find('.tlk_option').noOption();
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.estr_option').noOption();

        var t = $(this).closest('form').find('.tlk_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('taluks/get_options', {
            tlk_fk_districts: d
        }, t, t.closest('div'));
    });

    $('#wrd_add_modal #wrd_add_form .tlk_option').change(function () {
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.estr_option').noOption();
        var t = $(this).closest('form').find('.ars_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('areas/get_options', {
            tlk_id: d
        }, t, t.closest('div'));
    });


    $('#wrd_add_modal #wrd_add_form .ars_option').change(function () {
        $(this).closest('form').find('.estr_option').noOption();
        var d = $(this).val();
        if (!d)
            return;

        t = $(this).closest('form').find('.estr_option');
        loadOption('estores/get_options_by_area', {
            ars_id: d
        }, t, t.closest('div'));
    });

    // Initializing the from with default values.
    $('form#wrd_add_form').initForm();

    $(document).on('click', '#add_wrd', function () {

        // If no task assigned
        if (!tsk_wrd_add)
            return;

        // Initializing the modal with default values.
        $('#wrd_add_modal #wrd_add_form').initForm();

        $('#wrd_add_modal').find('.modal-title').text('ADD WARD')

        $('#wrd_add_modal').modal('show');
    });

});



function afterWrdAddFormReset() {
    $('#wrd_add_form .dst_option').noOption();
    $('#wrd_add_form .tlk_option').noOption();
    $('#wrd_add_form .ars_option').noOption();
    $('#wrd_add_form .estr_option').noOption();
}


$('form#wrd_add_form').on('submit', function (e) {

    // If no task assigned
    if (!tsk_wrd_add && !tsk_wrd_edit)
        return;

    e.preventDefault();

    // Enabling disabled elements to take its values.
    // Because serializeArray() will not include the values of disabled elements.   
    var disabled = $(this).find(':input:disabled').removeAttr('disabled');

    var input = $(this).serializeArray();

    // Again desabling the desabled elements
    disabled.attr('disabled', 'disabled');

    $(this).postForm(site_url("wards/save"), input, function () {
        loadWardOptions();

        var pageNo = '';
        var wrd_id = getInputValue(input, 'wrd_id');

        // If the action was Edit
        if (typeof wrd_id != 'undefined' && wrd_id > 0) {
            pageNo = $("#wrd_pagination").curPage();
        }

        // If the action was Add
        else
            pageNo = 0;

        showSuccessToast('Ward saved successfully');
        loadWards(pageNo);
    })
});

function afterWrdAddFormReset(frm) {
    frm.find('.dst_option').noOption();
    frm.find('.tlk_option').noOption();
    frm.find('.ars_option').noOption();
    frm.find('.wrd_option').noOption();
    frm.find('.estr_option').noOption();
}

function beforeEditWard(wrd_id) {
    var input = { wrd_id: wrd_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#wrd_add_modal').find('.modal-title').text('EDIT WARD');

    $('#wrd_add_modal').modal('show');

    $('form#wrd_add_form').postForm(site_url("wards/before_edit"), input, function (res, form) {

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#wrd_add_modal').find('.modal-title').text('EDIT WARD');
        form.find('.stt_option').val(res.stt_id);
        form.find('.dst_option').html(res.dst_option);
        form.find('.tlk_option').html(res.tlk_option);
        form.find('.ars_option').html(res.ars_option);
        form.find('.estr_option').html(res.estr_option);
    }, function (res, form) {
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}