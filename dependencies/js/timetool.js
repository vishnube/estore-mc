

var TTool_format = 12;
var TToolTarget = '';


$(document).on('change', ".timetool .TTool_format", TToolFormateChange);
$(document).on('keydown', ".timetool .tbl_timetool_input input[type=text]", function (event) {
    TToolIntOnly(event);
});

$(document).on('keyup focus click', ".timetool .tbl_timetool_input input[type=text]", function () {
    var val = $(this).val();
    var min_limit, max_limit = '';
    switch ($(this).prop('class')) {
        case "TTool_hh":
            if (TTool_format == 12) {
                min_limit = 1;
                max_limit = 12;
            }
            else {
                min_limit = 0;
                max_limit = 23;
            }
            break;
        default:
            min_limit = 0;
            max_limit = 59;
    }


    if (parseInt($(this).val(), 10) > max_limit)
        val = max_limit;
    else if (parseInt($(this).val(), 10) < min_limit)
        val = min_limit;

    $(this).val(TToolAddZero(val));
});




$(document).on('click focus', ".timetool .tbl_timetool_input input[type=text]", function () {
    $(this).select();
});

$(document).on('click', ".timetool .TTool_close", function () {
    TToolSetTime();
    $('#time_tool_model').modal('hide');
});



$('.TTool .TTool_target').click(load_TTool);



function load_TTool() {

    // If you want to call any function before time set, declare the function in global scope having name = 'before_timetool'
    if (typeof before_timetool == 'function')
        before_timetool();

    // removing if previous ly loaded.
    $('#time_tool_model').remove();
    var html = getTToolBody();
    $('body').append(html);
    $('#time_tool_model').modal('show');
    TToolTarget = $(this);

    TToolReadTime();
}






function TToolReadTime() {
    // The source time (ie:- $('.TTool .TTool_target').val()) must be in the format : "hh:mm:ss ampm".

    var time = TToolTarget.val();
    var hh, mm, ss, ampm;

    if (time) {
        time = time.split(':');
        hh = time[0];
        mm = time[1];
        var temp = time[2].split(" ");
        ss = temp[0];
        ampm = temp[1];
        ampm = ampm ? ampm.toUpperCase() : '';
        TTool_format = ampm ? 12 : 24;

    }

    // If no time, setting current time.
    else {
        var d = new Date();
        hh = d.getHours();
        mm = d.getMinutes();
        ss = d.getSeconds();

        //Converting to 12hr format.
        TTool_format = 12; // sETTING 12HR FORMAT.
        ampm = (hh >= 12) ? 'PM' : 'AM';
        hh = (hh > 12) ? (hh - 12) : hh;
        if (!hh)
            hh = 12; // if hh is zero it will be 12 AM.
    }

    if (TTool_format == 12) {
        TTool_format = 12;
        $('.timetool .TTool_meridiem[value="' + ampm + '"]').prop('checked', true);
        $('.timetool').find('.TR_TTool_meridiem').show();
    }
    else {
        $('.timetool').find('.TR_TTool_meridiem').hide();
    }

    $('.timetool .TTool_format[value="' + TTool_format + '"]').prop('checked', true);
    $('.timetool .TTool_hh').val(hh);
    $('.timetool .TTool_mm').val(mm);
    $('.timetool .TTool_ss').val(ss);
}

function TToolSetTime() {
    var ampm = (TTool_format == 12) ? ' ' + $('.timetool .TTool_meridiem:checked').val() : '';
    var hh = $('.timetool .TTool_hh').val();
    var mm = $('.timetool .TTool_mm').val();
    var ss = $('.timetool .TTool_ss').val();
    var time = hh + ':' + mm + ':' + ss + ampm;
    TToolTarget.val(time);
    TToolTarget.removeClass('clock')
}


