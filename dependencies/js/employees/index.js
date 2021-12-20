/**
 * This function should be in global scope.
 * Don't put this function in "employee/add.js".
 * Because we will use the "EMPLOYEE ADD" form and "employee/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterEmployeeSave(res, form, input) {

    loadEmployeeOptions();

    var pageNo = '';
    var mbr_id = getInputValue(input, 'mbr_id');

    // If the action was Edit
    if (typeof mbr_id != 'undefined' && mbr_id > 0) {
        pageNo = $("#emply_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Employee saved successfully');
    loadEmployees(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "employee/add.js".
 */
$(document).on('click', '#tbl_emply .edit_mbr', function () {

    // If no task assigned
    if (!tsk_emply_edit)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    activateTab('add');
    beforeEditEmployee($mbr_id);
});

$(document).on('click', '#tbl_emply .deactivate_mbr', function () {

    // If no task assigned
    if (!tsk_emply_deactivate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("employees/deactivate");
    changeStatus(url, { mbr_id: $mbr_id }, $mbr_name, INACTIVE, function () {
        var pageNo = $("#emply_pagination").curPage();
        loadEmployees(pageNo);
    });
});

$(document).on('click', '#tbl_emply .activate_mbr', function () {

    // If no task assigned
    if (!tsk_emply_activate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("employees/activate");
    changeStatus(url, { mbr_id: $mbr_id }, $mbr_name, ACTIVE, function () {
        var pageNo = $("#emply_pagination").curPage();
        loadEmployees(pageNo);
    });
});
