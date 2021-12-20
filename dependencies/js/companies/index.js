/**
 * This function should be in global scope.
 * Don't put this function in "company/add.js".
 * Because we will use the "COMPANY ADD" form and "company/add.js" in several places of this applicmpion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterCompaniesave(res, form, input) {
    loadCompanyOptions();
    showSuccessToast('Company saved successfully');
    loadCompanies();
}

/**
 * This function should be in global scope.
 * Don't put this function in "company/add.js".
 */
$(document).on('click', '#tbl_cmp .edit_cmp', function () {
    var $cmp_id = $(this).closest('tr').find('.cmp_id').val();
    beforeEdit_cmp($cmp_id);
});



$(document).on('click', '#tbl_cmp .deactivate_cmp', function () {
    var $cmp_id = $(this).closest('tr').find('.cmp_id').val();
    var $cmp_name = $(this).closest('tr').find('.cmp_name').val();
    var url = site_url("companies/deactivate");
    changeStatus(url, { cmp_id: $cmp_id }, $cmp_name, INACTIVE, afterChangeCompanyStatus);
});

$(document).on('click', '#tbl_cmp .activate_cmp', function () {
    var $cmp_id = $(this).closest('tr').find('.cmp_id').val();
    var $cmp_name = $(this).closest('tr').find('.cmp_name').val();
    var url = site_url("companies/activate");
    changeStatus(url, { cmp_id: $cmp_id }, $cmp_name, ACTIVE, afterChangeCompanyStatus);
});

function afterChangeCompanyStatus() {
    loadCompanies();
    loadCompanyOptions();
}


