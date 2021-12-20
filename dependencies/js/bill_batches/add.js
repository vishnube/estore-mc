$(document).ready(function () {


    $(document).on('click', '#add_blb', function () {

        // Initializing the modal with default values.
        $('#blb_add_modal #blb_add_form').initForm();

        $('#blb_add_modal').find('.modal-title').text('ADD BILL BATCH')

        $('#blb_add_modal').modal('show');
    });


    $('form#blb_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        var bill_type = $('.bill_type[name=bill_type]:checked').val();
        input.push({ name: "bill_type", value: bill_type })
        input.push({ name: "blb_for", value: bill_type })
        $(this).postForm(site_url("bill_batches/save"), input, afterBillBatchsave)
    });

});

function beforeEdit_blb(blb_id) {
    var bill_type = $('.bill_type[name=bill_type]:checked').val();
    var input = { blb_id: blb_id, bill_type: bill_type };
    $('form#blb_add_form').postForm(site_url("bill_batches/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#blb_add_modal').modal('show');
    });
}
