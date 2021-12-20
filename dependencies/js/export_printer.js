$(document).on('click', '.export-PRINT', function () {
    var tbl = $(this).data('target');
    var tblData = readTBLData(tbl);  // @ common.js

    if (!tblData['body'].length) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No data to export!'
        });
        return false;
    }

    // '#printData' @ views/print_reports.php
    // '.print-data' is @ views/print_reports.php
    $('#printData').show();

    var leftMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="M_LFT"]').val());
    var topMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="M_TOP"]').val());
    var showHeader = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_HDR"]').val(); // 1 => show, 2 => Hide
    var showLogo = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_LOGO"]').val(); // 1 => show, 2 => Hide
    var showCanvas = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_CANVAS"]').val(); // 1 => show, 2 => Hide
    var showTitle1 = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_1"]').val(); // 1 => show, 2 => Hide
    var showTitle2 = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_2"]').val(); // 1 => show, 2 => Hide
    var showTitle3 = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_3"]').val(); // 1 => show, 2 => Hide
    var title1TopMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_1_M_TOP"]').val());
    var title2TopMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_2_M_TOP"]').val());
    var title3TopMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_3_M_TOP"]').val());
    var title1Color = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_1_COLOR"]').val()
    var showBorder = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_BDR"]').val(); // 1 => show, 2 => Hide
    var showPreview = $('[data-reftbl=""][data-cat="2"][data-key="PRNT_PRV"]').val();
    var delay = $('[data-reftbl=""][data-cat="2"][data-key="PRNT_DLY"]').val();

    $('#printData .print-data').css('margin-left', leftMargin);
    $('#printData .print-data').css('margin-top', topMargin);

    $('#printData .print-data .rep-header').hide();
    $('#printData .print-data .rep-logo').hide();
    $('#printData .print-data .rep-canvas').hide();
    $('#printData .print-data .title1').hide();
    $('#printData .print-data .title1').html('');
    $('#printData .print-data .title2').hide();
    $('#printData .print-data .title2').html('');
    $('#printData .print-data .title3').hide();
    $('#printData .print-data .title3').html('');
    $('#printData .print-data .rep-border').hide();


    if (showHeader == 1) {
        $('#printData .print-data .rep-header').show();
        if (showLogo == 1)
            $('#printData .print-data .rep-logo').show();
        if (showCanvas == 1)
            $('#printData .print-data .rep-canvas').show();
        if (showTitle1 == 1) {
            $('#printData .print-data .title1').css('margin-top', title1TopMargin);
            $('#printData .print-data .title1').css('color', rgb2hex(title1Color));
            $('#printData .print-data .title1').show();
            $('#printData .print-data .title1').html($(tbl).attr('data-exportTitle_1'));
        }
        if (showTitle2 == 1) {
            $('#printData .print-data .title2').css('margin-top', title2TopMargin);
            $('#printData .print-data .title2').show();
            $('#printData .print-data .title2').html($(tbl).attr('data-exportTitle_2'));
        }
        if (showTitle3 == 1) {
            $('#printData .print-data .title3').css('margin-top', title3TopMargin);
            $('#printData .print-data .title3').show();
            $('#printData .print-data .title3').html($(tbl).attr('data-exportTitle_3'));
        }
    }
    if (showBorder == 1)
        $('#printData .print-data .rep-border').show();

    var str = '<table cellpadding="0" cellspacing="0">';
    str += '<thead><tr>';
    $(tblData['head']).each(function (i, col) {
        str += '<th style="' + col.style + '">' + col.text + '</th>';
    });
    str += ' </tr></thead>';
    str += '<tbody>';

    $(tblData['body']).each(function (i, row) {

        str += '<tr>';
        $(row).each(function (i, col) {
            str += '<td style="' + col.style + '">' + col.text + '</td>';
        });
        str += '</tr>';

    });
    str += '</tbody>';
    str += '</table>';

    $('#printData .print-data .dv-tbl-reports').html(str);
    if (showPreview == 1) {
        printNewWindow($('#printData').html(), delay, showPreview);
        $('#printData').hide();
    }
    else {
        setTimeout(function () {
            window.print();
            $('#printData').hide();
        }, delay);
    }
});