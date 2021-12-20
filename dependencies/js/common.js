var maskCount = []; // Ajax mask counter


// Hiding elements on outside mouse click
$(document).mouseup(function (e) {

	// Settings Sidebar Window @ dependencies\js\user_settings.js
	var controlSidebar = $(e.target).hasClass('sr-settings-sidebar') ? true : false;
	var controlSidebarChildren = ($(e.target).closest(".sr-settings-sidebar").length > 0) ? true : false;

	// Toaster
	var toast = $(e.target).hasClass('toast') ? true : false;
	var toastChildren = ($(e.target).closest(".toast").length > 0) ? true : false;

	if (controlSidebar || controlSidebarChildren || toast || toastChildren)
		return;

	else {
		// Hiding Settings Sidebar Window
		if (typeof srControlSidebar._jQueryInterface != 'undefined' && $('.sr-settings-sidebar').is(":visible"))
			srControlSidebar._jQueryInterface.call($(this), 'toggle');
	}
});


$(".modal").draggable({
	handle: ".modal-header"
});
$('.sr-collapse.collapse').on('shown.bs.collapse', function (e) {
	var clicked_anchor = $(document).find("[href='#" + $(e.target).attr('id') + "']")
	clicked_anchor.find(".fa-chevron-down, .fa-chevron-up").toggleClass("fa-chevron-down fa-chevron-up");
});
$('.sr-collapse.collapse').on('hidden.bs.collapse', function (e) {
	var clicked_anchor = $(document).find("[href='#" + $(e.target).attr('id') + "']")
	clicked_anchor.find(".fa-chevron-down, .fa-chevron-up").toggleClass("fa-chevron-down fa-chevron-up");
});
$('.sr-page-nave.nav-tabs a').on('shown.bs.tab', function () {
	var container = $(this).attr('href');
	$(container).find('form').find(':input:not([type="hidden"]):visible').eq(0).focus();
});
$('.sr-page-nave.nav-tabs a').on('click', function () {
	// var container = $(this).attr('href');
	// $(container).find('form').initForm();
});

$('.quick-search').on("keyup", function (event) {
	quickSearch($(this))
});
$(document).on('click', '.sr-tbl-reports tbody tr', function (event) {
	// We have placed the Action buttons also on this row. So click on this should be avoid here.
	// Allowed only the clicks on <td>
	if (event.target.tagName.toLowerCase() === 'td') {
		if ($(this).hasClass('active'))
			$(this).removeClass("active").addClass('active2');
		else if ($(this).hasClass('active2'))
			$(this).removeClass("active2");
		else
			$(this).addClass('active');
	}
});


$(document).on('click', '.dv-perpage .dropdown-item', function () {
	$('.dv-perpage .dropdown-item').removeClass('active');
	$(this).addClass('active');
	var perPage = $(this).attr('data-numRec');
	$(this).closest('.dv-perpage').find('.per-page').val(perPage)
	$(this).closest('.dv-perpage').find('.spn-per-page').html(!perPage ? 'All' : perPage);

	var callback = window[$(this).closest('.dv-perpage').data('callback')];
	if (typeof callback === "function")
		callback();
	else if (callback)
		alert("Function not defined")
});

/**
 * Eg 1:-   $('#box').ignore(".hidden").html();   // Taking all innerHTML of $('#box') except of element $(".hidden").
 * Eg 2:-   $('#box').ignore(".hidden").text();   // Taking all text contents of $('#box') except the texts inside $(".hidden")* 
 * 
 */
$.fn.ignore = function (sel) {
	return this.clone().find(sel || ">*").remove().end();
};

// To get selected values of multi select element.
$.fn.vals = function () {
	var x = 0;
	var arr = [];
	$(this).find(":selected").each(function (i, selected) {
		if ($(selected).val()) arr[x++] = $(selected).val();
	});

	if (arr.length) return arr;
	else return "";
};


$.fn.copyQuery = function (q) {
	$(this).val(q).show().select();
	document.execCommand("copy"); // Copying to clip board
	alert(q);
};

/**
 * url				:	post url
 * input			:	data to be posted
 * success_callback	:	callback will be called if post is success. If no value leave it ''.
 * failure_callback	:	callback will be called if post is failure. If no value leave it ''.
 * mask				:	Show or not the Ajax mask.
 * 								values: 
 * 								false		:	Don't show Ajax Mask
 * 								true		:	Show Ajax Mask over the current element. It is the default value.
 * 								$(object)	:	Show Ajax Mask over the object defined by 'mask'
 * 												Eg: $('#myDiv'), $('form p'), ect
 * reset			:	Reset the form inputs after Ajax success. TRUE/FALSE
 * file				:	Has files to upload. TRUE/FALSE
 */
