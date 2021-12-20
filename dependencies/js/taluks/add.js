$(document).ready(function () {
    $('#tlk_add_modal #tlk_add_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });



    $(document).on('click', '#add_tlk', function () {

        // Initializing the modal with default values.
        $('#tlk_add_modal #tlk_add_form').initForm();

        $('#tlk_add_modal').find('.modal-title').text('ADD TALUK')

        $('#tlk_add_modal').modal('show');
    });


    $('form#tlk_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("taluks/save"), input, afterTalukSave)
    });

});

function beforeEdit_tlk(tlk_id) {
    var input = { tlk_id: tlk_id };
    $('form#tlk_add_form').postForm(site_url("taluks/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        form.find('.dst_option').html(res.dst_option);
        $('#tlk_add_modal').modal('show');
    });
}
