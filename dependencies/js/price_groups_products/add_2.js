$(document).ready(function () {
    $('#product_load_form .pgp_option').change(function () {
        loadProducts(0)
        loadPriceGroupAddedProducts(0)
    });

    $(document).on('click', '.cstr-stk-dt', function () {
        $('#cstr_stock_modal').modal('show');
        var html = $(this).closest('tr').find('.cstr-stk-container').html()
        if (!html)
            html = get_no_result_div('NO STOCK');
        $('#cstr_stock_modal .modal-body').html(html);
    });


    $(document).on('click', '.edit_pgprd', function () {

        // If no task assigned
        if (!tsk_pgp_edit)
            return;

        var $pgprd_id = $(this).closest('tr').find('.pgprd_id').val();
        before_edit_price_group($pgprd_id);
    });


    // Modal @ price_groups_products\central_store_stock.php
    $(document).on('click', '#cstr_stock_modal .show-stock-flow', function () {
        // To work this, You need
        //      views/stock_avg/stock_flow.php
        // And the script 'js/stock_avg/stock_flow.js', 'bcmath-min.js' and 'php_function_equivalent_for_js.js' 
        // And you need to include following line of code in your controller index() function
        //      $cstr_mbrtp_id = $this->central_stores->get_member_type_id();
        //      $data['cstr_mbr_option'] = $this->central_stores->get_members_option(array('mbr_fk_clients' => $this->clnt_id), active, $cstr_mbrtp_id);
        //      $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));
        var container = $(this).closest('.cstr-stk-row');
        var cstr_mbr_id = container.find('.cstr_mbr_id').val();
        var prd_id = container.find('.prd_id').val();
        var pdbch_id = container.find('.pdbch_id').val();
        var ugp_id = $(this).closest('.ugp-stk').find('.ugp_id').val();
        initStockFlow(cstr_mbr_id, '', prd_id, pdbch_id, ugp_id);
    });


    $('form#pgprd_edit_form').on('submit', function (e) {
        e.preventDefault();
        var input = $(this).serializeArray();
        $(this).postForm(site_url("price_groups_products/edit"), input, function (res, form, input) {
            showSuccessToast('Price group saved successfully');
            $('#pgprd_edit_modal').modal('hide');
            loadPriceGroupAddedProducts($("#pgprd_added_pagination").curPage()); // @ price_groups_products\add_2.js       
            load_price_groups_products(0); // price_groups_products\list.js
        });
    });



    // Initializing the search from with default values.
    $('form#product_added_form').initForm();

    $(document).on('change', 'form#product_added_form .pct_parent_selector', function (e) {
        create_pct_parent_radios($(this), 'rad_product_added_form', ''); // @ product_categories/add.js
    });

    $(document).on('click', 'form#product_added_form .reset-pctopt', function (e) {
        reset_pct($(this).closest('form')); // @ product_categories/add.js
    });

    // Detect pagination click
    $("#pgprd_added_pagination").on("click", "a", function (e) {
        e.preventDefault();
        var pageno = $(this).attr("data-ci-pagination-page");
        if (typeof pageno == "undefined")
            return;
        loadPriceGroupAddedProducts(pageno);
    });


    $('form#prd-dt-from').on('submit', function (e) {
        // If no task assigned
        if (!tsk_pgp_add && !tsk_pgp_edit)
            return;

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("price_groups_products/save"), input, function (r, form, input) {
            showSuccessToast('Price group saved successfully');
            var prd_id = getInputValue(input, 'pgprd_fk_products');
            if (prd_id) {
                // #tbl_prd @ price_groups_products\product_not_added.php
                $("#tbl_prd .prd_id[value=" + prd_id + "]").closest('tr').next().find('.btn-unsel-prd').focus();

                // Marking the product as "Added" to Price Group by a 'Tik Icon'
                $("#tbl_prd .prd_id[value=" + prd_id + "]").closest('tr').find('.add-status').html(getAddedHTMLMark());
            }
            $('#product-details').modal('hide');
            loadPriceGroupAddedProducts(0); // @ price_groups_products\add_2.js       
            load_price_groups_products(0); // price_groups_products\list.js
        }, function (r, form, input) {
            showValidationErrors(r.v_error, form);
            showOtherErrors(r.o_error);

            if ($.isEmptyObject(r.v_error))
                return;

            // Validating
            $.each(r.v_error, function (index, value) {
                // Taking index of the field
                var temp = index.match(/\[(.*)\]/);
                if (temp) {
                    var i = temp[1];
                    var field = index.substr(0, index.indexOf('['));
                    form.find('.' + field).eq(i).parent().append(value);
                }
            });
        }, true, false);
    });


    // #product_added_form @ price_groups_products\product_added.php
    $('form#product_added_form').on('submit', function (e) {
        e.preventDefault();


        var pgp_id = $('#product_load_form .pgp_option').val(); // @ price_groups_products\product_not_added.php

        if (!pgp_id) {
            Swal.fire('Oops!', "Please select a price group", 'error');
            return true;
        }

        loadPriceGroupAddedProducts(0);
    });

}); //$(document).ready();