$.fn.postForm = function (url, input, success_callback, failure_callback, mask, reset, file) {
	var form = $(this);

	mask = ifDef(mask, mask, true);// typeof mask === 'undefined' ? true : mask;
	reset = ifDef(reset, reset, true);// typeof reset === 'undefined' ? true : reset;	
	file = ifDef(file, true, false);// typeof reset === 'undefined' ? true : reset;	

	// If need to show mask
	if (mask) {
		// If value of the mask = true, Showing mask on the current element.
		if (mask === true)
			startMask(form);
		else
			startMask(mask);
	}

	// Removing previous validation messages
	remValidations(form);

	var ajxSetup = {
		type: "POST",
		url: url,
		dataType: 'json', // data type
		data: input, // post data || get data
		success: function (r) {
			// We have disabled the SUBMIT button when submiting a FORM as
			// <form onsubmit="$(this).find(':submit').prop('disabled', true)">
			// So here we reseting Submit Button. 
			form.find(':submit').prop("disabled", false);

			// If was needed to show, Removing it
			if (mask) {
				// If value of the mask = true, Removing the mask from the current element.
				if (mask === true)
					stopMask(form);
				else
					stopMask(mask);
			}

			// If Success
			if (r.status == 1) {

				// If the form is a Modal, Closing it if "self-close" is checked
				// It should be done before initializing the form.
				if (form.closest(".modal").find('.self-close').prop('checked'))
					form.closest(".modal").modal('hide');

				// Initializing the container
				if (reset)
					form.initForm();

				form.find(':input:not([type="hidden"])').eq(0).focus();

				if (typeof success_callback === "function")
					success_callback(r, form, input);
			}
			// If Failed
			else if (r.status == 2) {
				if (typeof failure_callback === "function") {
					failure_callback(r, form, input);
				}
				else {
					showValidationErrors(r.v_error, form);
					showOtherErrors(r.o_error);
				}
			}
			else {
				if (r.indexOf("Logged Out") != -1) {
					window.location.href = site_url('logout');
					return;
				}
			}
		},
		error: function (xhr, resp, text) {

			// if (resp.response != "true") {
			// 	window.location.href = site_url('logout');
			// 	return;
			// }

			// Reseting Submit Button
			form.find(':submit').prop("disabled", false);

			// If was needed to show, Removing it
			if (mask) {
				// If value of the mask = true, Removing the mask from the current element.
				if (mask === true)
					stopMask(form);
				else
					stopMask(mask);
			}

			// Alert about a bug.
			alertError();
		}
	}

	// File Upload
	if (file) {
		ajxSetup.contentType = false;
		ajxSetup.processData = false;
	}


	$.ajax(ajxSetup);
};



// Check/Uncheck checkbox on Ctrl key press.
$(document).on("keydown", '.sr-form input[type="checkbox"]', function (e) {
	e.preventDefault();
	if (e.ctrlKey) {
		$(this).prop('checked', !$(this).prop("checked"));
	}
});


// Check Radio on Ctrl key press.
$(document).on("keydown", '.sr-form input[type="radio"]', function (e) {
	e.preventDefault();
	if (e.ctrlKey) {
		$(this).prop('checked', true);
	}
});


function activateTab(tab) {
	$('.nav-tabs a[href="#' + tab + '"]').tab('show');
};

function alertError(title, text) {
	title = typeof title == 'undefined' ? 'Some errors occured' : title;
	text = typeof text == 'undefined' ? 'Please contact your software consultant' : text;
	Swal.fire({
		type: 'error',//warning, error, success, info, question
		title: title,
		text: text,
		//footer: '<a href="www.billmate.in">www.billmate.in</a>',
		allowOutsideClick: false
	});
}

function remValidations(container) {
	container.find('.form-validation').remove();
	$('.toast').not('.bg-success').hide();
}

function showSuccessToast(msg, delay) {
	delay = ifDef(delay, delay, 5000)
	$(document).Toasts('create', {
		class: 'bg-success',
		title: 'Success',
		icon: 'fas fa-check fa-lg',
		autohide: true,
		delay: delay,
		subtitle: '',
		body: msg
	});
}

