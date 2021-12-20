$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#brnd_search_form').initForm();

	// Loading all brands. Before doing this you should initalize the search form
	loadBrands();

	$('form#brnd_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadBrands();
	});
});


function loadBrands() {
	var input = $('form#brnd_search_form').serializeArray();
	var url = site_url("brands/get_brnds");
	$('form#brnd_search_form').postForm(url, input, createBrandTable, '', $("#tbl_brnd_container"), false);
}

function loadBrandOptions() {
	loadOption('brands/get_options', [], $('.brnd_option'));
}

// Create table list
function createBrandTable(res) {
	var tbl = $("#tbl_brnd");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.brand_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.brand_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='brnd_id' value='" + row.brnd_id + "'>";
		str += "<input type='hidden' class='brnd_name' value='" + row.brnd_name + "'><span class='text-danger'>" + row.brnd_name + '</span>';
		str += "</td>";

		str += '<td class="text-right">';

		if (row.brnd_status == ACTIVE) {
			str += editBtn('Brand', ['.edit_brnd']);
			str += deleteBtn('Brand', ['.deactivate_brnd'], 'Deactivate');
		}
		else if (row.brnd_status == INACTIVE)
			str += activateBtn('Brand', ['.activate_brnd']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

