$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#pct_search_form').initForm();

	// Loading all categories. Before doing this you should initalize the search form
	loadProductCategories();

	$('form#pct_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadProductCategories();
	});



	$(document).on('change', 'form#pct_search_form .pct_parent_selector', function (e) {
		create_pct_parent_radios($(this), 'rad_pct_search_form', '')
	});

	$(document).on('click', 'form#pct_search_form .reset-pctopt', function (e) {
		reset_pct($(this).closest('form'))
	});

});

function loadProductCategories() {
	var input = $('form#pct_search_form').serializeArray();
	// adding an additional input
	input.push({ name: "pct_parent", value: $('form#pct_search_form').find('.pct_parent:checked').val() });
	var url = site_url("product_categories/get_pcts");
	$('form#pct_search_form').postForm(url, input, createProductCategoriesTable, '', $("#tbl_pct_container"), false);
}


function loadProductCategoriesOptions(pct_parent) {
	pct_parent = ifDef(pct_parent, pct_parent, 0);
	loadOption('product_categories/get_options', { pct_parent: pct_parent }, $('.pct_option'));
}

// Create table list
function createProductCategoriesTable(res) {
	var tbl = $("#tbl_pct");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.pct_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.pct_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='pct_id' value='" + row.pct_id + "'>";
		str += "<input type='hidden' class='pct_name' value='" + row.pct_name + "'>" + row.pct_name;
		str += "</td>";

		str += "<td>" + (row.parent_name ? row.parent_name : '') + "</td>";

		str += '<td class="text-right">';

		if (row.pct_status == ACTIVE) {
			str += editBtn('Category', ['.edit_pct']);
			str += deleteBtn('Category', ['.deactivate_pct'], 'Deactivate');
		}
		else if (row.pct_status == INACTIVE)
			str += activateBtn('Category', ['.activate_pct']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

