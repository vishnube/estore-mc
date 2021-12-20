$(document).ready(function () {


    $(document).on('click', '#add_tg', function () {

        // Initializing the modal with default values.
        $('#tg_add_modal #tg_add_form').initForm();

        $('#tg_add_modal').find('.modal-title').text('ADD TAG')

        $('#tg_add_modal').modal('show');
    });


    $('form#tg_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("tags/save"), input, afterTagSave)
    });

});

function beforeEdit_tg(tg_id) {
    var input = { tg_id: tg_id };
    $('form#tg_add_form').postForm(site_url("tags/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#tg_add_modal').modal('show');
    });
}
