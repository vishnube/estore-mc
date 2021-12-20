/**
 * This function should be in global scope.
 * Don't put this function in "hsn_detail/add.js".
 * Because we will use the "HSN_DETAIL ADD" form and "hsn_detail/add.js" in several places of this applihsnion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterHsnDetailSave(res, form, input) {
    var pageNo = '';
    var hsn_id = getInputValue(input, 'hsn_id');

    // If the action was Edit
    if (typeof hsn_id != 'undefined' && hsn_id > 0) {
        pageNo = $("#hsn_pagination").curPage();
        // activateTab('config');
    }

    // If the action was Add
    else
        pageNo = 0;


    loadHsnDetailOptions();
    showSuccessToast('Hsn_detail saved successfully');
    loadHsnDetails(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "hsn_detail/add.js".
 */
$(document).on('click', '#tbl_hsn .edit_hsn', function () {
    var $hsn_id = $(this).closest('tr').find('.hsn_id').val();
    beforeEditHsn($hsn_id);
});



$(document).on('click', '#tbl_hsn .deactivate_hsn', function () {
    var $hsn_id = $(this).closest('tr').find('.hsn_id').val();
    var $hsn_name = $(this).closest('tr').find('.hsn_name').val();
    var url = site_url("hsn_details/deactivate");
    changeStatus(url, { hsn_id: $hsn_id }, $hsn_name, INACTIVE, afterChangeHsn_detailStatus);
});

$(document).on('click', '#tbl_hsn .activate_hsn', function () {
    var $hsn_id = $(this).closest('tr').find('.hsn_id').val();
    var $hsn_name = $(this).closest('tr').find('.hsn_name').val();
    var url = site_url("hsn_details/activate");
    changeStatus(url, { hsn_id: $hsn_id }, $hsn_name, ACTIVE, afterChangeHsn_detailStatus);
});

function afterChangeHsn_detailStatus() {
    loadHsnDetails();
    loadHsnDetailOptions();
}


