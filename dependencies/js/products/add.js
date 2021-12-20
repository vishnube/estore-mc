$(document).ready(function () {

    // Initializing the from with default values.
    $('form#prd_add_form').initForm();

    $('form#prd_add_form').on('submit', function (e) {

        // If no task assigned
        if (!tsk_prd_add)
            return;

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var form_data = new FormData();

        // Getting form input values in object format
        var input = objectifyForm($(this).serializeArray());

        // Adding object values in to form_data
        for (var key in input) {
            form_data.append(key, input[key]);
        }

        // Adding extra values.
        var pct_parent = $(this).find('.pct_parent:checked').val();
        form_data.append('pct_parent', ifDef(pct_parent, pct_parent, ''));

        // Multiple select values should be added manually
        form_data.delete('tg_id[]');
        var tg_id = $('#prd_add_form .tg_option').val();
        $.each(tg_id, function (index, value) {
            form_data.append("tg_id[]", value);
        });


        // Adding photo1
        var photo1 = $("#photo1").prop("files")[0];
        form_data.append("photo1", ifDef(photo1, photo1, ''));

        // Adding photo2
        var photo2 = $("#photo2").prop("files")[0];
        form_data.append("photo2", ifDef(photo2, photo2, ''));

        // Adding Units
        form_data.delete('product_units');
        $(this).find('#tbl_prd_add_ugp .prd_add_ugp_check:checked').each(function () {
            form_data.append("product_units[]", $(this).val());
        });

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("products/save"), form_data, afterProductSave, function (r, form) {
            showValidationErrors(r.v_error, form);
            showOtherErrors(r.o_error);

            if ($.isEmptyObject(r.v_error))
                return;

            // Photo1 validation
            if (typeof r.v_error.photo1 != 'undefined')
                form.find('#photo1').closest('.sr-input-group').append(r.v_error.photo1);

            // Photo1 validation
            if (typeof r.v_error.photo2 != 'undefined')
                form.find('#photo2').closest('.sr-input-group').append(r.v_error.photo2);

        }, true, true, true)

    });




    $(document).on('change', 'form#prd_add_form .pct_parent_selector', function (e) {
        create_pct_parent_radios($(this), 'rad_prd_add_form', ''); // @ product_categories/index.js
    });

    $(document).on('click', 'form#prd_add_form .reset-pctopt', function (e) {
        reset_pct($(this).closest('form')); // @ product_categories/index.js
    });

});

function afterProductAddFormReset(form) {
    // Rich Text Editor
    form.find('.prd_disc').summernote({ height: 150 });
    form.find('.prd_disc').summernote('code', '');

    // File Uploader
    bsCustomFileInput.init();
    form.find('.custom-file-label').html('');

    // CAtegory
    reset_pct(form); // @ product_categories/index.js

    //Showing product unit add container. It will be hidden on EDIT
    $('form#prd_add_form .dv-product-units').show();
}

function resetFile(obj) {
    $(obj).closest('.input-group').find('.custom-file-input').val('');
    $(obj).closest('.input-group').find('.custom-file-label').html('')
}

function beforeEditProduct(prd_id) {

    //Hiding product unit add container
    $('form#prd_add_form .dv-product-units').hide();

    var input = { prd_id: prd_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#prd_add_form .sr-form-tag .sr-form-title').html('EDIT PRODUCT');

    $('form#prd_add_form').postForm(site_url("products/before_edit"), input, function (res, form) {

        form.loadFormInputs(res);

        // Rich Text Editor
        form.find('.prd_disc').summernote('code', res.prd_disc);

        // Categroy     
        if (eval(res.prd_fk_product_categories)) {
            var select = $('form#prd_add_form .pct_parent_selector');
            create_pct_parent_radios(select, 'rad_prd_add_form', '', res.prd_fk_product_categories, res.cat_name);
        }

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#prd_add_form .sr-form-tag .sr-form-title').html('EDIT PRODUCT');

        //Hiding product unit add container
        $('form#prd_add_form .dv-product-units').hide();
    }, function (res, form) {
        activateTab('list');
        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });
}