/**
 * This function should be in global scope.
 * Don't put this function in "area/add.js".
 */
$(document).on('click', '#tbl_ars .edit_ars', function () {

    // If no task assigned
    if (!tsk_wrd_edit)
        return;

    var $ars_id = $(this).closest('tr').find('.ars_id').val();
    beforeEditArea($ars_id);
});

$(document).on('click', '#tbl_ars .deactivate_ars', function () {

    // If no task assigned
    if (!tsk_wrd_deactivate)
        return;

    var $ars_id = $(this).closest('tr').find('.ars_id').val();
    var $ars_name = $(this).closest('tr').find('.ars_name').val();
    var url = site_url("areas/deactivate");
    changeStatus(url, { ars_id: $ars_id }, $ars_name, INACTIVE, function () {
        var pageNo = $("#ars_pagination").curPage();
        loadAreas(pageNo);
        loadWards(0)
    });
});

$(document).on('click', '#tbl_ars .activate_ars', function () {

    // If no task assigned
    if (!tsk_wrd_activate)
        return;

    var $ars_id = $(this).closest('tr').find('.ars_id').val();
    var $ars_name = $(this).closest('tr').find('.ars_name').val();
    var url = site_url("areas/activate");
    changeStatus(url, { ars_id: $ars_id }, $ars_name, ACTIVE, function () {
        var pageNo = $("#ars_pagination").curPage();
        loadAreas(pageNo);
        loadWards(0)
    });
});
