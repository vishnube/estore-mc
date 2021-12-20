/**
 * This function should be in global scope.
 * Don't put this function in "category/add.js".
 * Because we will use the "CATEGORY ADD" form and "category/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterPartyCategorySave(res, form, input) {
    loadPartyCategoryOptions();
    $('#prty_cat_add_modal').modal('hide')
    showSuccessToast('Category saved successfully');
    loadPartyCategories();
}

/**
 * This function should be in global scope.
 * Don't put this function in "category/add.js".
 */
$(document).on('click', '#tbl_prty_cat .edit_prty_cat', function () {
    var $cat_id = $(this).closest('tr').find('.cat_id').val();
    // activateTab('add');
    beforeEditPartyCategory($cat_id);
});



$(document).on('click', '#tbl_prty_cat .deactivate_prty_cat', function () {
    var $cat_id = $(this).closest('tr').find('.cat_id').val();
    var $cat_name = $(this).closest('tr').find('.cat_name').val();
    var url = site_url("categories/deactivate");
    changeStatus(url, { cat_id: $cat_id }, $cat_name, INACTIVE, afterPartyCategoryStatusChange);
});

$(document).on('click', '#tbl_prty_cat .activate_prty_cat', function () {
    var $cat_id = $(this).closest('tr').find('.cat_id').val();
    var $cat_name = $(this).closest('tr').find('.cat_name').val();
    var url = site_url("categories/activate");
    changeStatus(url, { cat_id: $cat_id }, $cat_name, ACTIVE, afterPartyCategoryStatusChange);
});

function afterPartyCategoryStatusChange() {
    loadPartyCategories();
    loadPartyCategoryOptions();
}