function showValidationErrors(data, container) {
	if ($.isEmptyObject(data))
		return;

	$(document).Toasts('create', {
		class: 'bg-danger',
		title: 'Errors',
		icon: 'fas fa-exclamation-triangle fa-lg',
		subtitle: '',
		body: 'Please give the required values correctly'
	})
	$.each(data, function (index, value) {
		//$(value).insertAfter(container.find('[name="' + index + '"]')); // This code will brake the style of Radio.
		container.find('[name="' + index + '"]').parent().append(value);
	});

	// Focusing to first error element
	container.find('.form-validation.error').eq(0).prev('textarea,select,input').focus();
}


function showOtherErrors(err) {
	if (!err)
		return;
	$(document).Toasts('create', {
		class: 'bg-maroon',
		title: 'Errors',
		icon: 'fas fa-exclamation-circle fa-lg',
		subtitle: '',
		body: err
	})
}


/**
 * Show Ajax Mask
 * @param {*} selector: $('#myForm'), $('div'), ect. 
 * 						default value = $(body)
 */
function startMask(selector) {
	// To work this function we need dependencies/plugins/overlay/loadingoverlay.min.js
	// Sources: https://www.jqueryscript.net/loading/Simple-Flexible-Loading-Overlay-Plugin-With-jQuery-loadingoverlay-js.html
	//          https://gasparesganga.com/labs/jquery-loading-overlay/
	selector = typeof selector === 'undefined' ? $('body') : selector;
	maskCount[selector] = typeof maskCount[selector] === 'undefined' ? 0 : maskCount[selector]++;

	// Only one mask allowed
	if (maskCount[selector] == 0)
		selector.LoadingOverlay("show");
}

/**
 * Hide Ajax Mask
 * @param {*} selector: $('#myForm'), $('div'), ect. 
 * 						default value = $(body)
 */
function stopMask(selector) {
	// To work this function we need dependencies/plugins/overlay/loadingoverlay.min.js
	selector = typeof selector === 'undefined' ? $('body') : selector;
	if (typeof maskCount[selector] === 'undefined')
		return;

	// Only one mask allowed
	if (maskCount[selector] <= 0)
		selector.LoadingOverlay("hide");
	else
		maskCount[selector]--;
}

function getToday() {
	var dt = new Date();
	var y = dt.getFullYear();
	var m = dt.getMonth();
	var d = dt.getDate();
	m++;
	if (m < 10)
		m = ("0" + m);
	if (d < 10)
		d = ("0" + d);
	return d + '-' + m + '-' + y;
}

function getTime() {
	var dt = new Date();
	return dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
}


function formatPaginDetails(num_rows, total_rows, offset) {
	var f = 0;
	if (Number(num_rows))
		f = (Number(offset) + 1) + " - " + (Number(offset) + Number(num_rows)) + ' of ' + total_rows;

	var p = '<div class="pgn-cont p-2 ml-lg-n2 clearer" style="margin-top:-11px;">';
	p += '<div class="float-left" style="margin-top:7px;"><i class="fad fa-flower-daffodil fa-2x" style="--fa-primary-color:#32CD32;--fa-secondary-color:#cc4aac;--fa-secondary-opacity: 1.0"></i>&nbsp;&nbsp;</div>';
	p += '<div class="float-left" style="margin-top:5px;"><span class="pgn-num" style="font-size:20px;font-weight:normal;">' + f + ' Records</span></div>';
	p += '</div>';
	return p;
}


// Highlighting Focus Element
$(document).on('focus', '.sr-wraper :input, .sr-wraper-bold :input', function () {
	$('.sr-wraper,.sr-wraper-bold').removeClass('active');
	$(this).closest('.sr-wraper,.sr-wraper-bold').addClass('active');
});

// Removing Highlightings when blur
$(document).on('blur', '.sr-wraper :input, .sr-wraper-bold :input', function () {
	$('.sr-wraper,.sr-wraper-bold').removeClass('active');
});

$('.sr-go-to-tbl').click(function (e) {
	var pagin = $(this).data('pagin');
	var tbl = $(this).data('table');
	goToTbl($(pagin), $(tbl));
});

function goToTbl(pagin, tbl) {
	// If there are pagination links visible
	if (pagin.html().length)
		scrollTo(pagin.closest('.sr-pg-link-container'));
	else
		scrollTo(tbl);
}


function scrollTo(target) {
	if (!target || (typeof target.offset() == 'undefined'))
		return;

	$('html, body').animate({
		scrollTop: target.offset().top - 1
	}, 'slow');
}


