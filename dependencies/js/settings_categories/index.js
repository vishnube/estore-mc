/**
 * This function should be in global scope.
 * Don't put this function in "settings_category/add.js".
 * Because we will use the "SETTINGS CATEGORY ADD" form and "settings_category/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterSettingsCategorySave(res, form, input) {
    $('form#stct_add_form .query').copyQuery(res.query); // Showing query and copying to clip board.

    loadSettingsCategoryOptions();

    // $('#stct_add_modal').modal('hide'); // The modal shouldn't be hide, because the save query need to note for DB purposes.

    showSuccessToast('Settings category saved successfully');
    loadSettingsCategories();
}

/**
 * This function should be in global scope.
 * Don't put this function in "settings_category/add.js".
 */
$(document).on('click', '#tbl_stct .edit_stct', function () {
    var $stct_id = $(this).closest('tr').find('.stct_id').val();
    // activateTab('add');
    beforeEdit_stct($stct_id);
});



$(document).on('click', '#tbl_stct .deactivate_stct', function () {
    var $stct_id = $(this).closest('tr').find('.stct_id').val();
    var $stct_name = $(this).closest('tr').find('.stct_name').val();
    var url = site_url("settings_categories/deactivate");
    changeStatus(url, { stct_id: $stct_id }, $stct_name, INACTIVE, afterChangeStatus);
});

$(document).on('click', '#tbl_stct .activate_stct', function () {
    var $stct_id = $(this).closest('tr').find('.stct_id').val();
    var $stct_name = $(this).closest('tr').find('.stct_name').val();
    var url = site_url("settings_categories/activate");
    changeStatus(url, { stct_id: $stct_id }, $stct_name, ACTIVE, afterChangeStatus);
});

function afterChangeStatus() {
    loadSettingsCategories();
    loadSettingsCategoryOptions();
}
