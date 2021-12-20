
// To work this you need to include following line of code in your controller index() function
//      $cstr_mbrtp_id = $this->central_stores->get_member_type_id();
//      $data['cstr_mbr_option'] = $this->central_stores->get_members_option(array('mbr_fk_clients' => $this->clnt_id), active, $cstr_mbrtp_id);
//      $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));
// And
//      $this->load->view('stock_avg/stock_flow');
// 
// And the script 'bcmath-min.js' and 'php_function_equivalent_for_js.js' 
$(document).ready(function () {

    // Initializing the search form with default values.
    $('form#stock_flow_search_form').initForm();

    $('form#stock_flow_search_form').on('submit', function (e) {
        e.preventDefault();
        loadStockFlow(0);
    });

    // Detect pagination click
    $("#stock_flow_pagination").on("click", "a", function (e) {
        e.preventDefault();
        var pageno = $(this).attr("data-ci-pagination-page");
        if (typeof pageno == "undefined")
            return;
        loadStockFlow(pageno);
    });

    $('#stock_flow_search_form .cstr_option').change(function () {
        onStkFlowCstrChanged($(this))
    });

    $('#stock_flow_search_form .prd_option').change(function () {
        onStkFlowPrdChanged($(this))
    });
});

function initStockFlow(cstr_mbr_id, gdn_id, prd_id, pdbch_id, ugp_id) {
    // console.log('cstr_mbr_id: ' + cstr_mbr_id);
    // console.log('gdn_id: ' + gdn_id);
    // console.log('prd_id: ' + prd_id);
    // console.log('pdbch_id: ' + pdbch_id);
    // console.log('ugp_id: ' + ugp_id);

    if (!cstr_mbr_id || !prd_id || !pdbch_id || !ugp_id) {
        alert("Central Store / Product / Batch / Unit are mandatory");
        return;
    }

    var input = {
        stkavg_cstr_mbr_id: cstr_mbr_id,
        stkavg_fk_products: prd_id,
        stkavg_fk_product_batches: pdbch_id,
        stkavg_ugp_id: ugp_id,
        offset: 0,
        per_page: $('.dv-perpage #stock_flow_per_page').val()
    }

    loadStockFlow(0, input);

    $('form#stock_flow_search_form .cstr_option').val(cstr_mbr_id);
    onStkFlowCstrChanged($('form#stock_flow_search_form .cstr_option'))
    $('form#stock_flow_search_form .prd_option').val(prd_id);
    loadStkFlowPdbch($('form#stock_flow_search_form .prd_option'), pdbch_id);
    loadStkFlowBasicUgp($('form#stock_flow_search_form .prd_option'), ugp_id);
    $('#stock-flow-modal').modal('show');
}

function loadStockFlow(pagno, input) {

    if (typeof input == 'undefined') {
        if (
            !$('form#stock_flow_search_form .cstr_option').val() ||
            !$('form#stock_flow_search_form .prd_option').val() ||
            !$('form#stock_flow_search_form .pdbch_option').val() ||
            !$('form#stock_flow_search_form .ugp_option').val()
        ) {
            alert("Central Store / Product / Batch / Unit are mandatory");
            return;
        }
        input = $('form#stock_flow_search_form').serializeArray();

        // adding an additional input
        input.push({ name: "offset", value: pagno })
        input.push({ name: "per_page", value: $('.dv-perpage #stock_flow_per_page').val() })
    }

    $('#stockFlowQuickSearch').val('');
    var url = site_url("stock_avg/get_stock_flow/") + pagno;
    $('form#stock_flow_search_form').postForm(url, input, afterStockFlowLoad, '', $('#tbl_stock_flow_container'), false)
}