function before_edit_price_group(pgprd_id) {
    var input = { pgprd_id: pgprd_id };
    $('form#pgprd_edit_form').postForm(site_url("price_groups_products/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#pgprd_edit_modal').modal('show');
        $('#pgprd_edit_modal .prd-dt').html('<span class="btn btn-danger btn-flat">' + res.pgp_name + '</span><span class="btn btn-success btn-flat">' + res.prd_name + ' <small>[' + res.pdbch_name + ']</small></span>');
        $('#pgprd_edit_modal .pgprd_fk_unit_groups').html(res.unit_option);
    });
}

// Load pagination
function loadPriceGroupAddedProducts(pagno) {

    // If no task assigned
    if (!tsk_pgp_list)
        return;

    var pgp_id = $('#product_load_form .pgp_option').val(); // @ price_groups_products\product_not_added.php

    if (!pgp_id)
        return;

    var input = {
        pgprd_fk_price_groups: pgp_id,
        rad_product_load_form: $('#product_added_form [name=rad_product_added_form]:checked').val(), // Product category
        prd_name: $('#product_added_form [name=prd_name').val(),
        offset: pagno,
        per_page: $('.dv-perpage #pgprd_added_per_page').val()
    }

    var url = site_url("price_groups_products/get_pgprds2/") + pagno;
    $('#tbl_pgprd_added_container').postForm(url, input, afterPriceGroupAddedProductLoad, '', $('#tbl_pgprd_added_container'), false)
}


function onPriceGroupAddedProductPerpageChanged() {
    loadPriceGroupAddedProducts(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterPriceGroupAddedProductLoad(res, container, input) {
    $("#pgprd_added_pagination").html(res.page_link);
    $("#pgprd_added_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
    createPriceGroupAddedProductTable(res);
}

// Create table list
function createPriceGroupAddedProductTable(res) {
    var tbl = $("#tbl_pgprd_added");
    tbl.find("tbody").empty();
    sno = Number(res.offset);
    var str = "";
    if (!res.price_group_data.length) {
        str = get_no_result_row(tbl);
    }

    $.each(res.price_group_data, function (i, row) {
        sno += 1;
        str += "<tr class='quick-search-row'>";

        // $('.export').text() will be used for export (Print/PDF/Excel)
        str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
        str += "<td>";
        str += "<input type='hidden' class='prd_id' value='" + row.prd_id + "'>"
        str += "<input type='hidden' class='prd_name' value='" + row.prd_name + "'>";
        str += "<input type='hidden' class='pgprd_id' value='" + row.pgprd_id + "'>"
        str += "<input type='hidden' class='pgp_name' value='" + row.pgp_name + "'>";
        str += "<span class='export green-code' data-exportStyle='font-weight: bold; color:#f00; text-align: right;'>" + row.prd_name + ' (' + row.pdbch_name + ')' + '</span>';
        str += "</td>";
        str += "<td>" + parseFloat(roundNum(row.pgprd_qty, 2)) + ' ' + row.unt_name + "</td>";
        str += "<td>" + parseFloat(roundNum(row.pgprd_dsc, 2)) + "</td>";
        str += "<td>" + parseFloat(roundNum(row.pgprd_dscp, 2)) + "</td>";
        str += "<td>" + parseFloat(roundNum(row.pgprd_rate, 2)) + "</td>";
        str += "<td style='padding:7px 0'>";
        str += editBtn('PriceGroup', ['.edit_pgprd']);
        str += deleteBtn('PriceGroup', ['.delete_pgprd']);
        str += "</td>";
        str += "</tr>";
    });
    tbl.find("tbody").append(str);
}

$(document).on('click', '#tbl_pgprd_added .delete_pgprd', function () {

    // If no task assigned
    if (!tsk_pgp_deactivate)
        return;

    var $pgprd_id = $(this).closest('tr').find('.pgprd_id').val();
    var $pgp_name = $(this).closest('tr').find('.pgp_name').val();
    var $prd_name = $(this).closest('tr').find('.prd_name').val();

    var prd_id = $(this).closest('tr').find('.prd_id').val();

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
                    loadProducts($("#prd_pagination").curPage())
                    showSuccessToast('Price Group deleted successfully');
                    var pageNo = $("#pgprd_added_pagination").curPage();
                    loadPriceGroupAddedProducts(pageNo);
                    load_price_groups_products(0); // price_groups_products\list.js
                    if (prd_id) {

                        // Waiting for 2 sec to load #tbl_prd by func: 'loadProducts()'
                        setTimeout(function () {
                            // #tbl_prd @ price_groups_products\product_not_added.php
                            $("#tbl_prd .prd_id[value=" + prd_id + "]").closest('tr').find('.btn-unsel-prd').focus();
                        }, 2000);
                    }
                }
                else {
                    var msg = typeof r.o_error == 'undefined' ? 'Couldn\'t ' + 'delete' : r.o_error;
                    Swal.fire('Oops!', msg, 'error');
                }
            }, 'json');
        }
    });

});

