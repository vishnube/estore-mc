/**
 * This function should be in global scope.
 * Don't put this function in "state/add.js".
 * Because we will use the "STATE ADD" form and "state/add.js" in several places of this applisttion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterStateSave(res, form, input) {
    loadStateOptions();
    showSuccessToast('State saved successfully');
    loadStates();
}

/**
 * This function should be in global scope.
 * Don't put this function in "state/add.js".
 */
$(document).on('click', '#tbl_stt .edit_stt', function () {
    var $stt_id = $(this).closest('tr').find('.stt_id').val();
    beforeEdit_stt($stt_id);
});



$(document).on('click', '#tbl_stt .deactivate_stt', function () {
    var $stt_id = $(this).closest('tr').find('.stt_id').val();
    var $stt_name = $(this).closest('tr').find('.stt_name').val();
    var url = site_url("states/deactivate");
    changeStatus(url, { stt_id: $stt_id }, $stt_name, INACTIVE, afterChangeStateStatus);
});

$(document).on('click', '#tbl_stt .activate_stt', function () {
    var $stt_id = $(this).closest('tr').find('.stt_id').val();
    var $stt_name = $(this).closest('tr').find('.stt_name').val();
    var url = site_url("states/activate");
    changeStatus(url, { stt_id: $stt_id }, $stt_name, ACTIVE, afterChangeStateStatus);
});

function afterChangeStateStatus() {
    loadStates();
    loadStateOptions();
}


