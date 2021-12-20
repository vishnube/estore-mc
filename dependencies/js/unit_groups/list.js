$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#ugp_search_form').initForm();

	// Loading all unit_groups. Before doing this you should initalize the search form
	loadUnitGroups();

	$('form#ugp_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadUnitGroups();
	});
});


function loadUnitGroups() {
	var input = $('form#ugp_search_form').serializeArray();
	var url = site_url("unit_groups/get_ugps");
	$('form#ugp_search_form').postForm(url, input, afterLoadUnitGroups, '', $("#tbl_ugp_container"), false);
}

function afterLoadUnitGroups(res) {
	createProductAddUnitGroupTable(res); // @ products/list.js
	createProductUnitAddTable(res); // @ product_units/list.js
	createUnitGroupTable(res);
}


// Create table list
function createUnitGroupTable(res) {
	var tbl = $("#tbl_ugp");
	tbl.find("tbody").empty();

	var str = "";
	if (!res.unit_group_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.unit_group_data, function (i, row) {

		str += "<tr>";
		str += "<td><span class='mt-2 d-block'>" + row.ugp_group_no + "</span></td>";
		str += "<td>" + row.ugp_name + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='ugp_group_no' value='" + row.ugp_group_no + "'>";
		str += row.text;
		str += "</td>";

		str += '<td class="text-right">';

		if (row.ugp_status == ACTIVE) {
			str += editBtn('Unit_group', ['.edit_ugp', '.mt-2']);
			str += deleteBtn('Unit_group', ['.deactivate_ugp', '.mt-2'], 'Deactivate');
		}
		else if (row.ugp_status == INACTIVE)
			str += activateBtn('Unit_group', ['.activate_ugp, .mt-2']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

