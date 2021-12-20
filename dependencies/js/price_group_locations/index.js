/**
 * This function should be in global scope.
 * Don't put this function in "price_group/add.js".
 */
$(document).on('click', '#tbl_pgpl .edit_pgpl', function () {

    // If no task assigned
    if (!tsk_pgp_edit)
        return;

    var $pgpl_id = $(this).closest('tr').find('.pgpl_id').val();
    beforeEditPriceGroupLocations($pgpl_id);
});

$(document).on('click', '#tbl_pgpl .delete_pgpl', function () {

    // If no task assigned
    if (!tsk_pgp_deactivate)
        return;

    var $pgpl_id = $(this).closest('tr').find('.pgpl_id').val();
    var $pgp_name = $(this).closest('tr').find('.pgp_name').val();
    var url = site_url("price_group_locations/delete");

    Swal.fire({
        title: 'Are you sure?',
        html: "This will delete this location from <b>" + $pgp_name + '</b>',
        icon: 'warning',
        iconHtml: '<i class="fas fa-trash-alt"></i>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
        if (result.value) {
            $.post(url, { pgpl_id: $pgpl_id }, function (r) {
                if (r.status == 1) {
                    Swal.fire(
                        'Deleted!',
                        'Location has been deleted from <b>' + $pgp_name + '</b>',
                        'success'
                    );

                    showSuccessToast('Location deleted successfully');
                    var pageNo = $("#pgpl_pagination").curPage();
                    load_price_group_locations(pageNo);
                }
                else {
                    var msg = typeof r.o_error == 'undefined' ? 'Couldn\'t ' + 'delete' : r.o_error;
                    Swal.fire('Oops!', msg, 'error');
                }
            }, 'json');
        }
    });

});


