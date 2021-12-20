$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#grp_search_form').initForm();

	// Loading all groups. Before doing this you should initalize the search form
	loadgroups();

	$('form#grp_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadgroups();
	});
});


function loadgroups() {
	var input = $('form#grp_search_form').serializeArray();
	var url = site_url("groups/get_grps");
	$('form#grp_search_form').postForm(url, input, creategroupTable, '', $("#tbl_grp_container"), false);
}

function loadgroupOptions() {
	loadOption('groups/get_options', [], $('.grp_option'));
}

// Create table list
function creategroupTable(res) {
	var tbl = $("#tbl_grp");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.table.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.table, function (i, row) {

		var style = (row.grp_pre == 1) ? ' style="cursor: no-drop"' : ''; // Cursor "blocked" for preefined groups.
		sno += 1;
		str += "<tr" + style + ">";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='grp_id' value='" + row.grp_id + "'>";
		str += "<input type='hidden' class='grp_name' value='" + row.grp_name + "'>" + row.grp_name;
		str += "</td>";

		str += '<td class="text-right">';

		if (row.grp_status == ACTIVE) {
			str += editBtn('group', ['.edit_grp']);
			str += deleteBtn('group', ['.deactivate_grp'], 'Deactivate');
		}
		else if (row.grp_status == INACTIVE)
			str += activateBtn('group', ['.activate_grp']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

