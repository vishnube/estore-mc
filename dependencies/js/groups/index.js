/**
 * This function should be in global scope.
 * Don't put this function in "group/add.js".
 * Because we will use the "group ADD" form and "group/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function aftergroupSave(res, form, input) {
    loadgroupOptions();
    $('#grp_add_modal').modal('hide');
    showSuccessToast('Group saved successfully');
    loadgroups();
}

/**
 * This function should be in global scope.
 * Don't put this function in "group/add.js".
 */
$(document).on('click', '#tbl_grp .edit_grp', function () {
    var $grp_id = $(this).closest('tr').find('.grp_id').val();
    // activateTab('add');
    beforeEdit_grp($grp_id);
});



$(document).on('click', '#tbl_grp .deactivate_grp', function () {
    var $grp_id = $(this).closest('tr').find('.grp_id').val();
    var $grp_name = $(this).closest('tr').find('.grp_name').val();
    var url = site_url("groups/deactivate");
    changeStatus(url, { grp_id: $grp_id }, $grp_name, INACTIVE, afterChangeStatus);
});

$(document).on('click', '#tbl_grp .activate_grp', function () {
    var $grp_id = $(this).closest('tr').find('.grp_id').val();
    var $grp_name = $(this).closest('tr').find('.grp_name').val();
    var url = site_url("groups/activate");
    changeStatus(url, { grp_id: $grp_id }, $grp_name, ACTIVE, afterChangeStatus);
});

function afterChangeStatus() {
    loadgroups();
    loadgroupOptions();
}

