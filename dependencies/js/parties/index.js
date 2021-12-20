/**
 * This function should be in global scope.
 * Don't put this function in "party/add.js".
 * Because we will use the "PARTY ADD" form and "party/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterPartySave(res, form, input) {

    loadPartyOptions();

    var pageNo = '';
    var mbr_id = getInputValue(input, 'mbr_id');

    // If the action was Edit
    if (typeof mbr_id != 'undefined' && mbr_id > 0) {
        pageNo = $("#prty_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Party saved successfully');
    loadParties(pageNo);
}



/**
 * This function shoud not be in any file under 'gstnumbers' folder
 * Because, it will be called from employees/add.js, parties/add.js, vehicles/add.js, ect and will be different for each
 * So put this function in employees/index.js (In case of Employees), parties/index.js (In case of Parties) ect.
 * @param {*} res 
 * @param {*} form 
 * @param {*} input 
 */
function afterGstSave(res, form, input) {
    $('#gst_add_modal').modal('hide');
    var gst_id = getInputValue(input, 'gst_id');

    showSuccessToast('GST details saved successfully');

    // If the action was Edit
    if (typeof gst_id != 'undefined' && gst_id > 0) {
        mbr_id = getInputValue(input, 'gst_fk_members');
        show_gst_details(mbr_id);
    }

    var pageNo = $("#prty_pagination").curPage();
    loadParties(pageNo);
}
/**
 * This function shoud not be in any file under 'gstnumbers' folder
 * Because, it will be called from employees/add.js, parties/add.js, vehicles/add.js, ect and its content may be different for each
 * So put this function in employees/index.js (In case of Employees), parties/index.js (In case of Parties) ect.
*/
function afterGstStatusChanged(mbr_id) {
    show_gst_details(mbr_id);
    var pageNo = $("#prty_pagination").curPage();
    loadParties(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "party/add.js".
 */
$(document).on('click', '#tbl_prty .edit_mbr', function () {

    // If no task assigned
    if (!tsk_prty_edit)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    activateTab('add');
    beforeEditParty($mbr_id);
});

/**
 * This function should be in global scope.
 * Don't put this function in "party/add.js".
 */
$(document).on('click', '#party_details_modal .edit_mbr', function () {

    // If no task assigned
    if (!tsk_prty_edit)
        return;

    $('#party_details_modal').modal('hide');

    var $mbr_id = $(this).closest('.modal').find('.mbr_id').val();
    activateTab('add');
    beforeEditParty($mbr_id);
});

$(document).on('click', '#tbl_prty .deactivate_mbr', function () {

    // If no task assigned
    if (!tsk_prty_deactivate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("parties/deactivate");
    changeStatus(url, { mbr_id: $mbr_id }, $mbr_name, INACTIVE, function () {
        var pageNo = $("#prty_pagination").curPage();
        loadParties(pageNo);
    });
});

$(document).on('click', '#tbl_prty .activate_mbr', function () {

    // If no task assigned
    if (!tsk_prty_activate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("parties/activate");
    changeStatus(url, { mbr_id: $mbr_id }, $mbr_name, ACTIVE, function () {
        var pageNo = $("#prty_pagination").curPage();
        loadParties(pageNo);
    });
});
