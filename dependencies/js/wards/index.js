/**
 * This function should be in global scope.
 * Don't put this function in "ward/add.js".
 */
$(document).on('click', '#tbl_wrd .edit_wrd', function () {

    // If no task assigned
    if (!tsk_wrd_edit)
        return;

    var $wrd_id = $(this).closest('tr').find('.wrd_id').val();
    beforeEditWard($wrd_id);
});

$(document).on('click', '#tbl_wrd .deactivate_wrd', function () {

    // If no task assigned
    if (!tsk_wrd_deactivate)
        return;

    var $wrd_id = $(this).closest('tr').find('.wrd_id').val();
    var $wrd_name = $(this).closest('tr').find('.wrd_name').val();
    var url = site_url("wards/deactivate");
    changeStatus(url, { wrd_id: $wrd_id }, $wrd_name, INACTIVE, function () {
        var pageNo = $("#wrd_pagination").curPage();
        loadWards(pageNo);
    });
});

$(document).on('click', '#tbl_wrd .activate_wrd', function () {

    // If no task assigned
    if (!tsk_wrd_activate)
        return;

    var $wrd_id = $(this).closest('tr').find('.wrd_id').val();
    var $wrd_name = $(this).closest('tr').find('.wrd_name').val();
    var url = site_url("wards/activate");
    changeStatus(url, { wrd_id: $wrd_id }, $wrd_name, ACTIVE, function () {
        var pageNo = $("#wrd_pagination").curPage();
        loadWards(pageNo);
    });
});
