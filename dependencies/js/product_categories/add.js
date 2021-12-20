$(document).ready(function () {


    $(document).on('click', '#add_pct', function () {

        // Initializing the modal with default values.
        $('#pct_add_modal #pct_add_form').initForm();
        $('#pct_add_modal').find('.modal-title').text('ADD CATEGORY')
        $('#pct_add_modal').modal('show');
    });


    $('form#pct_add_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        var pct_parent = $(this).find('.pct_parent:checked').val();
        // adding an additional input
        input.push({ name: "pct_parent", value: pct_parent });
        $(this).postForm(site_url("product_categories/save"), input, afterProductCategoriesSave)
    });

    $(document).on('change', 'form#pct_add_form .pct_parent_selector', function (e) {
        create_pct_parent_radios($(this), 'rad_pct_add_form', '')
    });

    $(document).on('click', 'form#pct_add_form .reset-pctopt', function (e) {
        reset_pct($(this).closest('form'))
    });

});


function beforeEditProductCategories(pct_id) {
    var input = { pct_id: pct_id };
    $('form#pct_add_form').postForm(site_url("product_categories/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);

        loadOption('product_categories/get_options', { pct_parent: 0, not: pct_id }, $('form#pct_add_form .pct_option'));


        if (eval(res.pct_parent)) {
            var select = $('form#pct_add_form .pct_parent_selector');
            create_pct_parent_radios(select, 'rad_pct_add_form', '', res.pct_parent, res.pct_parent_name, pct_id);
        }
        $('#pct_add_modal').modal('show');
    });
}
