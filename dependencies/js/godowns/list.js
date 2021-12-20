$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#gdn_search_form').initForm();

	// Loading all godowns. Before doing this you should initalize the search form
	loadGodowns();

	$('form#gdn_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadGodowns();
	});
});


function loadGodowns() {
	if (!$('#gdn_search_form #gdn_fk_central_stores').val()) {
		var tbl = $("#tbl_gdn");
		tbl.find("tbody").html(get_no_result_row(tbl, 'SELECT A CENTRAL STORE'));
		return;
	}

	var input = $('form#gdn_search_form').serializeArray();
	var url = site_url("godowns/get_gdns");
	$('form#gdn_search_form').postForm(url, input, createGodownTable, '', $("#tbl_gdn_container"), false);
}

function loadGodownOptions() {
	loadOption('godowns/get_options', [], $('.gdn_option'));
}

// Create table list
function createGodownTable(res) {
	var tbl = $("#tbl_gdn");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.godown_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.godown_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='gdn_id' value='" + row.gdn_id + "'>";
		str += "<input type='hidden' class='gdn_name' value='" + row.gdn_name + "'>" + row.cstr_name;
		str += "</td>";


		str += "<td>" + row.gdn_name + "</td>";

		str += '<td class="text-right">';

		if (row.gdn_status == ACTIVE) {
			str += editBtn('Godown', ['.edit_gdn']);
			str += deleteBtn('Godown', ['.deactivate_gdn'], 'Deactivate');
		}
		else if (row.gdn_status == INACTIVE)
			str += activateBtn('Godown', ['.activate_gdn']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

