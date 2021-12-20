/**
 * This function should be in global scope.
 * Don't put this function in "price_group/add.js".
 */
$(document).on('click', '#tbl_pgp .edit_pgp', function () {

    // If no task assigned
    if (!tsk_pgp_edit)
        return;

    var $pgp_id = $(this).closest('tr').find('.pgp_id').val();
    beforeEditPriceGroup($pgp_id);
});

$(document).on('click', '#tbl_pgp .deactivate_pgp', function () {

    // If no task assigned
    if (!tsk_pgp_deactivate)
        return;

    var $pgp_id = $(this).closest('tr').find('.pgp_id').val();
    var $pgp_name = $(this).closest('tr').find('.pgp_name').val();
    var url = site_url("price_groups/deactivate");
    changeStatus(url, { pgp_id: $pgp_id }, $pgp_name, INACTIVE, function () {
        var pageNo = $("#pgp_pagination").curPage();
        load_price_groups(pageNo);
        load_price_group_options();
    });
});

$(document).on('click', '#tbl_pgp .activate_pgp', function () {

    // If no task assigned
    if (!tsk_pgp_activate)
        return;

    var $pgp_id = $(this).closest('tr').find('.pgp_id').val();
    var $pgp_name = $(this).closest('tr').find('.pgp_name').val();
    var url = site_url("price_groups/activate");
    changeStatus(url, { pgp_id: $pgp_id }, $pgp_name, ACTIVE, function () {
        var pageNo = $("#pgp_pagination").curPage();
        load_price_groups(pageNo);
        load_price_group_options();
    });
});
