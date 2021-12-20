$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#dst_search_form').initForm();

	// Loading all districts. Before doing this you should initalize the search form
	loadDistricts(0);

	$('form#dst_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadDistricts();
	});

	// Detect pagination click
	$("#dst_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadDistricts(pageno);
	});
});



function loadDistrictOptions() {
	loadOption('districts/get_options', [], $('.dst_option'), $('.dst_option').closest('div'));
}




function onDistrictPerpageChanged() {
	loadDistricts(0)
}

// Load pagination
function loadDistricts(pagno) {
	var input = $('form#dst_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #dst_per_page').val() })

	//$('#districtQuickSearch').val('');
	var url = site_url("districts/get_dsts/") + pagno;
	$('form#dst_search_form').postForm(url, input, afterDistrictLoad, '', $('#tbl_dst_container'), false)
}

function afterDistrictLoad(res, container, input) {
	$("#dst_pagination").html(res.page_link);
	$("#dst_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createDistrictTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#dst_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createDistrictTable(res) {
	var tbl = $("#tbl_dst");
	tbl.find("tbody").empty();

	var sno = Number(res.offset);
	var str = "";
	if (!res.district_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.district_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='dst_id' value='" + row.dst_id + "'>";
		str += "<input type='hidden' class='dst_name' value='" + row.dst_name + "'><span class='text-success'>" + row.dst_name + '</span> / <span class="text-danger">' + row.stt_name + '</span>'
		str += "</td>";

		str += '<td class="text-right">';

		if (row.dst_status == ACTIVE) {
			str += editBtn('District', ['.edit_dst']);
			str += deleteBtn('District', ['.deactivate_dst'], 'Deactivate');
		}
		else if (row.dst_status == INACTIVE)
			str += activateBtn('District', ['.activate_dst']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

