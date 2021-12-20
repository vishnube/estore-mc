// This variable is used both in "dependencies\AdminLTE-3.0.2\dist\js\adminlte.js" and "dependencies\js\common.js" to hide the Settings sidebar when its outside mouse click.
var srControlSidebar = new Object();


// Loading settings
loadUserSettings();

$(document).ready(function () {

    // $('#settings-handler').on('click', function () {

    //     // If is expanding the settings window
    //     if (!$('.sr-settings-sidebar').is(":visible")) {

    //         // No need to load if already loaded.
    //         if ($('#tbl-user-settings tbody').html())
    //             return;

    //         loadUserSettings();
    //     }
    // });

    // Toggle user
    $(document).on('click', '.toggle_user', function () {

        var tr = $(this).closest('tr');
        var cst_id = tr.find('.cst_id').val();
        var cst_usertype = tr.find('.cst_usertype').val(); // Curent user
        // var container = $(this).closest('td');
        var container = $(this);

        var input = {
            cst_id: cst_id,
            cst_usertype: cst_usertype
        };
        container.addClass('fa-spin'); // Ajax spinning icon
        container.postForm(site_url("settings/toggle_user"), input, function (res, form, input) {
            //showSuccessToast('User changed successfully');
            tr.find('.cst_usertype').val(res.cst_usertype);
            tr.find('.txt-usrtp').html(res.txt_usrtp);
            container.removeClass('fa-spin');
        }, '', false, false);

    });

    $(document).on('change', '#tbl-user-settings .usrst', function () {
        var cst_id = $(this).closest('tr').find('.cst_id').val();
        var cur_val = '';
        var tr = $(this).closest('tr');
        var container = $(this).closest('td');

        // If Textbox
        if ($(this).hasClass('usrst_txt')) {
            cur_val = $(this).val();
        }

        // Dropdown
        else if ($(this).hasClass('usrst_drop')) {
            cur_val = $(this).val();
        }

        // Radio
        else if ($(this).hasClass('usrst_rad')) {
            cur_val = $(this).closest('td').find('input[type=radio]:checked').val();
        }

        // Checkbox
        else if ($(this).hasClass('usrst_chk')) {
            cur_val = $(this).closest('td').find('input[type=checkbox]').prop('checked') ? 1 : '';
        }

        // Textarea
        else if ($(this).hasClass('usrst_txta')) {
            cur_val = $(this).val();
        }

        else {
            alert("Couldn't identify the input type");
            return;
        }

        var input = {
            cst_id: cst_id,
            cst_val: cur_val
        };

        tr.find('.cst_val').val(cur_val);
        container.postForm(site_url("settings/save_user_settings"), input, afterUserSettingsSave, settingsValError, true, false);
    });

});

function settingsValError(res, form, input) {
    form.find('.form-group').append(res.v_error.cst_val);
}

function afterUserSettingsSave(res, form, input) {

    //showSuccessToast('Settings saved successfully');

    // No need to load.
    // loadUserSettings();
}

function loadUserSettings() {
    var input = $('form#user-settings').serializeArray();
    var url = site_url("settings/get_user_settings");
    $('form#user-settings').postForm(url, input, afterUserSettingsLoad, '', $('#tbl-user-settings'), false);
}

