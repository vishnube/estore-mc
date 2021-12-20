

$(document).on('click', '.deactivate_punt', function () {

    // If no task assigned
    if (!tsk_prd_deactivate)
        return;

    var $punt_id = $(this).closest('.unt-dv').find('.punt_id').val();
    var $punt_name = "The Unit";
    var url = site_url("product_units/deactivate");
    changeStatus(url, { punt_id: $punt_id }, $punt_name, INACTIVE, afterProductUnitsSave);
});

$(document).on('click', '.activate_punt', function () {

    // If no task assigned
    if (!tsk_prd_activate)
        return;

    var $punt_id = $(this).attr('data-punt_id');
    var $punt_name = "The Unit";
    var url = site_url("product_units/activate");
    changeStatus(url, { punt_id: $punt_id }, $punt_name, ACTIVE, function (res, form, input) {
        afterProductUnitsSave(res, form, input);
        $('#punt_add_modal').modal('hide');
    });
});


function afterProductUnitsSave(res, form, input) {
    pageNo = $("#prd_pagination").curPage();
    showSuccessToast('Unit saved successfully');
    loadProducts(pageNo);
}