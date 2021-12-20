/**
 * This function should be in global scope.
 * Don't put this function in "unit_group/add.js".
 * Because we will use the "UNIT GROUP ADD" form and "unit_group/add.js" in several places of this appliugpion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterUnitGroupSave(res, form, input) {
    showSuccessToast('Unit Group saved successfully');
    loadUnitGroups();
}

$(document).on('click', '#tbl_ugp .deactivate_ugp', function () {
    var $ugp_group_no = $(this).closest('tr').find('.ugp_group_no').val();
    var $ugp_name = "This Unit Group";
    var url = site_url("unit_groups/deactivate");
    changeStatus(url, { ugp_group_no: $ugp_group_no }, $ugp_name, INACTIVE, afterChangeUnitGroupStatus);
});

$(document).on('click', '#tbl_ugp .activate_ugp', function () {
    var $ugp_group_no = $(this).closest('tr').find('.ugp_group_no').val();
    var $ugp_name = "This Unit Group";
    var url = site_url("unit_groups/activate");
    changeStatus(url, { ugp_group_no: $ugp_group_no }, $ugp_name, ACTIVE, afterChangeUnitGroupStatus);
});

function afterChangeUnitGroupStatus() {
    loadUnitGroups();
}