function afterUserSettingsLoad(res, container, input) {

    var tbl = $("#tbl-user-settings");
    tbl.find("tbody").empty();
    var sno = 0;
    var str = "";

    if (!res.settings_data.length) {
        str = get_no_result_row(tbl);
    }

    // Global array variable used to export table data to PDF, Excel, etc @ export.js
    // settingsTblData['exportColHeads'] = ['#', 'Name', 'Value'];
    // settingsTblData['exportColData'] = []; // Initializing. Otherwise on pagination, previous data will be exist there.

    $.each(res.settings_data, function (i, row) {

        // // Global array variable used to export table data to PDF, Excel, etc @ export.js
        // settingsTblData['exportColData'][i] = {
        //     sno: sno,
        //     st_name: row.st_name,
        //     cst_val: (row.st_input == 1) || (row.st_input == 5) ? row.cst_val : row.st_pval[row.cst_val]
        // };

        sno += 1;

        str += "<tr class='quick-search-row'>";
        str += "<td><span class='export'>" + sno + "</span></td>";

        str += "<td>";

        refTable = row.ref_tbl_name ? '&nbsp;&nbsp;<span style="color:#fcbe01;font-size:9px"><i class="fal fa-border-all"></i>&nbsp;' + row.ref_tbl_name + '</span>' : '';

        str += "<input type='hidden' class='cst_id' value='" + row.cst_id + "'>";

        // This element is used to take settings value by using data attributes 'data-reftbl' and 'data-key'
        // Eg: var EXPT_LOGO = $('[data-reftbl="2"][data-cat="2"][data-key="EXPT_LOGO"]').val()
        str += '<input type="hidden" data-reftbl="' + row.st_ref_tbl + '" data-cat="' + row.stct_id + '" data-key="' + row.stky_name + '" class="cst_val" value="' + row.cst_val + '">';

        str += '<input type="hidden" class="st_dval" value="' + row.st_dval + '">';
        str += '<input type="hidden" class="cst_usertype" value="' + row.cst_usertype + '">';
        str += "<input type='hidden' class='st_name' value='" + row.st_name + "'><span class='text-nowrap export'>" + row.st_name + " (#" + row.cst_id + ")</span>" + refTable;
        str += "</td>";

        str += '<td class="text-nowrap">';

        // Textbox
        if (row.st_input == 1) {
            str += '<div class="form-group mb-0">';
            str += '<input type="text" class="form-control form-control-sm usrst usrst_txt" value="' + row.cst_val + '">';
            str += '</div>';
            str += '<span class="export d-none">' + row.cst_val + '</span>';
        }

        // Dropdown
        else if (row.st_input == 2) {
            str += '<div class="form-group">';
            str += '<select class="form-control form-control-sm usrst usrst_drop">';
            var t = '';
            $.each(row.st_pval, function (key, val) {
                if (key == row.cst_val) {
                    str += '<option value="' + key + '" selected="">' + val + '</option>';
                    t = val;
                }
                else
                    str += '<option value="' + key + '">' + val + '</option>';
            });
            str += '</select>';
            str += '</div>';
            str += '<span class="export d-none">' + t + '</span>';
        }

        // Radio Button
        else if (row.st_input == 3) {
            str += '<div class="form-group clearfix  mb-0">';
            var radClass = 'danger';
            var t = '';
            $.each(row.st_pval, function (key, val) {
                radClass = radClass == 'success' ? 'danger' : 'success';
                var checked = '';

                if (key == row.cst_val) {
                    t = val;
                    checked = ' checked = "checked"';
                }

                str += '<div class="icheck-' + radClass + ' d-inline">';
                str += '<input type="radio" class="usrst usrst_rad ' + radClass + '" value="' + key + '" name="usrcst_id_' + row.cst_id + '" id="usrcst_id_' + row.cst_id + key + '" ' + checked + '>';
                str += '<label for="usrcst_id_' + row.cst_id + key + '">' + val + '</label>&nbsp;&nbsp;';
                str += '</div>';
            });
            str += '</div>';
            str += '<span class="export d-none">' + t + '</span>';
        }

        // Checkbox
        else if (row.st_input == 4) {
            str += '<div class="form-group clearfix  mb-0">';
            var t = '';
            $.each(row.st_pval, function (key, val) {
                var checked = '';
                if (key == row.cst_val) {
                    t = val;
                    checked = ' checked = "checked"';
                }
                str += '<div class="icheck-primary d-inline">';
                str += '<input type="checkbox" class="usrst usrst_chk" value="' + key + '" name="usrcst_id_' + row.cst_id + '" id="usrcst_id_' + row.cst_id + key + '"  ' + checked + '>';
                str += '<label for="usrcst_id_' + row.cst_id + key + '">' + val + '</label>&nbsp;';
                str += '</div>';
            });
            str += '</div>';
            str += '<span class="export d-none">' + t + '</span>';
        }

        // Textarea
        else if (row.st_input == 5) {
            str += '<div class="form-group">';
            str += '<textarea class="form-control form-control-sm usrst usrst_txta" rows="2">' + row.cst_val + '</textarea>';
            str += '</div>';
            str += '<span class="export d-none">' + row.cst_val + '</span>';
        }
        str += "</td>";

        str += "<td class='text-nowrap'><span class='text-warning txt-usrtp'>" + res.user_types[row.cst_usertype] + "</span>";
        str += '&nbsp;&nbsp;<i class="toggle_user fad fa-sync-alt cursor-pointer" style="--fa-primary-color: limegreen; --fa-secondary-color: orangered;--fa-secondary-opacity: 1.0" title="Change User"></i>';



        str += "</td>";

        str += "<td class='text-center'><span class='text-teal'>" + res.version_option[row.st_fk_versions] + "</span></td>";


        str += "</tr>";
    });

    tbl.find("tbody").append(str);
}