



$(document).on('click', '.export-PRINT-2', function () {

    var tbl = $(this).data('target');

    if (!$(tbl).find('tbody').html()) {
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

    var str = $(tbl).parent().html();

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

$(document).on('click', '.export-EXCEL-2', function () {

    var tbl = $(this).data('target');

    if (!$(tbl).find('tbody').html()) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No data to export!'
        });
        return false;
    }
    var fileformat = $(tbl).data('file-format');
    if (fileformat == 'EXCEL')
        exportTableToExcel(tbl);
    else if (fileformat == 'CSV')
        download_table_as_csv(tbl);
    return;
});

function exportTableToExcel(table, filename = '') {
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById($(table).attr('id'));
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename ? filename + '.xls' : 'excel_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
    }
}


// Converting HTML table data to Excel including Colspan|Rowspans.
function download_table_as_csv(table, separator = ',') {
    // Change here to your table with jquery selector, by table_id or table class. this code uses CLASS 'table'.
    var rows = $(table + ' tr');
    // Construct csv
    var csv = [];

    csv.push($(table).attr('data-exportTitle_1'));
    csv.push($(table).attr('data-exportTitle_2'));
    csv.push($(table).attr('data-exportTitle_3'));

    var lastrow = [];
    var repeatrowval = [];
    var tabledata = [];
    // the code is structured only if you can base the col numbers in a spesific row. in my situation it's the last tr in thead.         
    var cols = $(table + ' > thead > tr:last > th').length;

    $(table + ' > thead > tr:last > th').attr('rowSpan', '1');

    for (var i = 0; i < cols; i++) {
        repeatrowval.push(1);
        lastrow.push('none')
    }

    // for (var i = 0; i < rows.length - 1; i++) { //loop every row
    for (var i = 0; i < rows.length; i++) { //loop every row
        //console.log("row: " + i)
        var row = [];
        var col = rows[i].querySelectorAll('td, th');
        var col_len = 0;
        for (var j = 0; j < cols; j++) {
            var a = 0;
            //console.log(repeatrowval[j]);
            if (repeatrowval[j] > 1) {
                data = lastrow[j];
                repeatrowval[j] = repeatrowval[j] - 1;
                //console.log("row: " + i + " reapet_col: " + j + " = " + data);
                //row.push('"' + data + '"'); //No need to repeat same values on rowspans
                row.push(''); // So puting blank for same values on rowspans
            } else {
                if (col[col_len] === undefined)
                    break;
                var colspan = col[col_len].colSpan ?? 1;

                //console.log("row: " + i + ", col: " + j + ", colspan = " + colspan + ", repeatrowval: " + repeatrowval[j])

                for (var r = 0; r < colspan; r++) {

                    var rowspan = col[col_len].rowSpan ?? 1;
                    //console.log('rowspan: ' + rowspan)
                    if (rowspan == 1) {
                        // Clean innertext to remove multiple spaces and jumpline (break csv)
                        var data = $(col[col_len]).text().replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                        // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                        data = data.replace(/"/g, '""');
                        repeatrowval[j] = 1;
                        lastrow[j] = data
                        //}
                    } else {
                        // Clean innertext to remove multiple spaces and jumpline (break csv)
                        var data = $(col[col_len]).text().replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                        // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                        data = data.replace(/"/g, '""');
                        lastrow[j] = data; repeatrowval[j] = rowspan;
                    }
                    // Push escaped string
                    //console.log("row: " + i + " col: " + j + " = " + data);
                    row.push('"' + data + '"');
                    if (colspan > 1) { a++ };
                }
                col_len++
            }
            if (a > 0) { j = +a };
        }
        csv.push(row.join(separator));
        tabledata.push(row);
    }
    var csv_string = csv.join('\n');
    // Download it
    var filename = 'export_Report_' + new Date().toLocaleDateString() + '.csv';
    var link = document.createElement('a');
    link.style.display = 'none';
    link.setAttribute('target', '_blank');
    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}