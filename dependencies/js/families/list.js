$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#fmly_search_form').initForm();

	// Loading all families. Before doing this you should initialize the search form
	loadFamilies(0);

	$('form#fmly_search_form').on('submit', function (e) {
		e.preventDefault();
		loadFamilies(0);
	});



	$('form#fmly_search_form .dv-sls-from .dropdown-item').on('click', function (e) {
		var sls_from = $(this).attr('data-dt');
		$('form#fmly_search_form  #sls_from').val(sls_from);
	});

	// Detect pagination click
	$("#fmly_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadFamilies(pageno);
	});

	$('#fmly_search_form .stt_option').change(function () {
		$(this).closest('form').find('.dst_option').noOption();
		$(this).closest('form').find('.tlk_option').noOption();
		$(this).closest('form').find('.ars_option').noOption();
		$(this).closest('form').find('.wrd_option').noOption();

		var t = $(this).closest('form').find('.dst_option');
		var d = $(this).val();
		if (!d)
			return;
		loadOption('districts/get_options', {
			dst_fk_states: d
		}, t, t.closest('div'));
	});

	$('#fmly_search_form .dst_option').change(function () {
		$(this).closest('form').find('.tlk_option').noOption();
		$(this).closest('form').find('.ars_option').noOption();
		$(this).closest('form').find('.wrd_option').noOption();

		var t = $(this).closest('form').find('.tlk_option');
		var d = $(this).val();
		if (!d)
			return;
		loadOption('taluks/get_options', {
			tlk_fk_districts: d
		}, t, t.closest('div'));
	});

	$('#fmly_search_form .tlk_option').change(function () {
		$(this).closest('form').find('.ars_option').noOption();
		$(this).closest('form').find('.wrd_option').noOption();
		var t = $(this).closest('form').find('.ars_option');
		var d = $(this).val();
		if (!d)
			return;
		loadOption('areas/get_options', {
			tlk_id: d
		}, t, t.closest('div'));
	});

	$('#fmly_search_form .ars_option').change(function () {
		$(this).closest('form').find('.wrd_option').noOption();
		var t = $(this).closest('form').find('.wrd_option');
		var d = $(this).val();
		if (!d)
			return;
		loadOption('wards/get_options2', {
			ars_id: d
		}, t, t.closest('div'));
	});


	$('#fmly_search_form .cstr_option').change(function () {
		$(this).closest('form').find('.estr_option').noOption();
		var t = $(this).closest('form').find('.estr_option');
		var d = $(this).val();
		if (!d)
			return;
		loadOption('estores/get_options_by_central_store', {
			cstr_id: d
		}, t, t.closest('div'), '', false, true);
	});

});


// Load pagination
function loadFamilies(pagno) {

	// If no task assigned
	if (!tsk_fmly_list)
		return;

	var input = $('form#fmly_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #fmly_per_page').val() })

	$('#familyQuickSearch').val('');
	var url = site_url("families/get_fmlys/") + pagno;
	$('form#fmly_search_form').postForm(url, input, afterFamilyLoad, '', $('#tbl_fmly_container'), false)
}

function loadFamilyOptions() {
	//loadOption('families/get_options', [], $('.fmly_option'));
}


