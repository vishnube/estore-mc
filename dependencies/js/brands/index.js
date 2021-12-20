/**
 * This function should be in global scope.
 * Don't put this function in "brand/add.js".
 * Because we will use the "BRAND ADD" form and "brand/add.js" in several places of this applibrndion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterBrandsave(res, form, input) {
    loadBrandOptions();
    showSuccessToast('Brand saved successfully');
    loadBrands();
}

/**
 * This function should be in global scope.
 * Don't put this function in "brand/add.js".
 */
$(document).on('click', '#tbl_brnd .edit_brnd', function () {
    var $brnd_id = $(this).closest('tr').find('.brnd_id').val();
    beforeEdit_brnd($brnd_id);
});



$(document).on('click', '#tbl_brnd .deactivate_brnd', function () {
    var $brnd_id = $(this).closest('tr').find('.brnd_id').val();
    var $brnd_name = $(this).closest('tr').find('.brnd_name').val();
    var url = site_url("brands/deactivate");
    changeStatus(url, { brnd_id: $brnd_id }, $brnd_name, INACTIVE, afterChangeBrandStatus);
});

$(document).on('click', '#tbl_brnd .activate_brnd', function () {
    var $brnd_id = $(this).closest('tr').find('.brnd_id').val();
    var $brnd_name = $(this).closest('tr').find('.brnd_name').val();
    var url = site_url("brands/activate");
    changeStatus(url, { brnd_id: $brnd_id }, $brnd_name, ACTIVE, afterChangeBrandStatus);
});

function afterChangeBrandStatus() {
    loadBrands();
    loadBrandOptions();
}


