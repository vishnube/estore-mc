/**
 * This function should be in global scope.
 * Don't put this function in "estore/add.js".
 * Because we will use the "estore ADD" form and "estore/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterestoreSave(res, form, input) {

    loadestoreOptions();

    var pageNo = '';
    var mbr_id = getInputValue(input, 'mbr_id');

    // If the action was Edit
    if (typeof mbr_id != 'undefined' && mbr_id > 0) {
        pageNo = $("#estr_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Estore saved successfully');
    loadestores(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "estore/add.js".
 */
$(document).on('click', '#tbl_estr .edit_mbr', function () {

    // If no task assigned
    if (!tsk_estr_edit)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    activateTab('add');
    beforeEditestore($mbr_id);
});

$(document).on('click', '#tbl_estr .deactivate_mbr', function () {

    // If no task assigned
    if (!tsk_estr_deactivate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $estr_id = $(this).closest('tr').find('.estr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("estores/deactivate");
    changeStatus(url, { mbr_id: $mbr_id, estr_id: $estr_id }, $mbr_name, INACTIVE, function () {
        var pageNo = $("#estr_pagination").curPage();
        loadestores(pageNo);
    });
});

$(document).on('click', '#tbl_estr .activate_mbr', function () {

    // If no task assigned
    if (!tsk_estr_activate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $estr_id = $(this).closest('tr').find('.estr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("estores/activate");
    changeStatus(url, { mbr_id: $mbr_id, estr_id: $estr_id }, $mbr_name, ACTIVE, function () {
        var pageNo = $("#estr_pagination").curPage();
        loadestores(pageNo);
    });
});
