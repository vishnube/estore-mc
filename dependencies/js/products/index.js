/**
 * This function should be in global scope.
 * Don't put this function in "product/add.js".
 * Because we will use the "PRODUCT ADD" form and "product/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterProductSave(res, form, input) {

    loadProductOptions();

    var pageNo = '';
    var prd_id = input.get('prd_id'); // Here input is FormData() obj

    // If the action was Edit
    if (typeof prd_id != 'undefined' && prd_id > 0) {
        pageNo = $("#prd_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Product saved successfully');
    loadProducts(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "product/add.js".
 */
$(document).on('click', '#tbl_prd .edit_prd', function () {

    // If no task assigned
    if (!tsk_prd_edit)
        return;

    var $prd_id = $(this).closest('tr').find('.prd_id').val();
    activateTab('add');
    beforeEditProduct($prd_id);
});

$(document).on('click', '#tbl_prd .deactivate_prd', function () {

    // If no task assigned
    if (!tsk_prd_deactivate)
        return;

    var $prd_id = $(this).closest('tr').find('.prd_id').val();
    var $prd_name = $(this).closest('tr').find('.prd_name').val();
    var url = site_url("products/deactivate");
    changeStatus(url, { prd_id: $prd_id }, $prd_name, INACTIVE, function () {
        var pageNo = $("#prd_pagination").curPage();
        loadProducts(pageNo);
    });
});

$(document).on('click', '#tbl_prd .activate_prd', function () {

    // If no task assigned
    if (!tsk_prd_activate)
        return;

    var $prd_id = $(this).closest('tr').find('.prd_id').val();
    var $prd_name = $(this).closest('tr').find('.prd_name').val();
    var url = site_url("products/activate");
    changeStatus(url, { prd_id: $prd_id }, $prd_name, ACTIVE, function () {
        var pageNo = $("#prd_pagination").curPage();
        loadProducts(pageNo);
    });
});
