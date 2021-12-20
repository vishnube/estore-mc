$(document).ready(function () {

    // Initializing the search form with default values.
    $('form#stock_search_form').initForm();

    // Loading all orders. Before doing this you should initialize the search form
    loadStock(0);

    $('form#stock_search_form').on('submit', function (e) {
        e.preventDefault();
        loadStock(0);
    });

    // Detect pagination click
    $("#stock_pagination").on("click", "a", function (e) {
        e.preventDefault();
        var pageno = $(this).attr("data-ci-pagination-page");
        if (typeof pageno == "undefined")
            return;
        loadStock(pageno);
    });
});


// Load pagination
function loadStock(pagno) {
    // If no task assigned
    if (!tsk_odr_stk_list)
        return;

    var input = $('form#stock_search_form').serializeArray();

    // adding an additional input
    input.push({ name: "offset", value: pagno })
    input.push({ name: "per_page", value: $('.dv-perpage #stk_per_page').val() })

    $('#stockQuickSearch').val('');
    var url = site_url("orders/get_stock/") + pagno;
    $('form#stock_search_form').postForm(url, input, afterStockLoad, '', $('#tbl_stk_container'), false)
}

// function loadOrderOptions() {
//     loadOption('orders/get_options', [], $('.stk_option'));
// }


function onStockPerpageChanged() {
    loadStock(0)
}

/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterStockLoad(res, container, input) {
    $("#stock_pagination").html(res.page_link);
    $("#stock_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
    createStockTable(res);

    //Scroll to table if 'List Tab' is opened
    if ($('#stk-list-tab').hasClass('active'))
        goToTbl($("#stock_pagination"), $('.sr-tbl-cont'));
}

function emptyStockTable() {
    var tbl = $("#tbl_stk");
    tbl.find("tbody").html(get_no_result_row(tbl));
}

// Create table list
function createStockTable(res) {
    var tbl = $("#tbl_stk");
    tbl.find("tbody").empty();
    sno = Number(res.offset);
    var str = "";
    if (!res.order_data.length) {
        str = get_no_result_row(tbl);
    }

    $.each(res.order_data, function (i, row) {
        sno += 1;

        str += "<tr class='quick-search-row'>";

        // $('.export').text() will be used for export (Print/PDF/Excel)
        str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
        str += "<td>";
        str += "<input type='hidden' class='prd_id' value='" + row.prd_id + "'>"
        str += "<input type='hidden' class='prd_name' value='" + row.prd_name + "'><span class='export'>" + row.prd_name + '</span>';

        str += "</td>";

        str += "<td><span class='export'>" + parseFloat(row.qty) + ' ' + row.unt_name + "</span></td>";
        str += "<td><span class='export'>" + (parseFloat(row.picked) ? parseFloat(row.picked) + ' ' + row.unt_name : '') + "</span></td>";
        var totQty = bcadd(row.qty, row.picked, 5);

        var inQty = row.stk ? parseFloat(row.stk.qty) + ' ' + row.unt_name : ''// Quantity in stock (Total Stock quantity)
        str += "<td><span class='export'>" + inQty + "</span></td>";

        var outQty = ''; // Quantity out of stock
        if (!row.stk)
            outQty = parseFloat(totQty) + ' ' + row.unt_name;
        else if (parseFloat(row.stk.qty) < parseFloat(row.qty))
            outQty = parseFloat(bcsub(parseFloat(totQty), parseFloat(row.stk.qty), 5)) + ' ' + row.unt_name;
        str += "<td><span class='export'>" + outQty + "</span></td>";


        str += "</tr>";
    });
    tbl.find("tbody").append(str);
}