/**
 * Extracting values for attributes id & class from 'idClass' array
 * @param {*} idClass : Array of classes & id. 
 * 						Eg: ['.cls1','.cls2','.cls3','#myId']
 * 
 * Return: {class: ' cls1 cls2 cls3', id: 'myId'}
 */
function extractIdClass(idClass) {

	if (typeof idClass == 'undefined' || idClass == '')
		return { class: '', id: '' };
	var cls = '';
	var id = '';

	$.each(idClass, function (index, value) {

		// If first character is '.', it is a class.
		if (value.charAt(0) == ".") {

			// Taking class name after removing '.'
			cls += ' ' + value.substring(1);
		}

		// If first character is '#', it is an id.
		else if (value.charAt(0) == "#") {

			// Taking id name after removing '#'
			// There should be only one id. If more ids provided, taking only the last one.
			id = value.substring(1);
		}
	});

	return { class: cls, id: id };
}

/**
 * 
 * @param {*} title  	: 	Value for title attribute
 * @param {:} idClass 	:	Array of classes & id. 
 * 							Eg: ['.cls1','.cls2','.cls3','#myId']
 * @param {*} prefix 	: 	Prefix text for title. Default is "Add"
 */
function addBtn(title, idClass, prefix) {
	idClass = extractIdClass(idClass);
	prefix = typeof prefix == 'undefined' ? 'Add ' : prefix + ' ';
	var btn = '<span id="' + idClass['id'] + '" class="fa-stack' + idClass['class'] + '" title="' + prefix + title + '">';
	btn += '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-success"></i>';
	btn += '  <i class="fas fa-plus cursor-pointer fa-stack-1x fa-inverse"></i>';
	btn += '</span>';
	return btn;
}

function editBtn(title, idClass, prefix) {
	idClass = extractIdClass(idClass);
	prefix = typeof prefix == 'undefined' ? 'Edit ' : prefix + ' ';
	var btn = '<span id="' + idClass['id'] + '" class="fa-stack' + idClass['class'] + '" title="' + prefix + title + '">';
	btn += '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-info"></i>';
	btn += '  <i class="fas fa-pencil-alt cursor-pointer fa-stack-1x fa-inverse"></i>';
	btn += '</span>';
	return btn;
}

function activateBtn(title, idClass, prefix) {
	idClass = extractIdClass(idClass);
	prefix = typeof prefix == 'undefined' ? 'Activate ' : prefix + ' ';
	var btn = '<span id="' + idClass['id'] + '" class="fa-stack' + idClass['class'] + '" title="' + prefix + title + '">';
	btn += '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-success"></i>';
	btn += '  <i class="fas fa-check cursor-pointer fa-stack-1x fa-inverse"></i>';
	btn += '</span>';
	return btn;
}

function deleteBtn(title, idClass, prefix) {
	idClass = extractIdClass(idClass);
	prefix = typeof prefix == 'undefined' ? 'Delete ' : prefix + ' ';
	var btn = '<span id="' + idClass['id'] + '" class="fa-stack' + idClass['class'] + '" title="' + prefix + title + '">';
	btn += '  <i class="fas fa-circle cursor-pointer fa-stack-2x text-danger"></i>';
	btn += '  <i class="fas fa-trash-alt cursor-pointer fa-stack-1x fa-inverse"></i>';
	btn += '</span>';
	return btn;
}

/**
 * You can use any fontawesome icon here
 * @param {*} title  	: 	Value for title attribute
 * @param {:} idClass 	:	Array of classes & id. 
 * 							Eg: ['.cls1','.cls2','.cls3','#myId']
 * @param {*} prefix 	: 	Prefix text for title.
 * @param {*} otherClass : Fontawesome icon's class name. Eg: fas fa-unlock
 */
function otherBtn(title, idClass, prefix, otherClass, style, iconStyle) {
	idClass = extractIdClass(idClass);
	prefix = typeof prefix == 'undefined' ? '' : prefix + ' ';
	iconStyle = typeof iconStyle == 'undefined' ? '' : iconStyle;
	var btn = '<span id="' + idClass['id'] + '" class="fa-stack' + idClass['class'] + '" title="' + prefix + title + '">';
	btn += '  <i class="fas fa-circle cursor-pointer fa-stack-2x" style="' + style + '"></i>';
	btn += '  <i class="' + otherClass + ' cursor-pointer fa-stack-1x fa-inverse" style="' + iconStyle + '"></i>';
	btn += '</span>';
	return btn;
}



