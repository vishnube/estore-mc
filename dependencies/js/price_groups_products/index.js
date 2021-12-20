/**
 * This function should be in global scope.
 * Don't put this function in "price_group/add.js".
 */

$(document).on('click', '#tbl_pgprd .delete_pgprd', function () {

    // If no task assigned
    if (!tsk_pgp_deactivate)
        return;

    var $pgprd_id = $(this).closest('tr').find('.pgprd_id').val();
    var $pgp_name = $(this).closest('tr').find('.pgp_name').val();
    var $prd_name = $(this).closest('tr').find('.prd_name').val();
    var url = site_url("price_groups_products/delete");

    Swal.fire({
        title: 'Are you sure?',
        html: "This will delete <b>" + $prd_name + '</b> from <b>' + $pgp_name + '</b>',
        icon: 'warning',
        iconHtml: '<i class="fas fa-trash-alt"></i>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
        if (result.value) {
            $.post(url, { pgprd_id: $pgprd_id }, function (r) {
                if (r.status == 1) {
                    Swal.fire(
                        'Deleted!',
                        '<b>' + $prd_name + '</b> has been deleted from <b>' + $pgp_name + '</b>',
                        'success'
                    );

                    showSuccessToast('Products deleted successfully');
                    var pageNo = $("#pgprd_pagination").curPage();
                    load_price_groups_products(pageNo);
                    loadPriceGroupAddedProducts(0); // price_groups_products\add_2.js
                }
                else {
                    var msg = typeof r.o_error == 'undefined' ? 'Couldn\'t ' + 'delete' : r.o_error;
                    Swal.fire('Oops!', msg, 'error');
                }
            }, 'json');
        }
    });

});


