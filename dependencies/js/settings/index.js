/**
 * This function should be in global scope.
 * Don't put this function in "settings/add.js".
 * Because we will use the "SETTINGS ADD" form and "settings/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterSettingsave(res, form, input) {
    $('form#st_add_form .query').copyQuery(res.query); // Showing query and copying to clip board.

    loadSettingsOptions();

    var pageNo = '';
    var st_id = getInputValue(input, 'st_id');

    // If the action was Edit
    if (typeof st_id != 'undefined' && st_id > 0) {
        pageNo = $("#st_pagination").curPage();

        // We need to note update query after edit. It will be shown below the submit button in edit window.
        // So manually move to the "list" tab after note the query.
        // activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Settings saved successfully');
    loadSettings(pageNo);
}



/**
 * This function should be in global scope.
 * Don't put this function in "settings/add.js".
 */
$(document).on('click', '#tbl_st .edit_st', function () {
    var $st_id = $(this).closest('tr').find('.st_id').val();
    activateTab('add');
    beforeEditSettings($st_id);
});

$(document).on('click', '#tbl_st .deactivate_st', function () {
    var $st_id = $(this).closest('tr').find('.st_id').val();
    var $st_name = $(this).closest('tr').find('.st_name').val();
    var url = site_url("settings/deactivate");
    changeStatus(url, { st_id: $st_id }, $st_name, INACTIVE, function () {
        var pageNo = $("#st_pagination").curPage();
        loadSettings(pageNo);
    });
});

$(document).on('click', '#tbl_st .activate_st', function () {
    var $st_id = $(this).closest('tr').find('.st_id').val();
    var $st_name = $(this).closest('tr').find('.st_name').val();
    var url = site_url("settings/activate");
    changeStatus(url, { st_id: $st_id }, $st_name, ACTIVE, function () {
        var pageNo = $("#st_pagination").curPage();
        loadSettings(pageNo);
    });
});
