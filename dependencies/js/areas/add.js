$(document).ready(function () {
    $('#ars_add_modal #ars_add_form .stt_option').change(function () {
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

    $('#ars_add_modal #ars_add_form .dst_option').change(function () {
        $(this).closest('form').find('.tlk_option').noOption();

        var t = $(this).closest('form').find('.tlk_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('taluks/get_options', {
            tlk_fk_districts: d
        }, t, t.closest('div'));
    });


    // Initializing the from with default values.
    $('form#ars_add_form').initForm();

    $(document).on('click', '#add_ars', function () {

        // If no task assigned
        if (!tsk_wrd_add)
            return;

        // Initializing the modal with default values.
        $('#ars_add_modal #ars_add_form').initForm();

        $('#ars_add_modal').find('.modal-title').text('ADD AREA')

        $('#ars_add_modal').modal('show');
    });

});




$('form#ars_add_form').on('submit', function (e) {

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

    // adding an additional inputs
    // var mbrtp_id @ employees/index.php
    //input.push({ name: "ars_fk_member_types", value: mbrtp_id });

    $(this).postForm(site_url("areas/save"), input, function () {
        loadAreaOptions();

        var pageNo = '';
        var ars_id = getInputValue(input, 'ars_id');

        // If the action was Edit
        if (typeof ars_id != 'undefined' && ars_id > 0) {
            pageNo = $("#ars_pagination").curPage();
        }

        // If the action was Add
        else
            pageNo = 0;

        showSuccessToast('Area saved successfully');
        loadAreas(pageNo);
        loadWards(0);
    })
});

function afterArsAddFormReset(frm) {
    frm.find('.dst_option').noOption();
    frm.find('.tlk_option').noOption();
    frm.find('.ars_option').noOption();
    frm.find('.ars_option').noOption();
    frm.find('.estr_option').noOption();
}

function beforeEditArea(ars_id) {
    var input = { ars_id: ars_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#ars_add_modal').find('.modal-title').text('EDIT AREA');

    $('#ars_add_modal').modal('show');

    $('form#ars_add_form').postForm(site_url("areas/before_edit"), input, function (res, form) {

        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#ars_add_modal').find('.modal-title').text('EDIT AREA');
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