/**
 * This function should be in global scope.
 * Don't put this function in "family/add.js".
 * Because we will use the "FAMILY ADD" form and "family/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterFamilySave(res, form, input) {

    loadFamilyOptions();

    var pageNo = '';
    var mbr_id = getInputValue(input, 'mbr_id');

    // If the action was Edit
    if (typeof mbr_id != 'undefined' && mbr_id > 0) {
        pageNo = $("#fmly_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Family saved successfully');
    loadFamilies(pageNo);
}

function afterfmlySearchFormReset() {
    $('#fmly_search_form .dst_option').noOption();
    $('#fmly_search_form .tlk_option').noOption();
    $('#fmly_search_form .ars_option').noOption();
    $('#fmly_search_form .wrd_option').noOption();
}


/**
 * This function should be in global scope.
 * Don't put this function in "family/add.js".
 */
$(document).on('click', '#tbl_fmly .edit_mbr', function () {

    // If no task assigned
    if (!tsk_fmly_edit)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    activateTab('add');
    beforeEditFamily($mbr_id);
});

$(document).on('click', '#tbl_fmly .deactivate_mbr', function () {

    // If no task assigned
    if (!tsk_fmly_deactivate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("families/deactivate");
    changeStatus(url, { mbr_id: $mbr_id }, $mbr_name, INACTIVE, function () {
        var pageNo = $("#fmly_pagination").curPage();
        loadFamilies(pageNo);
    });
});

$(document).on('click', '#tbl_fmly .activate_mbr', function () {

    // If no task assigned
    if (!tsk_fmly_activate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("families/activate");
    changeStatus(url, { mbr_id: $mbr_id }, $mbr_name, ACTIVE, function () {
        var pageNo = $("#fmly_pagination").curPage();
        loadFamilies(pageNo);
    });
});
