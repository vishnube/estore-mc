$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#emply_cat_search_form').initForm();

	// Loading all categories. Before doing this you should initalize the search form
	loadEmployeeCategories();

	$('form#emply_cat_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadEmployeeCategories();
	});
});


function loadEmployeeCategories() {
	var input = $('form#emply_cat_search_form').serializeArray();

	// adding an additional inputs
	// var mbrtp_id @ employees/index.php
	input.push({ name: "cat_fk_member_types", value: mbrtp_id });

	var url = site_url("categories/get_cats");

	$('form#emply_cat_search_form').postForm(url, input, createEmployeeCategoryTable, '', $("#tbl_emply_cat_container"), false);
}

function loadEmployeeCategoryOptions() {
	loadOption('categories/get_options', { cat_fk_member_types: mbrtp_id }, $('.emply_cat_option'));
}

// Create table list
function createEmployeeCategoryTable(res) {
	var tbl = $("#tbl_emply_cat");
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
			str += editBtn('Category', ['.edit_emply_cat']);
			str += deleteBtn('Category', ['.deactivate_emply_cat'], 'Deactivate');
		}
		else if (row.cat_status == INACTIVE)
			str += activateBtn('Category', ['.activate_emply_cat']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

