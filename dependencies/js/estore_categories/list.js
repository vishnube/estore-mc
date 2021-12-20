$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#estr_cat_search_form').initForm();

	// Loading all categories. Before doing this you should initalize the search form
	loadEstoreCategories();

	$('form#estr_cat_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadEstoreCategories();
	});
});


function loadEstoreCategories() {
	var input = $('form#estr_cat_search_form').serializeArray();

	// adding an additional inputs
	// var mbrtp_id @ estores/index.php
	input.push({ name: "cat_fk_member_types", value: mbrtp_id });

	var url = site_url("categories/get_cats");

	$('form#estr_cat_search_form').postForm(url, input, createEstoreCategoryTable, '', $("#tbl_estr_cat_container"), false);
}

function loadEstoreCategoryOptions() {
	loadOption('categories/get_options', { cat_fk_member_types: mbrtp_id }, $('.estr_cat_option'));
}

// Create table list
function createEstoreCategoryTable(res) {
	var tbl = $("#tbl_estr_cat");
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
			str += editBtn('Category', ['.edit_estr_cat']);
			str += deleteBtn('Category', ['.deactivate_estr_cat'], 'Deactivate');
		}
		else if (row.cat_status == INACTIVE)
			str += activateBtn('Category', ['.activate_estr_cat']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