function convert_meridiem() {
    var hr = eval($('.timetool .TTool_hh').val());

    if (TTool_format == 12) {
        if (hr < 12) {
            $('.timetool .TTool_meridiem[value="AM"]').prop('checked', true);
            if (hr == 0)
                hr = 12;
        }
        else {
            $('.timetool .TTool_meridiem[value="PM"]').prop('checked', true);
            if (hr > 12)
                hr -= 12;
        }
    }
    else if (TTool_format == 24) {
        if ($('.timetool .TTool_meridiem:checked').val() == 'AM') {
            if (hr == 12)
                hr = 0;
        }
        else if ($('.timetool .TTool_meridiem:checked').val() == 'PM') {
            if (hr < 12)
                hr += 12;
        }
    }

    $('.timetool .TTool_hh').val(TToolAddZero(hr));
}

function TToolAddZero(val) {
    var time = ('0' + val).slice(-2);
    return time;
}


function getTToolBody() {
    var html = '<div class="modal fade" tabindex="-1" role="dialog" id="time_tool_model" data-backdrop="static">';
    html += '       <div class="modal-dialog modal-sm" role="document">';
    html += '        <div class="modal-content timetool">';
    html += '            <div class="modal-header bg-danger">';
    html += '                <h4 class="modal-title" id="gridSystemModalLabel">SET TIME</h4>';
    html += '                <button title="Close (Esc)" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

    html += '            </div>';
    html += '       <table class="tbl_timetool">';
    html += '        <tbody>';
    html += '            <tr>';
    html += '                <th style="width:100px;">Format</th>';
    html += '                <td>';
    html += '                    <input type="radio" name="TTool_format" value="12" checked="" class="TTool_format"> &nbsp;12 hrs.';
    html += '                    <input type="radio" name="TTool_format" value="24" class="TTool_format"> &nbsp;24 hrs.';
    html += '                </td>';
    html += '            </tr>';
    html += '            <tr class="TR_TTool_meridiem">';
    html += '                <th>Meridiem</th>';
    html += '                <td>';
    html += '                    <input type="radio" name="TTool_meridiem" value="AM" checked="" class="TTool_meridiem"> &nbsp;AM';
    html += '                    <input type="radio" name="TTool_meridiem" value="PM" class="TTool_meridiem"> &nbsp;PM';
    html += '                </td>';
    html += '            </tr>';
    html += '            <tr>';
    html += '                <td colspan="2">';
    html += '                    <table class="tbl_timetool_input">';
    html += '                        <tbody>';
    html += '                            <tr>';
    html += '                                <th>Hours</th>';
    html += '                                <th>Minuts</th>';
    html += '                                <th>Seconds</th>';
    html += '                            </tr>';
    html += '                            <tr>';
    html += '                                <th><input type="text" class="TTool_hh" value="" style="width:80px;"></th>';
    html += '                                <th><input type="text" class="TTool_mm" value="" style="width:80px;"></th>';
    html += '                                <th><input type="text" class="TTool_ss" value="" style="width:80px;"></th>';
    html += '                            </tr>';
    html += '                        </tbody>';
    html += '                    </table>';
    html += '                </td>';
    html += '            </tr>';

    html += '            <tr>';
    html += '                <td colspan="2">';
    html += '                   <div class="TTool_close btn btn-danger">Set Time</div>';
    html += '                </td>';
    html += '            </tr>';

    html += '        </tbody>';
    html += '       </table>';
    html += '        </div>';
    html += '    </div>';
    html += '</div>';
    return html;
}



//Allows only integer types

function TToolIntOnly(event) {
    // Allow: backspace, delete, tab, escape, enter and .
    var allowed = $.inArray(event.keyCode, [46, 8, 9, 27, 13, 190]);
    if (allowed !== -1 ||
        // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
        // Allow: home, end, left, up, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    else {
        // Ensure that it is not a number and stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
        }
    }
}




function TToolFormateChange() {

    TTool_format = $(".timetool .TTool_format:checked").val();

    if (TTool_format == 12) {
        $('.timetool').find('.TR_TTool_meridiem').show();
    }
    else {
        $('.timetool').find('.TR_TTool_meridiem').hide();
    }

    convert_meridiem();
}




