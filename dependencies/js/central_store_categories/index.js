/**
 * This function should be in global scope.
 * Don't put this function in "category/add.js".
 * Because we will use the "CATEGORY ADD" form and "category/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterCentralStoreCategorySave(res, form, input) {
    loadCentralStoreCategoryOptions();
    $('#cstr_cat_add_modal').modal('hide')
    showSuccessToast('Category saved successfully');
    loadCentralStoreCategories();
}

/**
 * This function should be in global scope.
 * Don't put this function in "category/add.js".
 */
$(document).on('click', '#tbl_cstr_cat .edit_cstr_cat', function () {
    var $cat_id = $(this).closest('tr').find('.cat_id').val();
    // activateTab('add');
    beforeEditCentralStoreCategory($cat_id);
});

$(document).on('click', '#tbl_cstr_cat .deactivate_cstr_cat', function () {
    var $cat_id = $(this).closest('tr').find('.cat_id').val();
    var $cat_name = $(this).closest('tr').find('.cat_name').val();
    var url = site_url("categories/deactivate");
    changeStatus(url, { cat_id: $cat_id }, $cat_name, INACTIVE, aftercentralStoreCategoryStatusChange);
});

$(document).on('click', '#tbl_cstr_cat .activate_cstr_cat', function () {
    var $cat_id = $(this).closest('tr').find('.cat_id').val();
    var $cat_name = $(this).closest('tr').find('.cat_name').val();
    var url = site_url("categories/activate");
    changeStatus(url, { cat_id: $cat_id }, $cat_name, ACTIVE, aftercentralStoreCategoryStatusChange);
});

function aftercentralStoreCategoryStatusChange() {
    loadCentralStoreCategories();
    loadCentralStoreCategoryOptions();
}


