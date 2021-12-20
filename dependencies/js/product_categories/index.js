
function reset_pct(form) {
    var container = form.find('.parents');
    container.html('');
    var dropdown = form.find('.pct_option');
    loadOption('product_categories/get_options', { pct_parent: 0 }, dropdown);
}

function create_pct_parent_radios(obj, radName, label, pct_id, pct_name, not) {
    var not = ifDef(not, not, '');
    var pct_id = ifDef(pct_id, pct_id, obj.val());
    var pct_name = ifDef(pct_name, pct_name, obj.find('option:selected').text());
    var container = obj.closest('.parent-dv').find('.parents');

    label = label ? '<label class="sr-label">' + label + '</label><br>' : '';
    var i = container.html() ? '<i class="fal fa-chevron-double-right"></i>' : label;
    var radLabel = radName + '_' + pct_id;
    var radio = i + '<div class="icheck-danger d-inline m-1"><input type="radio" value="' + pct_id + '" name="' + radName + '" checked id="' + radLabel + '" class="pct_parent"><label for="' + radLabel + '">' + pct_name + '</label></div>';

    container.append(radio);
    loadOption('product_categories/get_options', { pct_parent: pct_id, not: not }, obj);
    obj.val('');
}

/**
 * This function should be in global scope.
 * Don't put this function in "category/add.js".
 * Because we will use the "CATEGORY ADD" form and "category/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterProductCategoriesSave(res, form, input) {
    reset_pct($('form')); // It should be before loading table, Option
    loadProductCategoriesOptions();
    $('#pct_add_modal').modal('hide')
    showSuccessToast('Category saved successfully');
    loadProductCategories();
}

/**
 * This function should be in global scope.
 * Don't put this function in "category/add.js".
 */
$(document).on('click', '#tbl_pct .edit_pct', function () {
    var $pct_id = $(this).closest('tr').find('.pct_id').val();
    beforeEditProductCategories($pct_id);
});



$(document).on('click', '#tbl_pct .deactivate_pct', function () {
    var $pct_id = $(this).closest('tr').find('.pct_id').val();
    var $pct_name = $(this).closest('tr').find('.pct_name').val();
    var url = site_url("product_categories/deactivate");
    changeStatus(url, { pct_id: $pct_id }, $pct_name, INACTIVE, afterProductCategoriesStatusChange);
});

$(document).on('click', '#tbl_pct .activate_pct', function () {
    var $pct_id = $(this).closest('tr').find('.pct_id').val();
    var $pct_name = $(this).closest('tr').find('.pct_name').val();
    var url = site_url("product_categories/activate");
    changeStatus(url, { pct_id: $pct_id }, $pct_name, ACTIVE, afterProductCategoriesStatusChange);
});

function afterProductCategoriesStatusChange() {
    reset_pct($('form')); // It should be before loading table, Option
    loadProductCategories();
    loadProductCategoriesOptions();
}