/**
 * Adding values to input elements from array (after ajax responds)
 * @param {*} container : The parent container of input elements.
 * @param {*} data 		: Array of data for input elements
 * 							Eg: 
 * 							[{
 *								emply_date: "2020-01-01", *
 *								emply_fk_employee_category: "2",
 *								emply_id: "4",
 *								emply_name: "Alikkoya",
 *								emply_status: "1",
 *								o_error: "",
 *								status: 1
 *							}]
 */

$.fn.loadFormInputs = function (data) {
	var container = $(this)

	// Make blank all inputs.
	container.find(':input').not('.no-init, :radio, :checkbox').val('');
	container.find(':radio, :checkbox').not('.no-init').prop('checked', false);

	$.each(data, function (name, val) {
		var input = container.find('[name=' + name + '],[name="' + name + '[]"]'); // Selecting all element by name
		// if (!input.length)
		// 	input = container.find('[name="' + name + '[]"]'); // Selecting Multi-Select

		if (!input.length)
			return true;

		var type = input.prop('type');

		// If the input is a date
		if (input.hasClass('sr-date-field-1')) {
			// Date format documentation:   https://github.com/phstc/jquery-dateFormat
			input.val($.format.date(val + ' 00:00:00.546', "dd/MM/yyyy"));
		}
		else if (type == 'checkbox') {
			if (val == 1)
				input.prop('checked', true);
			else
				input.prop('checked', false);
		}
		else if (type == 'radio') {
			container.find('[name=' + name + '][value=' + val + ']').prop('checked', true);
		}
		else if (type == 'text' || type == 'hidden' || type == 'password' || type == 'textarea' || type == 'select-one' || type == 'select-multiple') {
			input.val(val);
		}

	});

	//Reseting Select2 EleBments
	$(this).find('.select2').select2();

	//Reseting Select2 Elements
	$(this).find('.select2bs4').select2({
		theme: 'bootstrap4'
	});
}

$.fn.initForm = function () {
	// Make blank all inputs.
	$(this).find(':input').not('.no-init,:radio, :checkbox,:submit').val('');
	$(this).find(':radio, :checkbox').not('.no-init').prop('checked', false);

	// Applying default values
	$(this).find(':input[data-default]').not('.no-init,:radio, :checkbox,:submit').each(function () {
		$(this).val($(this).data('default'));
	})
	$(this).find(':radio[data-default="true"], :checkbox[data-default="true"]').not('.no-init').prop('checked', true);


	// Applying default values for non-input elements such as <div>,<p>, ect having class 'has-default-value'
	$(this).find('.has-default-value').each(function () {
		$(this).html($(this).data('default'));
	});

	// Removing validation messages
	remValidations($(this));

	var callback = window[$(this).attr('data-afterFormInit')];
	if (typeof callback === "function")
		callback($(this).closest('form'));


	// It should be on last (After adding values of normal <select>)
	//Initialize Select2 Elements
	$(this).find('.select2').not('.no-init').select2()

	//Initialize Select2 Elements
	$(this).find('.select2bs4').not('.no-init').select2({
		theme: 'bootstrap4'
	})
};


$(document).on('click', '.sr-reset-btn', function (e) {
	e.preventDefault();
	// Initializing the from with default values.
	$(this).closest('form').initForm();
});

$.fn.curPage = function () {
	// Make blank all inputs.
	return $(this).find(".page-item.active a").text();
};

// Setting No Option title for <select> elemtns.
$.fn.noOption = function (title) {
	title = typeof title == 'undefined' ? 'No Options' : title;
	$(this).html('<option value="">' + title + '</option>')
};

// $(document).on('click', 'span', function () {
// 	alert('Id is: ' + $(this).attr('class'))
// })

(function ($) {
	"use strict";
	$.fn.openSelect = function () {
		return this.each(function (idx, domEl) {
			if (document.createEvent) {
				var event = document.createEvent("MouseEvents");
				event.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
				domEl.dispatchEvent(event);
			} else if (element.fireEvent) {
				domEl.fireEvent("onmousedown");
			}
		});
	}
}(jQuery));




/**
 * 
 * @param {*} url 		: Post Url
 * @param {*} input 	: Post input
 * @param {*} target	: The target <select> element in which the post response data to be applied 
 * @param {*} mask 		: The element on which the ajax mask to be displayed.
 * @param {*} callback 	: Function wil be called after option loaded
 * @param {*} autoReset : Reset previous value of target after new options added. (Default is true)
 * @param {*} selOne 	: Select the option if there is only one option. (autoReset must false)
 */
