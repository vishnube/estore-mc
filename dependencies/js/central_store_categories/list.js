$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#cstr_cat_search_form').initForm();

	// Loading all categories. Before doing this you should initalize the search form
	loadCentralStoreCategories();

	$('form#cstr_cat_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadCentralStoreCategories();
	});
});


function loadCentralStoreCategories() {
	var input = $('form#cstr_cat_search_form').serializeArray();

	// adding an additional inputs
	// var mbrtp_id @ central_stores/index.php
	input.push({ name: "cat_fk_member_types", value: mbrtp_id });

	var url = site_url("categories/get_cats");

	$('form#cstr_cat_search_form').postForm(url, input, createCentralStoreCategoryTable, '', $("#tbl_cstr_cat_container"), false);
}

function loadCentralStoreCategoryOptions() {
	loadOption('categories/get_options', { cat_fk_member_types: mbrtp_id }, $('.cstr_cat_option'));
}

// Create table list
function createCentralStoreCategoryTable(res) {
	var tbl = $("#tbl_cstr_cat");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.table.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.table, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='cat_id' value='" + row.cat_id + "'>";
		str += "<input type='hidden' class='cat_name' value='" + row.cat_name + "'>" + row.cat_name;
		str += "</td>";

		str += '<td class="text-right">';

		if (row.cat_status == ACTIVE) {
			str += editBtn('Category', ['.edit_cstr_cat']);
			str += deleteBtn('Category', ['.deactivate_cstr_cat'], 'Deactivate');
		}
		else if (row.cat_status == INACTIVE)
			str += activateBtn('Category', ['.activate_cstr_cat']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

