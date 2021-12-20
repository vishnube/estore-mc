$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#order_search_form').initForm();

	// Loading all orders. Before doing this you should initialize the search form
	loadOrders(0);

	$('form#order_search_form').on('submit', function (e) {
		e.preventDefault();
		loadOrders(0);
	});

	// Detect pagination click
	$("#odr_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadOrders(pageno);
	});
});


// Load pagination
function loadOrders(pagno) {

	// If no task assigned
	if (!tsk_odr_list)
		return;

	// Deleting data in stock table (Under Stock Tab)
	// emptyStockTable(); // @ orders\stock.js

	// Loading Stock (Under Stock Tab)
	loadStock(0);// @ orders\stock.js

	var input = $('form#order_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "bls_bill_type", value: 'sls_odr' })
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #odr_per_page').val() })

	$('#tbl_odr #odr-all-check').prop('checked', false)

	$('#orderQuickSearch').val('');
	var url = site_url("orders/get_odrs/") + pagno;
	$('form#order_search_form').postForm(url, input, afterOrderLoad, '', $('#tbl_odr_container'), false)
}

// function loadOrderOptions() {
// 	loadOption('orders/get_options', [], $('.odr_option'));
// }


function onOrderPerpageChanged() {
	loadOrders(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterOrderLoad(res, container, input) {
	$("#odr_pagination").html(res.page_link);
	$("#odr_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createOrderTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#odr-list-tab').hasClass('active'))
		goToTbl($("#odr_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createOrderTable(res) {
	var tbl = $("#tbl_odr");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.order_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.order_data, function (i, row) {
		sno += 1;

		var billNo = row.bill_no.blb_prefix + row.bill_no.bln_name + row.bill_no.blb_sufix;

		str += "<tr class='quick-search-row order-row tr-top' id='odr-row-" + row.bls_id + "'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td>";
		str += '	<div class="form-group clearfix m-0">';
		str += '		<div class="icheck-danger d-inline">';
		str += '			<input type="checkbox" class="row-check odr-check" id="odr-check-' + sno + '">';
		str += '			<label for="odr-check-' + sno + '"></label>';
		str += '		</div>';
		str += '		<span class="export" data-exportStyle="text-align: left;">' + sno + '</span>';
		str += '	</div>';
		str += '</td>';
		str += "<td>";
		str += "<input type='hidden' class='bls_id' value='" + row.bls_id + "'>"
		str += "<input type='hidden' class='bls_ref_key' value='" + row.bls_ref_key + "'>"
		str += "<input type='hidden' class='cur_status' value='" + row.ost_status + "'>"
		str += "<input type='hidden' class='billNo' value='" + billNo + "'>";
		str += "<span class='export' data-exportStyle='font-weight: bold; color:#f00; text-align: right;'>" + billNo + '</span>';

		// PENDING, PICKED, BILLED, PACKED, ESTORE, DELIVERED, PAID @ orders\index.php
		if (row.ost_status == PENDING)
			str += '<i class="fas fa-arrow-alt-circle-up handler show-odr text-primary ml-2" title="Show Order"></i>';
		else if (row.ost_status == PICKED)
			str += '<i class="fas fa-arrow-alt-circle-up handler make_bill text-success ml-2" title="Do Bill"></i>';
		else
			str += '<i class="fas fa-arrow-alt-circle-up handler show_bill text-danger ml-2" title="Show Bill"></i>';

		if (parseFloat(row.bls_ref_key))
			str += '<i class="fas fa-archive handler show-parent text-info ml-2" title="Show Parent Order"></i>';

		str += "</td>";


		str += "<td><span class='export'>" + row.bls_date + "</span></td>";
		str += "<td><span class='export'>" + row.from + "</span></td>";
		str += "<td><span class='export'>" + row.ward + "</span></td>";
		str += "<td><span class='export'>" + row.to + "</span></td>";
		str += "<td><span class='export'>" + row.prd_count + "</span></td>";
		str += "<td><span class='export'>" + row.ost_status_txt + "</span></td>";

		estrName = row.estore_data.estr_name ? row.estore_data.estr_name : '';
		estrMob1 = row.estore_data.estr_mob1 ? row.estore_data.estr_mob1 : '';
		estrMob2 = row.estore_data.estr_mob2 ? row.estore_data.estr_mob2 : '';
		sep = estrMob1 && estrMob2 ? ', ' : '';

		str += "<td>";
		str += '<span class="text-success" title ="Estore">' + estrName + '</span>';
		if (estrMob1)
			str += ' <a href="tel:' + estrMob1 + '"><i class="fad fa-phone-alt text-warning" style="font-size: 12px;"></i>' + estrMob1 + '</a>';
		if (estrMob2)
			str += ' <a href="tel:' + estrMob2 + '"><i class="fad fa-phone-alt text-warning" style="font-size: 12px;"></i>' + estrMob2 + '</a>';
		str += "<span class='d-none export'>" + estrName + ' (' + estrMob1 + sep + estrMob2 + ")</span>";
		str += "</td>";

		// $phone1 = " CONCAT('<a href=\"tel:',estr_mob1,'"><i class=\"fad fa-phone-alt\" style=\"font-size: 12px;\"></i> ',estr_mob1,'</a>') ";
		// $name = " CONCAT(') AS estr_str";
		// $q = "SELECT $name, $phone FROM families,wards,estores WHERE fmly_id = $fmly_id AND wrd_id = fmly_fk_wards AND estr_id = wrd_fk_estores";

		str += '<td class="text-right">';
		str += otherBtn('Log', ['.show-log'], 'Show', 'fas fa-angle-down toggler', 'color:#e9c818');

		// if (row.odr_status == ACTIVE) {
		// 	//str += addBtn('Order', ['#add_odr']);
		// 	if (tsk_odr_edit)
		// 		str += editBtn('Order', ['.edit_odr']);
		// 	if (tsk_odr_deactivate)
		// 		str += deleteBtn('Order', ['.deactivate_odr'], 'Deactivate');
		// }
		// else if (row.odr_status == INACTIVE && tsk_odr_activate)
		// 	str += activateBtn('Order', ['.activate_odr']);

		str += "</td>";
		str += "</tr>";

		// Showing order processing stages
		var found = false;
		var usr = '---';
		var tm = '--- ---';
		var icon = '';
		var tpCls = '';
		str += "<tr class='tr-bottom no-export' style='display:none;'>";
		str += "	<td colspan='10'>";
		$.each(res.ost_option_vals, function (opt, val) {
			found = false;
			usr = '---';
			tm = '--- ---';
			tpCls = 'text-danger';
			icon = 'fa-times';

			$.each(row.order_flow, function (f, flow) {
				if (flow.ost_status == opt) {
					found = true;
					usr = flow.mbr_name;
					tm = flow.ost_date;
					tpCls = 'text-success';
					icon = 'fa-check';
					return false; // breaks
				}
			});

			str += '		<div class="dv-logger">';
			str += '			<div class="tp ' + tpCls + '"><i class="fas ' + icon + '"></i>&nbsp;&nbsp; ' + val + '</div>';
			str += '			<div class="bt">';
			str += '				<div class="usr">' + usr + '</div>';
			str += '				<div class="tm">' + tm + '</div>';
			str += '			</div>';
			str += '		</div>';
		});

		str += "	</td>";
		str += "</tr>";

	});
	tbl.find("tbody").append(str);
}

$(document).on('click', '.show-odr', function () {
	var bls_id = $(this).closest('.order-row').find('.bls_id').val();
	var input = { bls_id: bls_id };

	$('#bill_details_modal').postForm(site_url("orders/get_details"), input, function (res) {
		$('#bill_details_modal').modal('show');
		$('#bill_details_modal .modal-content').html(res.html);
	});
});

$(document).on('click', '.show-parent', function () {
	var bls_id = $(this).closest('.order-row').find('.bls_ref_key').val();
	var input = { bls_id: bls_id };

	$('#bill_details_modal').postForm(site_url("orders/get_details"), input, function (res) {
		$('#bill_details_modal').modal('show');
		$('#bill_details_modal .modal-content').html(res.html);
	});
});

$(document).on('click', '.show_bill', function () {
	var ref_key = $(this).closest('.order-row').find('.bls_id').val();
	var input = { ref_key: ref_key };
	$('#show_bill_modal').postForm(site_url("orders/show_bill"), input, function (res) {
		$('#show_bill_modal').modal('show');
		$('#show_bill_modal .modal-content').html(res.html);
		$('#show_bill_modal #PDFdownloadLink').attr('href', site_url('bills/download/' + res.bls_id));
		$('#show_bill_modal .print-bill').attr('data-bls_id', res.bls_id);
	});
});

$(document).on('click', '#show_bill_modal .print-bill', function () {
	var input = { bls_id: $(this).attr('data-bls_id') }
	$('#show_bill_modal .modal-body').postForm(site_url("bills/print_bill"), input, function (res) {
		//printNewWindow(res.html, 1000, false);
		$('body').append('<div id="bill-print-container" class="sr-printable-common" style="width: 100%;">' + res.html + '</div>')
		setTimeout(function () {
			window.print();
			$('#bill-print-container').remove();
		}, 10);
	});
});




$(document).on('click', '.show-log', function () {
	if ($(this).find('.toggler').hasClass('fa-angle-down')) {
		$(this).find('.toggler').removeClass('fa-angle-down').addClass('fa-angle-up')
		$(this).closest('tr').nextAll('tr').eq(0).show();
	}
	else {
		$(this).find('.toggler').removeClass('fa-angle-up').addClass('fa-angle-down')
		$(this).closest('tr').nextAll('tr').eq(0).hide();
	}
});



$(document).on('click', '.mover', function () {
	if (!$('#tbl_odr .odr-check:checked').length) {
		Swal.fire('Oops!', "No orders are selected", 'error');
		return;
	}

	var levelId = $(this).attr('data-level-id');
	var levelName = $(this).attr('data-level-name');

	var prevLevel = '';
	if (levelId == PICKED)
		prevLevel = PENDING;

	else if (levelId == PACKED)
		prevLevel = BILLED;

	else if (levelId == ESTORE)
		prevLevel = PACKED;

	else if (levelId == DELIVERED)
		prevLevel = ESTORE;

	else if (levelId == PAID)
		prevLevel = DELIVERED;

	var flag = true;

	var levels = [];
	levels[PENDING] = 'PENDING';
	levels[PICKED] = 'PICKED';
	levels[BILLED] = 'BILLED';
	levels[PACKED] = 'PACKED';
	levels[ESTORE] = 'ESTORE';
	levels[DELIVERED] = 'DELIVERED';
	levels[PAID] = 'PAID';


	$('#tbl_odr .odr-check:checked').each(function () {
		var curLvel = $(this).closest('.order-row').find('.cur_status').val();
		if (curLvel != prevLevel)
			flag = false;
	})

	if (!flag) {
		var msg = "The current level of orders you need to move to <span class='text-danger'>" + levelName + "</span> should be <span class='text-danger'>" + levels[prevLevel] + "</span>";
		Swal.fire('Oops!', msg, 'error');
		return;
	}



	Swal.fire({
		title: 'Are you sure?',
		html: "This will move the selected orders to <b>" + levelName + "</b> processing level",
		icon: 'success',
		iconHtml: '<i class="fas fa-check"></i>',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, move!'
	}).then((result) => {
		if (result.value) {
			var orders = [];
			$('#tbl_odr .odr-check:checked').each(function () {
				var bls_id = $(this).closest('.order-row').find('.bls_id').val();
				orders.push(bls_id)
			})

			var input = {
				moveTo: levelId,
				orders: orders
			}
			$('form#order_search_form').postForm(site_url("orders/move_to_next"), input, function (r) {
				if (r.status == 1) {
					loadOrders(0);
					Swal.fire(
						'Congratulations!',
						'All selected order are moved to <b>' + levelName + '</b> level',
						'success'
					);
				}
				else {
					var msg = typeof r.o_error == 'undefined' ? 'Couldn\'t move' : r.o_error;
					Swal.fire('Oops!', msg, 'error');
				}
			}, '', $('#tbl_odr_container'), false)
		}
	});
});



$('#order_search_form #cstr_id').change(function () {
	var t = $(this).closest('form').find('#fmly_id');
	t.noOption();
	var d = $(this).val();
	if (!d) return;
	loadOption('bills/get_cstr_family_options', {
		mbr_id: d
	}, t, t.closest('div'));
});

$('#order_search_form .prd_option').change(function () {
	var t = $(this).closest('form').find('.pdbch_option');
	t.noOption();
	var d = $(this).val();
	if (!d) return;
	loadOption('product_batches/get_batches_by_prd_ids', {
		prd_ids: d
	}, t, t.closest('div'));
});

$(document).on('change', '#tbl_odr #odr-all-check', function () {
	$('#tbl_odr .odr-check').prop('checked', $(this).prop('checked'))
});

$(document).on('change', '#tbl_odr .odr-check', function () {
	if ($('#tbl_odr .odr-check:not(:checked)').length)
		$('#tbl_odr #odr-all-check').prop('checked', false)
	else
		$('#tbl_odr #odr-all-check').prop('checked', true)
});

// $(document).on('change', '.all-checker', function () {
// 	$(this).closest('table').find('.row-check').prop('checked', $(this).prop('checked'))
// });

// $(document).on('change', '.row-check', function () {
// 	if ($(this).closest('table').find('.row-check:not(:checked)').length)
// 		$(this).closest('table').find('.all-checker').prop('checked', false)
// 	else
// 		$(this).closest('table').find('.all-checker').prop('checked', true)
// });


// $(document).on('mouseenter', '.order-row', function (event) {
// 	//$('.dv-logger').hide();
// 	$(this).find('.dv-logger').show();
// 	console.log()
// 	var width = $(this).width()
// 	var height = $(this).find('.dv-logger').height()
// 	console.log("Width: " + width + ", H: " + height)
// 	$(this).find('.dv-logger').css('bottom', "-" + height + "px")
// 	$(this).find('.dv-logger').css('width', width + "px")
// })

// $(document).on('mouseleave', '.dv-logger', function () {
// 	// $(this).hide();
// 	$('.dv-logger').hide()
// });