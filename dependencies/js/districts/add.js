$(document).ready(function () {


    $(document).on('click', '#add_dst', function () {

        // Initializing the modal with default values.
        $('#dst_add_modal #dst_add_form').initForm();

        $('#dst_add_modal').find('.modal-title').text('ADD DISTRICT')

        $('#dst_add_modal').modal('show');
    });


    $('form#dst_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("districts/save"), input, afterDistrictSave)
    });

});

function beforeEdit_dst(dst_id) {
    var input = { dst_id: dst_id };
    $('form#dst_add_form').postForm(site_url("districts/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#dst_add_modal').modal('show');
    });
}
