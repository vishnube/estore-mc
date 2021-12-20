$(document).ready(function () {


    $(document).on('click', '#add_hsn', function () {

        // Initializing the modal with default values.
        $('#hsn_add_modal #hsn_add_form').initForm();

        $('#hsn_add_modal').find('.modal-title').text('ADD HSN_DETAIL')

        $('#hsn_add_modal').modal('show');
    });


    $('form#hsn_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("hsn_details/save"), input, afterHsnDetailSave)
    });

});

function beforeEditHsn(hsn_id) {
    var input = { hsn_id: hsn_id };
    $('form#hsn_add_form').postForm(site_url("hsn_details/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#hsn_add_modal').modal('show');
    });
}
