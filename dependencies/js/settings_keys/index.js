/**
 * This function should be in global scope.
 * Don't put this function in "settings_key/add.js".
 * Because we will use the "SETTINGS KEY ADD" form and "settings_key/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterSettingsKeysSave(res, form, input) {
    $('form#stky_add_form .query').copyQuery(res.query); // Showing query and copying to clip board.

    loadSettingsKeysOptions();

    // $('#stky_add_modal').modal('hide'); // The modal shouldn't be hide, because the save query need to note for DB purposes.

    showSuccessToast('Settings key saved successfully');
    loadSettingsKeys();
}

/**
 * This function should be in global scope.
 * Don't put this function in "settings_key/add.js".
 */
$(document).on('click', '#tbl_stky .edit_stky', function () {
    var $stky_id = $(this).closest('tr').find('.stky_id').val();
    // activateTab('add');
    beforeEdit_stky($stky_id);
});



$(document).on('click', '#tbl_stky .deactivate_stky', function () {
    var $stky_id = $(this).closest('tr').find('.stky_id').val();
    var $stky_name = $(this).closest('tr').find('.stky_name').val();
    var url = site_url("settings_keys/deactivate");
    changeStatus(url, { stky_id: $stky_id }, $stky_name, INACTIVE, afterChangeStatus);
});

$(document).on('click', '#tbl_stky .activate_stky', function () {
    var $stky_id = $(this).closest('tr').find('.stky_id').val();
    var $stky_name = $(this).closest('tr').find('.stky_name').val();
    var url = site_url("settings_keys/activate");
    changeStatus(url, { stky_id: $stky_id }, $stky_name, ACTIVE, afterChangeStatus);
});

function afterChangeStatus() {
    loadSettingsKeys();
    loadSettingsKeysOptions();
}
