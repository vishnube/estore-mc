$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#prty_cat_search_form').initForm();

	// Loading all categories. Before doing this you should initalize the search form
	loadPartyCategories();

	$('form#prty_cat_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadPartyCategories();
	});
});


function loadPartyCategories() {
	var input = $('form#prty_cat_search_form').serializeArray();

	// adding an additional inputs
	// var mbrtp_id @ parties/index.php
	input.push({ name: "cat_fk_member_types", value: mbrtp_id });

	var url = site_url("categories/get_cats");

	$('form#prty_cat_search_form').postForm(url, input, createPartyCategoryTable, '', $("#tbl_prty_cat_container"), false);
}

function loadPartyCategoryOptions() {
	loadOption('categories/get_options', { cat_fk_member_types: mbrtp_id }, $('.prty_cat_option'));
}

// Create table list
function createPartyCategoryTable(res) {
	var tbl = $("#tbl_prty_cat");
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
			str += editBtn('Category', ['.edit_prty_cat']);
			str += deleteBtn('Category', ['.deactivate_prty_cat'], 'Deactivate');
		}
		else if (row.cat_status == INACTIVE)
			str += activateBtn('Category', ['.activate_prty_cat']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

