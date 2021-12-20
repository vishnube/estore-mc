$(document).on('click', '.export-EXCEL', function () {

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

    var data = "";
    var EXPORT = [];

    // Adding titles
    EXPORT.push($(tbl).attr('data-exportTitle_1'));
    EXPORT.push($(tbl).attr('data-exportTitle_2'));
    EXPORT.push($(tbl).attr('data-exportTitle_3'));

    // Adding table heads
    var heads = [];
    $(tblData['head']).each(function (i, row) {
        heads.push(row.text);
    });
    EXPORT.push(heads.join(","));

    $(tblData['body']).each(function (i, val) {
        var arr = [];
        $(val).each(function (j, row) {
            // When converting to CSV, commas will be considered as columns.
            // So replacing all commas from data with semicolon.
            // arr.push(row.text.replace(/,/g, ";"));

            // The values enclosing by  double quotes to support comma.
            // https://stackoverflow.com/questions/44111580/how-to-deal-with-commas-in-csv-using-javascript
            arr.push('"' + row.text + '"');
        });
        EXPORT.push(arr.join(','));
    });

    data += EXPORT.join("\n");
    $(document.body).append('<a id="download-link" download="' + $(tbl).attr('data-exportTitle_2') + '.csv" href=' + URL.createObjectURL(new Blob([data], {
        type: "text/csv"
    })) + ' />');

    $('#download-link')[0].click();
    $('#download-link').remove();

    return;
});
