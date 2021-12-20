$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#stky_search_form').initForm();

	// Loading all Settings Categories. Before doing this you should initalize the search form
	loadSettingsKeys();

	$('form#stky_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadSettingsKeys();
	});
});


function loadSettingsKeys() {
	var input = $('form#stky_search_form').serializeArray();
	var url = site_url("settings_keys/get_stkys");
	$('form#stky_search_form').postForm(url, input, createSettingsKeysTable, '', $("#tbl_stky_container"), false);
}

function loadSettingsKeysOptions() {
	loadOption('settings_keys/get_options', [], $('.stky_option'));
}

// Create table list
function createSettingsKeysTable(res) {
	var tbl = $("#tbl_stky");
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
		str += "<input type='hidden' class='stky_id' value='" + row.stky_id + "'>";
		str += "<input type='hidden' class='stky_name' value='" + row.stky_name + "'>" + row.stky_name;
		str += "</td>";

		str += "<td>" + row.stky_desc + "</td>";


		str += '<td class="text-right">';

		if (row.stky_status == ACTIVE) {
			str += editBtn('Settings key', ['.edit_stky']);
			str += deleteBtn('Settings key', ['.deactivate_stky'], 'Deactivate');
		}
		else if (row.stky_status == INACTIVE)
			str += activateBtn('Settings key', ['.activate_stky']);
		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

