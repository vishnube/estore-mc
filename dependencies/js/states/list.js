$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#stt_search_form').initForm();

	// Loading all states. Before doing this you should initalize the search form
	loadStates();

	$('form#stt_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadStates();
	});
});


function loadStates() {
	var input = $('form#stt_search_form').serializeArray();
	var url = site_url("states/get_stts");
	$('form#stt_search_form').postForm(url, input, createStateTable, '', $("#tbl_stt_container"), false);
}

function loadStateOptions() {
	loadOption('states/get_options', [], $('.stt_option'));
}

// Create table list
function createStateTable(res) {
	var tbl = $("#tbl_stt");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.state_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.state_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='stt_id' value='" + row.stt_id + "'>";
		str += "<input type='hidden' class='stt_name' value='" + row.stt_name + "'><span class='text-danger'>" + row.stt_name + '</span>';
		str += "</td>";

		str += '<td class="text-right">';

		if (row.stt_status == ACTIVE) {
			//str += editBtn('State', ['.edit_stt']);
			str += deleteBtn('State', ['.deactivate_stt'], 'Deactivate');
		}
		else if (row.stt_status == INACTIVE)
			str += activateBtn('State', ['.activate_stt']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

