$(document).ready(function () {


    $(document).on('click', '#add_stt', function () {

        // Initializing the modal with default values.
        $('#stt_add_modal #stt_add_form').initForm();

        $('#stt_add_modal').find('.modal-title').text('ADD STATE')

        $('#stt_add_modal').modal('show');
    });


    $('form#stt_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("states/save"), input, afterStateSave)
    });

});

function beforeEdit_stt(stt_id) {
    var input = { stt_id: stt_id };
    $('form#stt_add_form').postForm(site_url("states/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#stt_add_modal').modal('show');
    });
}
