$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#st_search_form').initForm();

	// Loading all settings. Before doing this you should initialize the search form
	loadSettings(0);

	$('form#st_search_form').on('submit', function (e) {
		e.preventDefault();
		loadSettings(0);
	});

	// Detect pagination click
	$("#st_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadSettings(pageno);
	});


	$(document).on('click', '.show-st-details', function () {
		var st_id = $(this).closest('tr').find('.st_id').val();
		var input = { st_id: st_id };

		$('#settings_details_modal').postForm(site_url("settings/get_details"), input, function (res) {
			$('#settings_details_modal').modal('show');
			$('#settings_details_modal .st_desc').html(res.st_desc);
			$('#settings_details_modal .v_name').html(res.v_name);
			$('#settings_details_modal .st_ref_tbl').html(res.st_ref_tbl);
			$('#settings_details_modal .category').html(res.category);
			$('#settings_details_modal .st_validation').html(res.st_validation);


			$('#settings_details_modal .st_date').html(res.st_date);
			// $('#settings_details_modal .st_dusertype').html(res.st_dusertype);

			// // Textbox || Textarea
			// if (res.st_input == 1 || res.st_input == 5)
			// 	$('#settings_details_modal .st_dval').html(res.st_dval);

			// // Dropdown, Radio, Checkbox
			// else if (res.st_input == 2 || res.st_input == 3 || res.st_input == 4)
			// 	$('#settings_details_modal .st_dval').html(res.st_pval[res.st_dval]);
		});
	});

});


// Load pagination
function loadSettings(pagno) {
	var input = $('form#st_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })

	var url = site_url("settings/get_sts/") + pagno;

	$('form#st_search_form').postForm(url, input, afterSettingsLoad, '', $('#tbl_st_container'), false)
}

function loadSettingsOptions() {
	loadOption('settings/get_options', [], $('.st_option'));
}


/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterSettingsLoad(res, container, input) {
	$("#st_pagination").html(res.page_link);
	$("#st_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createSettingsTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#st-list-tab').hasClass('active'))
		goToTbl($("#st_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createSettingsTable(res) {
	var tbl = $("#tbl_st");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.settings_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.settings_data, function (i, row) {
		sno += 1;
		var reftbl = row.st_ref_tbl ? res.ref_tables[row.st_ref_tbl] : '';

		str += "<tr class='quick-search-row'>";
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td><span class='export'>" + reftbl + "</span></td>";
		str += "<td><span class='export'>" + row.stct_name + "</span></td>";
		str += "<td><span class='export'>" + row.stky_name + "</span></td>";

		str += "<td>";
		str += "<input type='hidden' class='st_id' value='" + row.st_id + "'>"
		str += "<input type='hidden' class='st_name' value='" + row.st_name + "'><span class='export'>" + row.st_name + " <span class='sr-bknote'>(#" + row.st_id + ")</span></span>";
		str += "&nbsp;<i title='Show Details' class='cursor-pointer show-st-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";
		str += "</td>";


		// Textbox || Textarea
		if (row.st_input == 1 || row.st_input == 5)
			str += "<td><span class='export'>" + row.cst_val + "</span></td>";

		// Dropdown, Radio
		else if (row.st_input == 2 || row.st_input == 3) {
			var stval = row.st_pval[row.cst_val]
			stval = (typeof stval == 'undefined') ? '' : stval;
			str += "<td><span class='export'>" + stval + "</span></td>";
		}

		// Checkbox
		else if (row.st_input == 2 || row.st_input == 3 || row.st_input == 4) {
			var stval = row.st_pval[row.cst_val]
			stval = (typeof stval == 'undefined') ? '<i class="fad fa-times" style="--fa-primary-color:red;--fa-secondary-color:red;"></i>' : stval;
			str += "<td><span class='export'>" + stval + "</span></td>";
		}

		str += "<td>"

		// Textbox
		if (row.st_input == 1) {
			str += '<div class="form-group">';
			str += '<input type="text" class="form-control form-control-sm" value="' + row.st_dval + '">';
			str += '</div>';
		}

		// Dropdown
		else if (row.st_input == 2) {
			str += '<div class="form-group">';
			str += '<select class="form-control form-control-sm">';
			$.each(row.st_pval, function (key, val) {
				if (key == row.st_dval)
					str += '<option value="' + key + '" selected="">' + val + '</option>';
				else
					str += '<option value="' + key + '">' + val + '</option>';
			});
			str += '</select>';
			str += '</div>';
		}

		// Radio Button
		else if (row.st_input == 3) {
			str += '<div class="form-group clearfix">';
			$.each(row.st_pval, function (key, val) {
				var checked = key == row.st_dval ? checked = "checked" : '';

				str += '<div class="icheck-danger d-inline">';
				str += '<input type="radio" value="' + key + '" name="st_id_' + row.st_id + '" id="st_id_' + row.st_id + key + '" ' + checked + '>';
				str += '<label for="st_id_' + row.st_id + key + '">' + val + '</label>&nbsp;';
				str += '</div>';
			});
			str += '</div>';
		}

		// Checkbox
		else if (row.st_input == 4) {
			str += '<div class="form-group clearfix">';
			$.each(row.st_pval, function (key, val) {
				var checked = key == row.st_dval ? checked = "checked" : '';
				str += '<div class="icheck-primary d-inline">';
				str += '<input type="checkbox" value="' + key + '" name="st_id_' + row.st_id + '" id="st_id_' + row.st_id + key + '"  ' + checked + '>';
				str += '<label for="st_id_' + row.st_id + key + '">' + key + ' <i class="fad fa-angle-right"  style="--fa-primary-color: limegreen; --fa-secondary-color: orangered;"></i> ' + val + '</label>&nbsp;';
				str += '</div>';
			});
			str += '</div>';
		}

		// Textarea
		else if (row.st_input == 5) {
			str += '<div class="form-group">';
			str += '<textarea class="form-control form-control-sm" rows="2">' + row.st_dval + '</textarea>';
			str += '</div>';

		}
		str += "</td>";

		str += "<td>" + res.user_types[row.cst_usertype] + "</td>";
		str += "<td>" + res.user_types[row.st_dusertype] + "</td>";
		//str += "<td>" + res.version_option[row.st_fk_versions] + "</td>";

		str += "<td>" + row.st_sort + "</td>";

		str += '<td class="text-right">';

		if (row.st_status == ACTIVE) {
			str += editBtn('Settings', ['.edit_st']);
			str += deleteBtn('Settings', ['.deactivate_st'], 'Deactivate');
		}
		else if (row.st_status == INACTIVE)
			str += activateBtn('Settings', ['.activate_st']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

