$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#tg_search_form').initForm();

	// Loading all tags. Before doing this you should initalize the search form
	loadTags();

	$('form#tg_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadTags();
	});
});


function loadTags() {
	var input = $('form#tg_search_form').serializeArray();
	var url = site_url("tags/get_tgs");
	$('form#tg_search_form').postForm(url, input, createTagTable, '', $("#tbl_tg_container"), false);
}

function loadTagOptions() {
	loadOption('tags/get_options', [], $('.tg_option'));
}

// Create table list
function createTagTable(res) {
	var tbl = $("#tbl_tg");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.tag_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.tag_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='tg_id' value='" + row.tg_id + "'>";
		str += "<input type='hidden' class='tg_name' value='" + row.tg_name + "'><span class='text-danger'>" + row.tg_name + '</span>';
		str += "</td>";

		str += '<td class="text-right">';

		if (row.tg_status == ACTIVE) {
			str += editBtn('Tag', ['.edit_tg']);
			str += deleteBtn('Tag', ['.deactivate_tg'], 'Deactivate');
		}
		else if (row.tg_status == INACTIVE)
			str += activateBtn('Tag', ['.activate_tg']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