function loadOption(url, input, target, mask, callback, autoReset, selOne) {
	input = ifDef(input, input, []); // typeof input == 'undefined' ? [] : input;
	mask = ifDef(mask, mask, target); //typeof mask === 'undefined' ? target : mask;
	autoReset = ifDef(autoReset, autoReset, true); // true/false
	selOne = ifDef(selOne, selOne, true); // true/false

	startMask(mask);
	$.post(site_url(url), input, function (html) {
		stopMask(mask);

		if (target.hasClass('select2')) {
			target.closest('div').find('.select2-selection__rendered').parent().focus().trigger("mousedown");
			target.closest('div').find('.select2-search__field').focus();
		}
		else {
			target.focus();
			// https://subinsb.com/how-to-open-a-select-element-using-jquery/
		}

		target.each(function () {
			// Taking current value
			var sel = $(this).val();

			// Adding new options
			$(this).html(html);

			// Reseting previous value
			if (autoReset && sel)
				$(this).val(sel);
			else if (!autoReset && selOne && ($(this).find("option:not([value=''])").length == 1)) {
				$(this).val($(this).find("option:not([value=''])").attr('value'));
			}

			// Removing empty valued options from SELECT BOXES those are doesn't use <option value="">
			if ($(this).hasClass('sr-no-empty-vals'))
				$(this).find('option[value=""]').remove();


			if (typeof callback === "function")
				callback($(this));
		});
	});

	// It should be on last (After adding values of normal <select>)
	//Initialize Select2 Elements
	$(this).find('.select2').select2()

	//Initialize Select2 Elements
	$(this).find('.select2bs4').select2({
		theme: 'bootstrap4'
	})
};


/**
 * @param {*} input: 	The input array used to ajax post
 * 			 			Eg: [
 *								{"name": "emply_name","value": "Alikkoya CLT"},
 *								{"name": "emply_status","value": "2"},
 *								{"name": "emply_id","value": "4"}
 *							]
 * @param {*} name :	The key of which value you want.
 * 						Eg: If you give "name = emply_id", This function will return 4
 */
function getInputValue(input, name) {
	var val = jQuery.grep(input, function (a) {
		return a['name'] == name;
	});
	if (typeof val[0] == 'undefined')
		return '';
	return val[0]['value'];
};

// Converting form.serializeArray() to object
function objectifyForm(formArray) {
	var returnArray = {};
	for (var i = 0; i < formArray.length; i++) {
		var k = formArray[i]['name'];
		var v = ifDef(formArray[i]['value'], formArray[i]['value'], '');
		returnArray[k] = v;
	}
	return returnArray;
}


function get_no_result_row(tbl, msg) {
	msg = ifDef(msg, msg, 'NO RESULTS FOUND')
	var str = "";
	str += '<tr class="no-result-row"><td colspan="' + tbl.find('thead tr:first-child th').length + '">';
	str += '<h3 class="text-danger text-center">';
	str += '<i class="fas fa-exclamation-triangle fa-lg"></i>';
	str += '<span class="pl-3">' + msg + '</span>';
	str += '</h3>';
	str += '</td></tr>';
	return str;
}

// No data 
function get_no_result_div(msg) {
	msg = ifDef(msg, msg, 'NO RESULTS FOUND'); // noDataToDisplay
	var noResult = '<h3 class="text-danger text-center no-result-row" style="width: 100%;">';
	noResult += '<i class="fas fa-exclamation-triangle fa-lg"></i>';
	noResult += '<span class="pl-3">' + msg + '</span>';
	noResult += '</h3>';
	return noResult;
}




/**
 * 
 * @param {*} url 		: POST URL
 * @param {*} input		: POST input values
 * @param {*} name 		:
 * @param {*} status 	: 1 => Activate, 2 => Deactivate, (Leave '' if you use 'other' parameter)
 * @param {*} callback 	: Callback function after POST success.
 * 						  This function has 2 arguments.
 * 							1. input => Input values used for POST
 * 							2. r => Post response
 * @param {*} other 	: For custome status change.
 * 							Leave 'status' = ''.
 * 							Eg:- 
 * 								var var other = {
 *													status1: 'depricate',
 *													status2: 'depricated',
 *													status3: 'Depricated',
 *													icon: 'warning',
 *													iconHtml: '<i class="fad fa-box-tissue"></i>'
 *												};
 * 								changeStatus(url, input, name, '', callback, other);
 */
