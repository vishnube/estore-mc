$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#stct_search_form').initForm();

	// Loading all Settings Categories. Before doing this you should initalize the search form
	loadSettingsCategories();

	$('form#stct_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadSettingsCategories();
	});
});


function loadSettingsCategories() {
	var input = $('form#stct_search_form').serializeArray();
	var url = site_url("settings_categories/get_stcts");
	$('form#stct_search_form').postForm(url, input, createSettingsCategoryTable, '', $("#tbl_stct_container"), false);
}

function loadSettingsCategoryOptions() {
	loadOption('settings_categories/get_options', [], $('.stct_option'));
}

// Create table list
function createSettingsCategoryTable(res) {
	var tbl = $("#tbl_stct");
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
		str += "<input type='hidden' class='stct_id' value='" + row.stct_id + "'>";
		str += "<input type='hidden' class='stct_name' value='" + row.stct_name + "'>" + row.stct_name;
		str += "</td>";
		str += "<td>" + row.stct_sort + "</td>";

		str += '<td class="text-right">';

		if (row.stct_status == ACTIVE) {
			str += editBtn('Settings category', ['.edit_stct']);
			str += deleteBtn('Settings category', ['.deactivate_stct'], 'Deactivate');
		}
		else if (row.stct_status == INACTIVE)
			str += activateBtn('Settings category', ['.activate_stct']);
		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

