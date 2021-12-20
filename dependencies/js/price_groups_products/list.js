
// Load pagination
function load_price_groups_products(pagno) {

	// If no task assigned
	if (!tsk_pgp_list)
		return;

	var input = $('form#pgprd_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #pgprd_per_page').val() })

	$('#priceGroupsProductsQuickSearch').val('');
	var url = site_url("price_groups_products/get_pgprds/") + pagno;
	$('form#pgprd_search_form').postForm(url, input, after_price_groups_products_load, '', $('#tbl_pgprd_container'), false)
}


function onPriceGroupsProductsPerpageChanged() {
	load_price_groups_products(0);
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function after_price_groups_products_load(res, container, input) {
	$("#pgprd_pagination").html(res.page_link);
	$("#pgprd_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createPrice_groups_productsTable(res);

	//Scroll to table if 'List Tab' is opened
	// if ($('#pgprd-tab').hasClass('active'))
	// 	goToTbl($("#pgprd_pagination"), $('#tbl_pgprd_container'));
}

// Create table list
function createPrice_groups_productsTable(res) {
	var tbl = $("#tbl_pgprd");
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
		str += "<input type='hidden' class='pgprd_id' value='" + row.pgprd_id + "'>"
		str += "<input type='hidden' class='pgp_name' value='" + row.pgp_name + "'>";
		str += "<input type='hidden' class='prd_name' value='" + row.prd_name + "'>";
		str += "<span class='export'>" + row.pgp_name + '</span>' + ' <span class="code-block">#PRC-' + row.pgprd_id + '</span>';



		var validity = $.format.date(row.pgpl_vf + ' 00:00:00.546', "dd/MM/yyyy") + ' - ' + $.format.date(row.pgpl_vt + ' 00:00:00.546', "dd/MM/yyyy");
		var vf = new Date(row.pgpl_vf);
		var vt = new Date(row.pgpl_vt);
		var today = new Date();
		vf = new Date(vf.getUTCFullYear(), vf.getUTCMonth(), vf.getUTCDate())
		vt = new Date(vt.getUTCFullYear(), vt.getUTCMonth(), vt.getUTCDate())
		today = new Date(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate());
		var vcls = (vf <= today && vt >= today) ? 'text-success' : 'text-danger';
		validityStr = '<span class="pl-2 ' + vcls + '" title="Validity">' + validity + '</span>';


		if (row.locations)
			str += '<div style="font-size: 12px;">' + row.locations + validityStr + '</div>';
		if (row.cstr_dt)
			str += '<div style="font-size: 12px;">' + row.cstr_dt + validityStr + '</div>';
		str += "</td>";


		str += "<td>";
		str += row.prd_name + ' <span class="badge badge-primary">' + row.pdbch_name + "</span>";
		str += "<span class='export d-none'>" + row.prd_name + ' (' + row.pdbch_name + ")</span>";
		str += "</td>";
		// Date format documentation:   https://github.com/phstc/jquery-dateFormat
		str += "<td><span class='export'>" + $.format.date(row.pgprd_date + ' 00:00:00.546', "dd/MM/yyyy") + "</span></td>";

		str += "<td><span class='export'>" + parseFloat(row.pgprd_qty) + ' ' + row.unt_name + "</span></td>";
		str += "<td><span class='export'>" + parseFloat(row.pgprd_dsc) + "</span></td>";
		str += "<td><span class='export'>" + parseFloat(row.pgprd_dscp) + '%' + "</span></td>";
		str += "<td><span class='export'>" + parseFloat(row.pgprd_rate) + "</span></td>";

		str += '<td class="text-right">';

		if (tsk_pgp_edit)
			str += editBtn('Product', ['.edit_pgprd']);
		if (tsk_pgp_deactivate)
			str += deleteBtn('Product', ['.delete_pgprd']);
		//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}