function onFamilyPerpageChanged() {
	loadFamilies(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterFamilyLoad(res, container, input) {
	$("#fmly_pagination").html(res.page_link);
	$("#fmly_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createFamilyTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#fmly_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createFamilyTable(res) {
	var tbl = $("#tbl_fmly");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.family_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.family_data, function (i, row) {
		sno += 1;

		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='fmly_id' value='" + row.fmly_id + "'>"
		str += "<input type='hidden' class='mbr_id' value='" + row.mbr_id + "'>"
		str += "<input type='hidden' class='mbr_name' value='" + row.mbr_name + "'><span class='export' data-exportStyle=''>" + row.mbr_name + '</span>';
		str += "&nbsp;<i title='Show Details' class='cursor-pointer show-fmly-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";

		str += "</td>";

		row.PCHS_AMT = !row.PCHS_AMT ? '' : parseFloat(row.PCHS_AMT);
		str += "<td><span class='export'>" + row.PCHS_AMT + "</span></td>";

		str += "<td><span class='export'></span></td>";// First billed
		str += "<td><span class='export'></span></td>";// Last billed




		str += "<td>";
		str += "<span class='export text-left' style='width:30px;display:inline-block;font-size: 20px;font-weight: bold;'>";
		str += "<span class='fmlm_count'>" + res.fmlm_count[row.mbr_id] + "</span>";// Class .fmlm_count is used for Javascritp purpose to reset count
		str += "</span>";
		str += addBtn('Member', ['.add_fmlm']);
		str += otherBtn('Members', ['.show_fmlm'], 'Show', 'fas fa-search', 'color:#2b769a');
		str += "</td>";


		str += "<td>";
		str += "<div class='export'>";
		if (row.estr_name)
			str += "<div class='text-orange' title='Estore'>" + row.estr_name + "</div> <div style='font-size: 12px;'> <span class='text-info' title='Central Store'>" + row.cstr_code + " - " + row.cstr_name + "</span></div>";
		else
			str += '<i class="fad fa-question" style="color:red;"></i>';
		str += "</div>";
		str += "</td>";

		str += "<td>";
		str += "<div class='export'><div class='text-orange' title='Ward'>" + row.wrd_name + "</div> <div style='font-size: 12px;'> <span class='text-pink' title='Area'>" + row.ars_name + "</span> / <span class='text-info' title='Taluk'>" + row.tlk_name + '</span> / <span class="text-success" title="District">' + row.dst_name + '</span> / <span class="text-danger" title="State">' + row.stt_name + '</span></div></div>';
		str += "</td>";

		str += "<td>";
		str += '<span class="badge bg-danger">' + Object.values(res.cats[row.mbr_id]).join('</span>&nbsp;<span class="badge bg-danger">') + '</span>';
		str += '<span class="export d-none">' + Object.values(res.cats[row.mbr_id]).join(', ') + '</span>';
		str += "</td>";

		str += '<td class="text-right">';

		if (row.mbr_status == ACTIVE) {
			//str += addBtn('Family', ['#add_mbr']);
			if (tsk_fmly_edit)
				str += editBtn('Family', ['.edit_mbr']);
			if (tsk_fmly_deactivate)
				str += deleteBtn('Family', ['.deactivate_mbr'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.mbr_status == INACTIVE && tsk_fmly_activate)
			str += activateBtn('Family', ['.activate_mbr']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}




$(document).on('click', '.show-fmly-details', function () {
	var mbr_id = $(this).closest('tr').find('.mbr_id').val();
	var input = { mbr_id: mbr_id };

	$('#family_details_modal').postForm(site_url("families/get_details"), input, function (res) {
		$('#family_details_modal').modal('show');
		$('#family_details_modal .modal-content').html(res.html);
		// initMap(res.lat, res.log);
	});
});




// function initMap(lat, lng) {
// 	const map = new google.maps.Map(document.getElementById("map"), {
// 		zoom: 5,
// 		center: { lat: lat, lng: lng },
// 		mapTypeId: "terrain",
// 	});

// 	const triangleCoords = [
// 		{ lat: lat + 0.4, lng: lng + 4 },
// 		{ lat: lat - 2.42, lng: lng - 1.15 },
// 		{ lat: lat + 3.435, lng: lng - 2.511 },
// 		{ lat: lat + 0.4, lng: lng + 4.922 },
// 	];


// 	// Construct the polygon.
// 	const bermudaTriangle = new google.maps.Polygon({
// 		paths: triangleCoords,
// 		strokeColor: "#FF0000",
// 		strokeOpacity: 0.8,
// 		strokeWeight: 2,
// 		fillColor: "#FF0000",
// 		fillOpacity: 0.35,
// 	});
// 	bermudaTriangle.setMap(map);
// }