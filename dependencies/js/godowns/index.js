/**
 * This function should be in global scope.
 * Don't put this function in "godown/add.js".
 * Because we will use the "GODOWN ADD" form and "godown/add.js" in several places of this appligdnion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterGodownSave(res, form, input) {
    loadGodownOptions();
    showSuccessToast('Godown saved successfully');
    loadGodowns();
}

/**
 * This function should be in global scope.
 * Don't put this function in "godown/add.js".
 */
$(document).on('click', '#tbl_gdn .edit_gdn', function () {
    var $gdn_id = $(this).closest('tr').find('.gdn_id').val();
    beforeEdit_gdn($gdn_id);
});



$(document).on('click', '#tbl_gdn .deactivate_gdn', function () {
    var $gdn_id = $(this).closest('tr').find('.gdn_id').val();
    var $gdn_name = $(this).closest('tr').find('.gdn_name').val();
    var url = site_url("godowns/deactivate");
    changeStatus(url, { gdn_id: $gdn_id }, $gdn_name, INACTIVE, afterChangeGodownStatus);
});

$(document).on('click', '#tbl_gdn .activate_gdn', function () {
    var $gdn_id = $(this).closest('tr').find('.gdn_id').val();
    var $gdn_name = $(this).closest('tr').find('.gdn_name').val();
    var url = site_url("godowns/activate");
    changeStatus(url, { gdn_id: $gdn_id }, $gdn_name, ACTIVE, afterChangeGodownStatus);
});

function afterChangeGodownStatus() {
    loadGodowns();
    loadGodownOptions();
}