function changeStatus(url, input, name, status, callback, other) {
	var status1, status2, status3;

	// To Activate
	if (status == 1) {
		status1 = 'activate';
		status2 = 'activated';
		status3 = 'Activated';
		icon = 'success';
		iconHtml = '<i class="fas fa-check"></i>';
	}

	//To deactivate
	else if (status == 2) {
		status1 = 'deactivate';
		status2 = 'deactivated';
		status3 = 'Deactivated';
		icon = 'warning';
		iconHtml = '<i class="fas fa-trash-alt"></i>';
	}

	else {
		status1 = other.status1;
		status2 = other.status2;
		status3 = other.status3;
		icon = other.icon;
		iconHtml = other.iconHtml;
	}




	Swal.fire({
		title: 'Are you sure?',
		html: "This will " + status1 + ' <b>' + name + '</b>',
		icon: icon,
		iconHtml: iconHtml,
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, ' + status1 + '!'
	}).then((result) => {
		if (result.value) {
			$.post(url, input, function (r) {
				if (r.status == 1) {

					Swal.fire(
						status3 + '!',
						'<b>' + name + '</b> has been ' + status2,
						'success'
					);

					if (typeof callback === "function")
						callback(input, r);
				}
				else {
					var msg = typeof r.o_error == 'undefined' ? 'Couldn\'t ' + status1 : r.o_error;
					Swal.fire('Oops!', msg, 'error');
				}
			}, 'json');
		}
	});
}

// Adding st,nd, th sufixes on Numbers.
function numberToOrdinal(num) {
	if (num === 0) {
		return '0'
	};
	let i = num.toString(), j = i.slice(i.length - 2), k = i.slice(i.length - 1);
	if (j >= 10 && j <= 20) {
		return ('th')
	} else if (j > 20 && j < 100) {
		if (k == 1) {
			return ('st')
		} else if (k == 2) {
			return ('nd')
		} else if (k == 3) {
			return ('rd')
		} else {
			return ('th')
		}
	} else if (j == 1) {
		return ('st')
	} else if (j == 2) {
		return ('nd')
	} else if (j == 3) {
		return ('rd')
	} else {
		return ('th')
	}
}



//Function to convert rgb color to hex format
function rgb2hex(val) {
	rgb = val.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

	if (!rgb)
		return val;

	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
	var hexDigits = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f");
	return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}

(function ($) {
	$.fn.inputFilter = function (inputFilter) {
		return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
			if (inputFilter(this.value)) {
				this.oldValue = this.value;
				this.oldSelectionStart = this.selectionStart;
				this.oldSelectionEnd = this.selectionEnd;
			} else if (this.hasOwnProperty("oldValue")) {
				this.value = this.oldValue;
				this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
			} else {
				this.value = "";
			}
		});
	};
}(jQuery));


function quickSearch(obj) {
	var search = obj.val().replace(/  +/g, ' '); // Replaces all multiple spaces with one
	var target = obj.data('target');
	var callback = window[obj.data('callback')];

	// If no searchs in the search_box, show all rows
	if (!search.length) {
		$(target).find(".quick-search-row").show();
	}
	else {
		var searchArr = search.trim().split(" ");
		var searchCount = searchArr.length;
		$.each($(target).find(".quick-search-row"), function () {
			var row = $(this);
			// var found = false;
			// $.each(searchArr, function (i, searchSTR) {
			// 	// Search for the 'search value' by removing all spaces from the row text.
			// 	if (row.text().trim().replace(/\s/g, "").toLowerCase().indexOf(searchSTR.trim().replace(/\s/g, "").toLowerCase()) !== -1) {
			// 		found = true;
			// 		return false;
			// 	}
			// });

			var foundCount = 0;

			// Converting row text to array after replacing all  multiple spaces with one.
			var rowArr = row.text().replace(/  +/g, ' ').trim().split(" ");
			$.each(searchArr, function (i, searchSTR) {
				$.each(rowArr, function (j, rowhSTR) {
					// Search for the 'search value' by removing all spaces from the row text.
					if (rowhSTR.trim().toLowerCase().indexOf(searchSTR.trim().toLowerCase()) !== -1) {
						foundCount++;
						return false;
					}
				});
			});

			if (foundCount && (foundCount == searchCount)) {
				row.show();
			}
			else
				row.hide();
		});
	}

	if (typeof callback === "function")
		callback(obj);
	else if (callback)
		alert("Function not defined")
}

// function onQuickSearch(search, data) {
// 	var temp = new Array();

