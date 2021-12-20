/**
 * This function should be in global scope.
 * Don't put this function in "bill_batch/add.js".
 * Because we will use the "BILL_BATCH ADD" form and "bill_batch/add.js" in several places of this appliblbion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterBillBatchsave(res, form, input) {
    // loadBillBatchOptions(); // Bill_batch option will be loaded only after State Option changed.
    var pageNo = '';
    var blb_id = getInputValue(input, 'blb_id');

    // If the action was Edit
    if (typeof blb_id != 'undefined' && blb_id > 0) {
        pageNo = $("#blb_pagination").curPage();
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Bill batch saved successfully');
    loadBillBatches(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "bill_batch/add.js".
 */
$(document).on('click', '#tbl_blb .edit_blb', function () {
    var $blb_id = $(this).closest('tr').find('.blb_id').val();
    beforeEdit_blb($blb_id);
});



$(document).on('click', '#tbl_blb .deactivate_blb', function () {
    Swal.fire('Deactivation will be done automatically when you ADD/ACTIVATE another one');
});

$(document).on('click', '#tbl_blb .activate_blb', function () {
    var $blb_id = $(this).closest('tr').find('.blb_id').val();
    var $blb_name = $(this).closest('tr').find('.blb_name').val();
    var url = site_url("bill_batches/activate");
    var bill_type = $('.bill_type[name=bill_type]:checked').val();
    changeStatus(url, { blb_id: $blb_id, bill_type: bill_type }, $blb_name, ACTIVE, afterChangeBillBatchStatus);
});

function afterChangeBillBatchStatus() {
    loadBillBatches();
    //loadBillBatchOptions();
}


