$(document).ready(function () {

    $('#pgpl_add_modal #pgpl_add_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();
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

    $('#pgpl_add_modal #pgpl_add_form .dst_option').change(function () {
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.wrd_option').noOption();

        var t = $(this).closest('form').find('.ars_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('areas/get_options_by_district', {
            dst_id: d
        }, t, t.closest('div'));
    });

    $('#pgpl_add_modal #pgpl_add_form .ars_option').change(function () {
        $(this).closest('form').find('.wrd_option').noOption();
        var d = $(this).val();
        if (!d)
            return;

        t = $(this).closest('form').find('.wrd_option');
        loadOption('wards/get_options2', {
            ars_id: d
        }, t, t.closest('div'));
    });

    // Initializing the from with default values.
    $('form#pgpl_add_form').initForm();

    $(document).on('click', '#add_pgpl', function () {

        // If no task assigned
        if (!tsk_pgp_add)
            return;

        // Initializing the modal with default values.
        $('#pgpl_add_modal #pgpl_add_form').initForm();
        $('#pgpl_add_modal').modal('show');
    });

});




$('form#pgpl_add_form').on('submit', function (e) {

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

    $(this).postForm(site_url("price_group_locations/save"), input, function () {

        var pageNo = '';
        var pgp_id = getInputValue(input, 'pgpl_id');

        // If the action was Edit
        if (typeof pgpl_id != 'undefined' && pgpl_id > 0) {
            pageNo = $("#pgpl_pagination").curPage();
        }

        // If the action was Add
        else
            pageNo = 0;
        showSuccessToast('Locations added successfully');
        load_price_group_locations(0);
    })
});


function beforeEditPriceGroupLocations(pgpl_id) {
    var input = { pgpl_id: pgpl_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#pgpl_add_modal').find('.modal-title').text('EDIT LOCATION');

    $('#pgpl_add_modal').modal('show');

    $('form#pgpl_add_form').postForm(site_url("price_group_locations/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#pgpl_add_modal').find('.modal-title').text('EDIT LOCATION');
        form.find('.dst_option').html(res.dst_option);
        form.find('.ars_option').html(res.ars_option);
        form.find('.wrd_option').html(res.wrd_option);
    }, function (res, form) {
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}
