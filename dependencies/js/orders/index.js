/**
 * This function should be in global scope.
 * Don't put this function in "order/add.js".
 * Because we will use the "ORDER ADD" form and "order/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
// function afterOrderSave(res, form, input) {

//     loadOrderOptions();

//     var pageNo = '';
//     var odr_id = input.get('odr_id'); // Here input is FormData() obj

//     // If the action was Edit
//     if (typeof odr_id != 'undefined' && odr_id > 0) {
//         pageNo = $("#odr_pagination").curPage();
//         activateTab('list');
//     }

//     // If the action was Add
//     else
//         pageNo = 0;

//     showSuccessToast('Order saved successfully');
//     loadOrders(pageNo);
// }

/**
 * This function should be in global scope.
 * Don't put this function in "order/add.js".
 */
// $(document).on('click', '#tbl_odr .edit_odr', function () {

//     // If no task assigned
//     if (!tsk_odr_edit)
//         return;

//     var $odr_id = $(this).closest('tr').find('.odr_id').val();
//     activateTab('add');
//     beforeEditOrder($odr_id);
// });

// $(document).on('click', '#tbl_odr .deactivate_odr', function () {

//     // If no task assigned
//     if (!tsk_odr_deactivate)
//         return;

//     var $odr_id = $(this).closest('tr').find('.odr_id').val();
//     var $odr_name = $(this).closest('tr').find('.odr_name').val();
//     var url = site_url("orders/deactivate");
//     changeStatus(url, { odr_id: $odr_id }, $odr_name, INACTIVE, function () {
//         var pageNo = $("#odr_pagination").curPage();
//         loadOrders(pageNo);
//     });
// });

// $(document).on('click', '#tbl_odr .activate_odr', function () {

//     // If no task assigned
//     if (!tsk_odr_activate)
//         return;

//     var $odr_id = $(this).closest('tr').find('.odr_id').val();
//     var $odr_name = $(this).closest('tr').find('.odr_name').val();
//     var url = site_url("orders/activate");
//     changeStatus(url, { odr_id: $odr_id }, $odr_name, ACTIVE, function () {
//         var pageNo = $("#odr_pagination").curPage();
//         loadOrders(pageNo);
//     });
// });
