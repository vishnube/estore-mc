$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#tlk_search_form').initForm();

	// Loading all taluks. Before doing this you should initalize the search form
	loadTaluks(0);

	$('form#tlk_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadTaluks();
	});



	// Detect pagination click
	$("#tlk_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadTaluks(pageno);
	});
});


function loadTalukOptions() {
	loadOption('taluks/get_options', [], $('.tlk_option'));
}



function onTalukPerpageChanged() {
	loadTaluks(0)
}

// Load pagination
function loadTaluks(pagno) {
	var input = $('form#tlk_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #tlk_per_page').val() })

	//$('#talukQuickSearch').val('');
	var url = site_url("taluks/get_tlks/") + pagno;
	$('form#tlk_search_form').postForm(url, input, afterTalukLoad, '', $('#tbl_tlk_container'), false)
}

function afterTalukLoad(res, container, input) {
	$("#tlk_pagination").html(res.page_link);
	$("#tlk_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createTalukTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#tlk_pagination"), $('.sr-tbl-cont'));
}


// Create table list
function createTalukTable(res) {
	var tbl = $("#tbl_tlk");
	tbl.find("tbody").empty();

	var sno = Number(res.offset);
	var str = "";
	if (!res.taluk_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.taluk_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='tlk_id' value='" + row.tlk_id + "'>";
		str += "<input type='hidden' class='tlk_name' value='" + row.tlk_name + "'><span class='text-info'>" + row.tlk_name + '</span> / <span class="text-success">' + row.dst_name + '</span> / <span class="text-danger">' + row.stt_name + '</span>';
		str += "</td>";

		str += '<td class="text-right">';

		if (row.tlk_status == ACTIVE) {
			str += editBtn('Taluk', ['.edit_tlk']);
			str += deleteBtn('Taluk', ['.deactivate_tlk'], 'Deactivate');
		}
		else if (row.tlk_status == INACTIVE)
			str += activateBtn('Taluk', ['.activate_tlk']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