function onStockFlowPerpageChanged() {
    loadStockFlow(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterStockFlowLoad(res, container, input) {
    $("#stock_flow_pagination").html(res.page_link);
    $("#stock_flow_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
    createStockFlowTable(res);
    goToTbl($("#stock_flow_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createStockFlowTable(res) {
    var tbl = $("#tbl_stock_flow");
    tbl.find("tbody").empty();
    sno = Number(res.offset);
    var str = "";
    if (!res.stock_avg_data.length) {
        str = get_no_result_row(tbl);
    }

    $.each(res.stock_avg_data, function (i, row) {
        sno += 1;

        str += "<tr class='quick-search-row'>";

        // $('.export').text() will be used for export (Print/PDF/Excel)
        str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
        str += "<td>";
        str += "<input type='hidden' class='stkavg_id' value='" + row.stkavg_id + "'>";
        str += "<input type='hidden' class='cstr_mbr_id' value='" + row.stkavg_cstr_mbr_id + "'>";
        str += "<input type='hidden' class='prd_id' value='" + row.stkavg_fk_products + "'>";
        str += "<input type='hidden' class='pdbch_id' value='" + row.stkavg_fk_product_batches + "'>";
        str += "<input type='hidden' class='ugp_id' value='" + row.stkavg_ugp_id + "'>";
        str += "<input type='hidden' class='gdn_id' value='" + row.stkavg_fk_godowns + "'>";
        str += "<input type='hidden' class='ref_tbl' value='" + row.stkavg_ref_tbl + "'>";
        str += "<input type='hidden' class='ref_id' value='" + row.stkavg_ref_id + "'>";
        str += "<span class='export'>" + $.format.date(row.stkavg_date, 'dd/MM/yyyy h:mm:ss a') + "</span>";
        str += "</td>";

        str += "<td><span class='export'>" + row.gdn_name + "</span></td>";
        str += "<td><span class='export'>" + row.stkavg_ref_tbl + "</span></td>";
        str += "<td><span class='export'>" + (parseFloat(row.stkavg_qty_in) ? parseFloat(row.stkavg_qty_in) + ' ' + row.unt_name + ' * ' + parseFloat(row.stkavg_rate) : '') + "</span></td>";
        str += "<td><span class='export'>" + (parseFloat(row.stkavg_qty_out) ? parseFloat(row.stkavg_qty_out) + ' ' + row.unt_name + ' * ' + parseFloat(row.stkavg_rate) : '') + "</span></td>";
        str += "<td><span class='export'>" + parseFloat(row.stkavg_bal_qty) + ' ' + row.unt_name + ' * ' + parseFloat(row.stkavg_bal_rate) + " = " + parseFloat(roundNum(bcmul(row.stkavg_bal_qty, row.stkavg_bal_rate, 5), 2)) + "</span></td>";
        str += "<td><span class='export'>" + parseFloat(row.stkavg_bal_gdn_qty) + ' ' + row.unt_name + "</span></td>";
        str += "</tr>";
    });
    tbl.find("tbody").append(str);
}



function onStkFlowCstrChanged(obj) {
    var target = obj.closest('form').find('.gdn_option');
    target.noOption('No Godowns');
    var cstr_id = obj.val();
    if (!cstr_id)
        return
    loadOption('central_stores/get_gdn_options', { mbr_id: cstr_id }, target, target.closest('div'));
}

function onStkFlowPrdChanged(obj) {
    loadStkFlowPdbch(obj);
    loadStkFlowBasicUgp(obj);
}

function loadStkFlowPdbch(obj, pdbch_id) {
    var t = obj.closest('form').find('.pdbch_option');
    t.noOption();

    var d = obj.val();
    if (!d)
        return;
    loadOption('product_batches/get_batches_by_prd_ids', {
        prd_ids: d
    }, t, t.closest('div'), function (t) {
        if (typeof pdbch_id != 'undefined')
            t.val(pdbch_id)
    });
}

function loadStkFlowBasicUgp(obj, ugp_id) {
    var t = obj.closest('form').find('.ugp_option');
    t.noOption();

    var d = obj.val();
    if (!d)
        return;
    loadOption('products/get_basic_units', {
        prd_id: d
    }, t, t.closest('div'), function (t) {
        if (typeof ugp_id != 'undefined')
            t.val(ugp_id)
    });
}
