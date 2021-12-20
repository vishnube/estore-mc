/**
 * This function should be in global scope.
 * Don't put this function in "tag/add.js".
 * Because we will use the "TAG ADD" form and "tag/add.js" in several places of this applitgion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterTagSave(res, form, input) {
    loadTagOptions();
    showSuccessToast('Tag saved successfully');
    loadTags();
}

/**
 * This function should be in global scope.
 * Don't put this function in "tag/add.js".
 */
$(document).on('click', '#tbl_tg .edit_tg', function () {
    var $tg_id = $(this).closest('tr').find('.tg_id').val();
    beforeEdit_tg($tg_id);
});



$(document).on('click', '#tbl_tg .deactivate_tg', function () {
    var $tg_id = $(this).closest('tr').find('.tg_id').val();
    var $tg_name = $(this).closest('tr').find('.tg_name').val();
    var url = site_url("tags/deactivate");
    changeStatus(url, { tg_id: $tg_id }, $tg_name, INACTIVE, afterChangeTagStatus);
});

$(document).on('click', '#tbl_tg .activate_tg', function () {
    var $tg_id = $(this).closest('tr').find('.tg_id').val();
    var $tg_name = $(this).closest('tr').find('.tg_name').val();
    var url = site_url("tags/activate");
    changeStatus(url, { tg_id: $tg_id }, $tg_name, ACTIVE, afterChangeTagStatus);
});

function afterChangeTagStatus() {
    loadTags();
    loadTagOptions();
}


