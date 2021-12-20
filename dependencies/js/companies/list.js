$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#cmp_search_form').initForm();

	// Loading all companies. Before doing this you should initalize the search form
	loadCompanies();

	$('form#cmp_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadCompanies();
	});
});


function loadCompanies() {
	var input = $('form#cmp_search_form').serializeArray();
	var url = site_url("companies/get_cmps");
	$('form#cmp_search_form').postForm(url, input, createCompanyTable, '', $("#tbl_cmp_container"), false);
}

function loadCompanyOptions() {
	loadOption('companies/get_options', [], $('.cmp_option'));
}

// Create table list
function createCompanyTable(res) {
	var tbl = $("#tbl_cmp");
	tbl.find("tbody").empty();

	var sno = 0;
	var str = "";
	if (!res.company_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.company_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='cmp_id' value='" + row.cmp_id + "'>";
		str += "<input type='hidden' class='cmp_name' value='" + row.cmp_name + "'><span class='text-danger'>" + row.cmp_name + '</span>';
		str += "</td>";
		str += "<td>" + row.cmp_cmsn + "</td>";
		str += "<td>" + row.cmp_exp + "</td>";

		str += '<td class="text-right">';

		if (row.cmp_status == ACTIVE) {
			str += editBtn('Company', ['.edit_cmp']);
			str += deleteBtn('Company', ['.deactivate_cmp'], 'Deactivate');
		}
		else if (row.cmp_status == INACTIVE)
			str += activateBtn('Company', ['.activate_cmp']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

