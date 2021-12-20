$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#prd_search_form').initForm();

	// Loading all products. Before doing this you should initialize the search form
	loadProducts(0);

	$('form#prd_search_form').on('submit', function (e) {
		e.preventDefault();
		loadProducts(0);
	});

	// Detect pagination click
	$("#prd_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadProducts(pageno);
	});
});


// Load pagination
function loadProducts(pagno) {

	// If no task assigned
	if (!tsk_prd_list)
		return;

	var input = $('form#prd_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #prd_per_page').val() })

	$('#productQuickSearch').val('');
	var url = site_url("products/get_prds/") + pagno;
	$('form#prd_search_form').postForm(url, input, afterProductLoad, '', $('#tbl_prd_container'), false)
}

function loadProductOptions() {
	loadOption('products/get_options', [], $('.prd_option'));
}


function onProductPerpageChanged() {
	loadProducts(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterProductLoad(res, container, input) {
	$("#prd_pagination").html(res.page_link);
	$("#prd_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createProductTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#prd-list-tab').hasClass('active'))
		goToTbl($("#prd_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createProductTable(res) {
	var tbl = $("#tbl_prd");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.product_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.product_data, function (i, row) {
		sno += 1;

		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='prd_id' value='" + row.prd_id + "'>"
		str += "<input type='hidden' class='prd_name' value='" + row.prd_name + "'><span class='export' data-exportStyle='font-weight: bold; color:#f00; text-align: right;'>" + row.prd_name + '</span>';
		str += "&nbsp;<i title='Show Details' class='cursor-pointer show-prd-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";

		str += "</td>";

		str += "<td>";
		str += row.cats + '<i class="far fa-chevron-double-right mx-1" style="font-size:8px"></i><span class="text-success"><u>' + row.pct_name + "</u></span>";
		str += "<span class='export d-none'>" + row.cats_export + "</span>";
		str += "</td>";


		// str += "<td><span class='export'>" + row.prd_rate_type + "</span></td>";
		// str += "<td><span class='export'>" + row.prd_madein + "</span></td>";
		// str += "<td><span class='export'>" + row.prd_prod_type + "</span></td>";



		str += "<td> ";
		str += row.product_units + row.add_unit;
		str += "<span class='export d-none'>" + row.product_unit_export + "</span>";
		str += "</td>";
		//str += "<td><span class='export'>" + row.prd_dietary + "</span></td>";

		str += "<td>";
		str += '<span class="badge bg-danger">' + Object.values(res.tags[row.prd_id]).join('</span>&nbsp;<span class="badge bg-danger">') + '</span>';
		str += '<span class="export d-none">' + Object.values(res.tags[row.prd_id]).join(', ') + '</span>';
		str += "</td>";


		str += '<td class="text-right">';

		if (row.prd_status == ACTIVE) {
			//str += addBtn('Product', ['#add_prd']);
			if (tsk_prd_edit)
				str += editBtn('Product', ['.edit_prd']);
			if (tsk_prd_deactivate)
				str += deleteBtn('Product', ['.deactivate_prd'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.prd_status == INACTIVE && tsk_prd_activate)
			str += activateBtn('Product', ['.activate_prd']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}


function createProductAddUnitGroupTable(res) {
	var tbl = $("#tbl_prd_add_ugp"); // @ products/add.php
	tbl.find("tbody").empty();

	var str = "";
	if (!res.unit_group_data.length) {
		str = '	<h3 class="text-danger text-center">' +
			'		<i class="fas fa-exclamation-triangle fa-lg"></i>' +
			'		<span class="pl-3">NO UNITS FOUND</span>' +
			'	</h3>';
	}
	$.each(res.unit_group_data, function (i, row) {
		str += "<tr class='quick-search-row'>";
		str += '<td>' +
			'		<div class="form-group clearfix mt-2">' +
			'			<div class="icheck-primary d-inline">' +
			'				<input id="ugp-' + row.ugp_group_no + '" class="prd_add_ugp_check" type="checkbox" value="' + row.ugp_group_no + '">' +
			'					<label for="ugp-' + row.ugp_group_no + '"></label>' +
			'			</div>' +
			'		</div>' +
			'	</td>';
		str += "<td style='font-size:20px;'>" + row.ugp_name + "</td>";
		str += "<td>" + row.text + "</td>";

		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}


$(document).on('click', '.show-prd-details', function () {
	var prd_id = $(this).closest('tr').find('.prd_id').val();
	var input = { prd_id: prd_id };

	$('#product_details_modal').postForm(site_url("products/get_details"), input, function (res) {
		$('#product_details_modal').modal('show');
		$('#product_details_modal .modal-content').html(res.html);
	});
});