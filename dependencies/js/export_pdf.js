/**
 * To work this function you need the following scripts:
 * <script src="dependencies/plugins/pdfmake-0.2.0/build/pdfmake.min.js"></script>
 * <script src="dependencies/plugins/pdfmake-0.2.0/build/vfs_fonts.js"></script>
 */
$(document).ready(function () {
    $(document).on('click', '.export-PDF', function () {

        var tbl = $(this).data('target');
        var tblData = readTBLData(tbl); // @ common.js

        if (!tblData['body'].length) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No data to export!'
            });
            return false;
        }

        var EXPORT = [];
        var heads = formatTblData([tblData['head']]);
        EXPORT['exportColHeads'] = heads[0];
        EXPORT['title_1'] = $(tbl).attr('data-exportTitle_1');
        EXPORT['title_2'] = $(tbl).attr('data-exportTitle_2');
        EXPORT['title_3'] = $(tbl).attr('data-exportTitle_3');
        EXPORT['logo'] = $('#app-logo-xs').attr('src'); // #app-logo-sm @ views\side_bar.php
        EXPORT['exportColData'] = formatTblData(tblData['body']);
        startPDF(EXPORT);
    });

    function formatTblData(tblData) {
        var temp = [];
        $(tblData).each(function (i, row) {
            var arr = [];
            $.each(row, function (j, col) {
                var text = col.text;
                var alignment = 'left';
                var bold = false;
                var color = '#000';
                if (col.style) {
                    $('body').append('<div id="export-styles" style="' + col.style + '">My Export Style</div>');
                    if ($('#export-styles').css('text-align'))
                        alignment = $('#export-styles').css('text-align');
                    if (eval($('#export-styles').css('font-weight')) >= 700)
                        bold = true;
                    if ($('#export-styles').css('color'))
                        color = rgb2hex($('#export-styles').css('color'));

                    $('#export-styles').remove();
                }

                arr.push({
                    text: text,
                    alignment: alignment,
                    bold: bold,
                    color: color
                });
            });
            temp[i] = arr;
        });

        return temp;
    }


    function startPDF(data) {

        var content = [];

        var showHeader = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_HDR"]').val(); // 1 => show, 2 => Hide
        var showBorder = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_BDR"]').val(); // 1 => show, 2 => Hide

        content.push(getTopMargin());


        // If need to show header section
        if (showHeader == 1)
            content.push(getHeaderDetails(data));

        // If need to show border line under header section
        if (showBorder == 1)
            content.push(getBorderLine());

        // Adding table data
        content.push(getTableData(data));

        var leftMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="M_LFT"]').val());
        var title1TopMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_1_M_TOP"]').val());
        var title2TopMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_2_M_TOP"]').val());
        var title3TopMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_3_M_TOP"]').val());
        var title1Color = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_1_COLOR"]').val()
        var pdf = {
            content: content,
            styles: {
                header1: {
                    fontSize: 20,
                    bold: true,
                    color: title1Color,
                    margin: [0, title1TopMargin, 0, 10]//[left, top, right, bottom]
                },
                header2: {
                    fontSize: 16,
                    bold: true,
                    margin: [0, title2TopMargin, 0, 0] //[left, top, right, bottom]
                },
                header3: {
                    fontSize: 16,
                    bold: true,
                    margin: [0, title3TopMargin, 0, 0] //[left, top, right, bottom]
                },
                tableExample: {
                    bold: false,
                    margin: [leftMargin, 5, 0, 15] //[left, top, right, bottom]
                },
                tableHeader: {
                    bold: true,
                    fontSize: 13,
                    color: 'black',
                    margin: [leftMargin, 0, 0, 0] //[left, top, right, bottom]
                    //border: [false, true, false, true]
                }
            },

            defaultStyle: {
                // alignment: 'justify'
            }
        }

        pdfMake.createPdf(pdf).download(data['title_2'] + '.pdf');
    }


    function getTopMargin() {
        var topMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="M_TOP"]').val());

        var m = {
            stack: [{ text: "" }],
            margin: [0, topMargin, 0, 0]//[left, top, right, bottom]
        }

        return m;
    }

    function getHeaderDetails(data) {
        var showLogo = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_LOGO"]').val(); // 1 => show, 2 => Hide
        var showCanvas = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_CANVAS"]').val(); // 1 => show, 2 => Hide
        var leftMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="M_LFT"]').val());

        var logo = {
            image: data['logo'],
            width: 100 * 0.7487922705314, // 0.748 is to make image clarity
            height: 100 * 0.7487922705314,
            margin: [leftMargin, 0, 0, 0],
        }

        var canvas = {
            canvas: [
                {
                    type: 'line',
                    x1: -20, y1: -2,
                    x2: 28, y2: -2,
                    lineWidth: 3,
                    lineColor: 'black'
                },
                {
                    type: 'rect',
                    x: 0,
                    y: 0,
                    w: 20,
                    h: 103 * 0.7487922705314,
                    lineColor: 'black',
                    color: 'black',
                },
                {
                    type: 'rect',
                    x: 23,
                    y: 2,
                    w: 4,
                    h: 100 * 0.7487922705314,
                    lineColor: 'red',
                    color: 'red',
                },
            ],
            width: 15,
            margin: [(10 + leftMargin), 0, 0, 0] //[left, top, right, bottom]
        }

        var showTitle1 = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_1"]').val(); // 1 => show, 2 => Hide
        var showTitle2 = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_2"]').val(); // 1 => show, 2 => Hide
        var showTitle3 = $('[data-reftbl=""][data-cat="2"][data-key="EXPT_TITLE_3"]').val(); // 1 => show, 2 => Hide
        var titles = {
            stack: [],
            margin: [(30 + leftMargin), -1, 0, 0]//[left, top, right, bottom]
        }

        // If show title 1
        if (showTitle1 == 1)
            titles.stack.push({ text: data['title_1'], style: 'header1' });

        // If show title 2
        if (showTitle2 == 1)
            titles.stack.push({ text: data['title_2'], style: 'header2' });

        // If show title 3
        if (showTitle3 == 1)
            titles.stack.push({ text: data['title_3'], style: 'header3' });

        var pageHead = {
            columns: []
        }

        if (showLogo == 1) {
            pageHead.columns.push(logo);
        }

        if (showCanvas == 1) {
            pageHead.columns.push(canvas);
        }

        // If No Logo & Canvas
        if ((showLogo == 2) && (showCanvas == 2)) {
            titles.margin = [leftMargin, -1, 0, 0];//[left, top, right, bottom]
        }

        // If both Logo & Canvas
        else if ((showLogo == 1) && (showCanvas == 1)) {
            titles.margin = [(30 + leftMargin), -1, 0, 0];//[left, top, right, bottom]
        }

        // If has Logo & no Canvas
        else if ((showLogo == 1 || showCanvas == 2)) {
            titles.margin = [(10 + leftMargin), -1, 0, 0];//[left, top, right, bottom]
        }

        // If no Logo but has Canvas
        else if ((showLogo == 2 || showCanvas == 1)) {
            titles.margin = [(30 + leftMargin), -1, 0, 0];//[left, top, right, bottom]
        }

        pageHead.columns.push(titles);
        //console.log(pageHead);
        return pageHead;
    }

    function getBorderLine() {

        var leftMargin = eval($('[data-reftbl=""][data-cat="2"][data-key="M_LFT"]').val());
        return {
            canvas: [
                {
                    type: 'rect',
                    x: leftMargin,
                    y: 3,
                    w: 515,
                    h: 2,
                    lineColor: 'black',
                    color: 'black',
                }
            ]
        };
    }

    function getTableData(data) {
        var headers = [];
        headers[0] = []
        $(data['exportColHeads']).each(function (i, val) {
            headers[0][i] = {
                text: val,
                style: 'tableHeader'
            }
        });
        var body = headers.concat(data['exportColData']);
        return {
            table: {
                headerRows: 1,
                // dontBreakRows: true,
                // keepWithHeaderRows: 1,
                body: body
            },
            layout: 'lightHorizontalLines',
            style: 'tableExample'
        }
    }
});