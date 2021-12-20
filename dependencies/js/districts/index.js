/**
 * This function should be in global scope.
 * Don't put this function in "district/add.js".
 * Because we will use the "DISTRICT ADD" form and "district/add.js" in several places of this applidstion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterDistrictSave(res, form, input) {
    // loadDistrictOptions(); // District option will be loaded only after State Option changed.
    var pageNo = '';
    var dst_id = getInputValue(input, 'dst_id');

    // If the action was Edit
    if (typeof dst_id != 'undefined' && dst_id > 0) {
        pageNo = $("#dst_pagination").curPage();
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('District saved successfully');
    loadDistricts(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "district/add.js".
 */
$(document).on('click', '#tbl_dst .edit_dst', function () {
    var $dst_id = $(this).closest('tr').find('.dst_id').val();
    beforeEdit_dst($dst_id);
});



$(document).on('click', '#tbl_dst .deactivate_dst', function () {
    var $dst_id = $(this).closest('tr').find('.dst_id').val();
    var $dst_name = $(this).closest('tr').find('.dst_name').val();
    var url = site_url("districts/deactivate");
    changeStatus(url, { dst_id: $dst_id }, $dst_name, INACTIVE, afterChangeDistrictStatus);
});

$(document).on('click', '#tbl_dst .activate_dst', function () {
    var $dst_id = $(this).closest('tr').find('.dst_id').val();
    var $dst_name = $(this).closest('tr').find('.dst_name').val();
    var url = site_url("districts/activate");
    changeStatus(url, { dst_id: $dst_id }, $dst_name, ACTIVE, afterChangeDistrictStatus);
});

function afterChangeDistrictStatus() {
    loadDistricts();
    loadDistrictOptions();
}