// 	// If no values in the search_box, show all rows
// 	if (!search.length)
// 		return data;

// 	$.each(data, function (i, row) {
// 		if (strInArray(search, row))
// 			temp.push(row);
// 	});

// 	return temp;
// }

// Search for a substring in strings which are array values.
function strInArray(str, arr) {
	var find = false;
	$.each(arr, function (k, v) {
		if (v.toString().toLowerCase().includes(str))
			find = true;
	});

	return find;
}


$(".numberOnly").on("keypress keyup blur", function (event) {
	$(this).val($(this).val().replace(/[^0-9\.]/g, ''));
	if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
		event.preventDefault();
	}
});


$(".intOnly").on("keypress keyup blur", function (event) {
	$(this).val($(this).val().replace(/-?\d+[^0-9]+/, ""));
	if ((event.which < 48 || event.which > 57)) {
		event.preventDefault();
	}
});


// Exporting print data to a new window and then prints
function printNewWindow(printHTML, timeout, showPreview) {
	timeout = typeof timeout == 'undefined' ? 0 : timeout;
	var disp_setting = "toolbar=yes,location=no,";
	disp_setting += "directories=yes,menubar=yes,";
	disp_setting += "scrollbars=yes,width=800, height=900, left=100, top=50";
	var docprint = window.open("", "", disp_setting);
	docprint.document.open();
	docprint.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"');
	docprint.document.write('"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
	docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">');
	docprint.document.write('<head><title>My Title</title>');
	docprint.document.write('<style type="text/css">body{ margin:0px;');
	docprint.document.write('font-family:verdana,Arial;color:#000;');
	docprint.document.write('font-family:Verdana, Geneva, sans-serif; font-size:12px;}');
	docprint.document.write('a{color:#000;text-decoration:none;} </style>');

	if (showPreview == 1)
		docprint.document.write('</head><body onLoad="setTimeout(function () {self.print()},' + timeout + ')"><center>');
	else
		docprint.document.write('</head><body onLoad="setTimeout(function () {self.print();self.close()},' + timeout + ')"><center>');

	docprint.document.write(printHTML);
	docprint.document.write('</center></body></html>');
	docprint.document.close();
	//docprint.window.print();
	if (showPreview == 1)
		docprint.focus();
	// else
	// 	docprint.close();
}

// Reading table data to export.
function readTBLData(tbl) {
	var data = new Array();
	data['head'] = [];
	data['body'] = [];

	// Reading heads
	$(tbl).find('thead .export').each(function () {
		//data['head'].push($(this).text());

		data['head'].push({
			text: $(this).text(),
			style: typeof $(this).attr('data-exportStyle') == 'undefined' ? '' : $(this).attr('data-exportStyle')
		});
	});

	// Reading Tbody data
	$(tbl).find('tbody tr:visible:not(.no-export)').each(function (index, tr) {

		// This line of code is needed if there is no data to display
		if (!$(tr).find('.export').length)
			return;

		data['body'][index] = [];
		$(tr).find('.export').each(function () {
			data['body'][index].push({
				text: $(this).text(),
				style: typeof $(this).attr('data-exportStyle') == 'undefined' ? '' : $(this).attr('data-exportStyle')
			});
		});
	});

	return data;
}

$(document).on('click', '.dv-ramp .ramp', function () {
	var tbl = $(this).closest('table');
	var tr = $(this).closest('tr');
	var th = $(this).closest('th');
	var colIndex = tr.find('th').index(th);
	tbl.find('tr th:nth-child(' + (colIndex + 1) + '), tr td:nth-child(' + (colIndex + 1) + ')').remove();
})

// Tables having rowspans (Eg: bills/list)
$(document).on('click', '.dv-ramp .ramp2', function () {
	var col = $(this).closest('th').attr('data-col');
	var tbl = $(this).closest('table');
	var printTbl = tbl.attr('data-print-table'); // A clone of the above table for export (Print/Excel) purpose

	// Removing column from table
	tbl.find('[data-col=' + col + ']').remove();
	$(printTbl).find('[data-col=' + col + ']').remove();
});

function getCrDrAmount(amount) {
	amount = eval(amount);
	if (!amount)
		return amount;
	else if (amount > 0)
		return amount + " Dr";
	else
		return Math.abs(amount) + " Cr";
}

function nl2br(str, is_xhtml) {
	if (typeof str === 'undefined' || str === null) {
		return '';
	}
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function ifDef(v, a, b) {
	return typeof v != 'undefined' ? a : b;
}