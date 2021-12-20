$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#hsn_search_form').initForm();

	// Loading all hsn_details. Before doing this you should initalize the search form
	loadHsnDetails(0);

	$('form#hsn_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadHsnDetails(0);
	});

	// Detect pagination click
	$("#hsn_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadHsnDetails(pageno);
	});
});


function onHsnPerpageChanged() {
	loadHsnDetails(0)
}


function loadHsnDetailOptions() {
	return;
	//loadOption('hsn_details/get_options', [], $('.hsn_option'));
}

function loadHsnDetails(pagno) {
	var input = $('form#hsn_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #hsn_per_page').val() })

	var url = site_url("hsn_details/get_hsns/") + pagno;
	$('form#hsn_search_form').postForm(url, input, afterHsnDetailsLoad, '', $("#tbl_hsn_container"), false);
}

function afterHsnDetailsLoad(res, container, input) {
	$("#hsn_pagination").html(res.page_link);
	$("#hsn_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createHsnDetailTable(res);
}

// Create table list
function createHsnDetailTable(res) {
	var tbl = $("#tbl_hsn");
	tbl.find("tbody").empty();


	var sno = Number(res.offset);
	var str = "";
	if (!res.hsn_detail_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.hsn_detail_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='hsn_id' value='" + row.hsn_id + "'>";
		str += "<input type='hidden' class='hsn_name' value='" + row.hsn_name + "'><span class='text-danger'>" + row.hsn_name + '</span>';
		str += "</td>";
		str += "<td>" + row.hsn_name_4_digit + "</td>";
		str += "<td style='white-space: break-spaces;'>" + row.hsn_commodity + "</td>";
		str += "<td>" + row.hsn_chapter + "</td>";
		str += "<td>" + row.hsn_sch + "</td>";
		str += "<td>" + row.hsn_gst + "%</td>";

		str += '<td class="text-right">';

		if (row.hsn_status == ACTIVE) {
			str += editBtn('Hsn_detail', ['.edit_hsn']);
			str += deleteBtn('Hsn_detail', ['.deactivate_hsn'], 'Deactivate');
		}
		else if (row.hsn_status == INACTIVE)
			str += activateBtn('Hsn_detail', ['.activate_hsn']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

