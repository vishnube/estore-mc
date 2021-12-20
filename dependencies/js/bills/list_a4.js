function getFootPchsButtons(id) {
    var billType = $('.bill_type[name=bill_type]:checked').val();
    var str = '';
    var btnPchsOdr = '<div class="btn btn-success convertor" data-tab="purchase" data-bill_type="pchs_odr" data-bls_ref_key="' + id + '"><div class="icon"><i class="far fa-money-check-edit-alt fa-flip-horizontal"></i> </div><div class="text">PURCHASE<br> ORDER</div></div>';
    var btnPchsBls = '<div class="btn btn-success convertor" data-tab="purchase" data-bill_type="pchs_bls" data-bls_ref_key="' + id + '"><div class="icon"><i class="fal fa-money-bill-alt"></i> </div><div class="text">PURCHASE<br> BILL</div></div>';
    var btnPchsRtn = '<div class="btn btn-success convertor" data-tab="purchase" data-bill_type="pchs_rtn" data-bls_ref_key="' + id + '"><div class="icon"><i class="fal fa-sign-in-alt fa-flip-horizontal"></i> </div><div class="text">PURCHASE<br> RETURN</div></div>';


    // Purchase => pchs_qtn, pchs_odr, pchs_bls, pchs_rtn
    if (billType == 'pchs_qtn') {
        str += btnPchsOdr + btnPchsBls;
    }
    else if (billType == 'pchs_odr') {
        str += btnPchsBls;
    }
    else if (billType == 'pchs_bls') {
        str += btnPchsRtn;
    }

    return str;
}

function getFootSlsButtons(id) {
    var billType = $('.bill_type[name=bill_type]:checked').val();
    var str = '';
    var btnSlsQtn = '<div class="btn btn-danger convertor" data-tab="sale" data-bill_type="sls_qtn" data-bls_ref_key="' + id + '"><div class="icon"><i class="fal fa-money-check-alt"></i></div><div class="text">SALE <br>QUOTATION</div></div>';
    var btnSlsOdr = '<div class="btn btn-danger convertor" data-tab="sale" data-bill_type="sls_odr" data-bls_ref_key="' + id + '"><div class="icon"><i class="far fa-money-check-edit-alt fa-flip-horizontal"></i> </div><div class="text">SALE <br>ORDER</div></div>';
    var btnSlsBls = '<div class="btn btn-danger convertor" data-tab="sale" data-bill_type="sls_bls" data-bls_ref_key="' + id + '"><div class="icon"><i class="fal fa-money-bill-alt"></i> </div><div class="text">SALE<br> BILL</div></div>';
    var btnSlsRtn = '<div class="btn btn-danger convertor" data-tab="sale" data-bill_type="sls_rtn" data-bls_ref_key="' + id + '"><div class="icon"><i class="fal fa-sign-in-alt fa-flip-horizontal"></i> </div><div class="text">SALE <br>RETURN</div></div>';

    // Purchase => pchs_qtn, pchs_odr, pchs_bls, pchs_rtn
    if (billType == 'pchs_qtn' || billType == 'pchs_odr' || billType == 'pchs_bls') {
        str += btnSlsQtn + btnSlsOdr + btnSlsBls;
    }

    // Sale => sls_qtn, sls_odr, sls_bls, sls_rtn
    else if (billType == 'sls_qtn') {
        str += btnSlsOdr + btnSlsBls;
    }
    // else if (billType == 'sls_odr') {
    //     str += btnSlsBls;
    // }
    else if (billType == 'sls_bls') {
        str += btnSlsRtn;
    }

    return str;
}

function createA4MainTable(res) {

    var billType = $('.bill_type[name=bill_type]:checked').val();

    var tbl1 = $("#tbl_bls_a4_container"); // For display table 
    tbl1.empty();
    $('#tbl_bls_a4_footer .tot-val').html('0.00');

    var sno = Number(res.offset);
    var prdNo = 0;
    var str1 = ""; // For display table 
    var tempText = '';

    if (!res.bill_data.length) {
        tbl1.html(get_no_result_div());
        return;
    }

    var totQty = 0, totAmt = 0, totCGST = 0, totSGST = 0, totIGSt = 0, totGrsAmt = 0, totKFC = 0, totGrsDisc = 0, totRound = 0, totNetAmt = 0, totPaid = 0, totBal = 0;

    $.each(res.bill_data, function (i, row) {
        prdNo = 0;
        sno += 1;
        str1 += "<div class='row m-0'><div class='col-12 a4-main-col'>";

        if (row.bls_status == 3) {
            str1 += '<div class="ribbon-wrapper ribbon-xl">';
            str1 += '<div class="ribbon bg-danger text-lg">CANCELED</div>'
            str1 += '</div>';
        }
        else if (row.bls_status == 2 && row.ref_to) {
            str1 += '<div class="ribbon-wrapper ribbon-xl">';
            str1 += '<div class="ribbon bg-warning">Moved to ' + row.ref_to + '</div>'
            str1 += '</div>';
        }

        str1 += "<div class='prda4 card'><div class='card-header bls-tr'>";


        str1 += '<div class="row">';
        str1 += '       <div class="carmo">';
        str1 += '           <div class="selwish">';
        if (row.bls_status == ACTIVE) {
            if (tasks['tsk_' + billType + '_edit'])
                str1 += '       <div class="edit_bls text-info" title="Edit Bill"><i class="fas fa-pencil-alt cursor-pointer"></i></div>';
            if (tasks['tsk_' + billType + '_deactivate'])
                str1 += '       <div class="mt-1 delete_bls text-danger" title="Delete Bill"><i class="fas fa-trash-alt cursor-pointer"></i></div>';
            if (tasks['tsk_' + billType + '_cancel'])
                str1 += '       <div class="mt-1 cancel_bls text-pink" title="Cancel Bill"><i class="fad fa-align-slash cursor-pointer"></i></div>';
        }
        str1 += '           </div>';

        str1 += '           <div class="selwish"><div class="rtoff-slno">' + sno + '</div></div>';
        str1 += '       </div>'; // <div class="carmo">

        var billNo = row.bill_no.blb_prefix + row.bill_no.bln_name + row.bill_no.blb_sufix;

        str1 += "       <input type='hidden' class='bls_id' value='" + row.bls_id + "'>";
        str1 += "       <input type='hidden' class='bls_name' value='" + billNo + "'>";

        str1 += '		<div class="coin-box">';
        str1 += '			<span class="coin-box-icon bg-success"><i class="fab fa-app-store"></i></span>';
        str1 += '			<div class="coin-box-content">';
        str1 += '				<span class="coin-box-text text-success">BILL NO:';
        str1 += '					&nbsp;<i title="Show Details" class="cursor-pointer show-bls-details fad fa-map-marker-exclamation" style="--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;"></i>';
        str1 += '				</span>';
        str1 += '				<span class="coin-box-number" style="text-align: center; font-size: 20px;">' + billNo + '</span>';
        str1 += '			</div>';
        str1 += '		</div>';

        str1 += '		<div class="coin-box">';
        str1 += '			<span class="coin-box-icon bg-info"><i class="fal fa-calendar-alt"></i></span>';
        str1 += '			<div class="coin-box-content">';
        str1 += '				<span class="coin-box-text text-info">DATE</span>';
        str1 += '				<span class="coin-box-number">' + row.bls_date + '<br>' + row.bill_time + '</span>';
        str1 += '			</div>';
        str1 += '		</div>';


        // Adding <br> in name between each 25 character. (But if it has only 35 characters, leave it free).
        tempText = row.from.length > 25 ? (row.from.length <= 35 ? row.from : row.from.match(/.{1,25}/g).join("<br>")) : row.from;
        str1 += '		<div class="coin-box col-lg-3 col-md-6 col-12">';
        str1 += '			<span class="coin-box-icon bg-pink"><i class="fas fa-sign-in"></i></span>';
        str1 += '			<div class="coin-box-content">';
        str1 += '				<span class="coin-box-text text-pink">FROM</span>';
        str1 += '				<span class="coin-box-number">' + tempText + '</span>';
        str1 += '			</div>';
        str1 += '		</div>';


        // Adding <br> in name between each 25 character. (But if it has only 35 characters, leave it free).
        tempText = row.to.length > 25 ? (row.to.length <= 35 ? row.to : row.to.match(/.{1,25}/g).join("<br>")) : row.to;
        str1 += '		<div class="coin-box col-lg-3 col-md-6 col-12">';
        str1 += '			<span class="coin-box-icon bg-primary"><i class="fas fa-sign-out"></i></span>';
        str1 += '			<div class="coin-box-content">';
        str1 += '				<span class="coin-box-text text-primary">TO</span>';
        str1 += '				<span class="coin-box-number">' + tempText + '</span>';
        str1 += '			</div>';
        str1 += '		</div>';

        str1 += '</div>'; // End of <div class="row">
        str1 += "</div>"; //<div class="card-header bls-tr">






        str1 += '   <div class="card-body">';
        str1 += '       <div class="row">';
        str1 += '           <div class="col-lg-10 col-12">';
        // If more than 3 items in a bill, Setting a height for container to scroll up/down.
        var prda4Height = row.blp_data.length > 4 ? ' style="height:323px"' : '';
        str1 += '       <div class="table-responsive p-0 dv-prda4" ' + prda4Height + '>';
        str1 += '       <table class="prda4 sr-tbl-reports table table-head-fixed text-nowrap  table-hover table-striped"><thead><tr>  <th>#</th> <th>Godown</th> <th>Product</th> <th>Quantity</th> <th>Rate <small> (With Tax)</small></th> <th>Amount <small> (Without Tax)</small></th>';

        // If Taxable
        if (row.bls_taxable == 1) {
            // Intra-State (CGST|SGST)
            if (row.bls_tax_state == 1)
                str1 += '<th>CGST</th> <th>SGST</th> <th>Gross Amount</th>';

            // Inter-State (IGST)
            else if (row.bls_tax_state == 2)
                str1 += '<th>IGST</th> <th>Gross Amount</th>';
        }

        str1 += '</tr></thead><tbody>';

        $.each(row.blp_data, function (j, blp_row) {
            prdNo++;
            totQty = bcadd(totQty, blp_row.blp_qty, 5);

            str1 += '<tr>';
            str1 += '<td>' + prdNo + '</td>';
            str1 += '<td>' + blp_row.gdn_name + '</td>';
            var batch = blp_row.pdbch_name ? '<br><small class="text-danger"><b>BATCH NO:</b> ' + blp_row.pdbch_name + '</small>' : '';
            str1 += '<td>' + blp_row.prd_name + batch + '</td>';
            str1 += '<td>' + parseFloat(blp_row.blp_qty) + ' ' + blp_row.unt_name + '</td>';
            str1 += '<td>' + number_format(roundNum(blp_row.blp_trate, 2), 2) + '</td>';
            str1 += '<td>' + number_format(roundNum(blp_row.blp_amount, 2), 2) + '</td>';

            if (row.bls_taxable == 1) {

                // Intra-State (CGST|SGST)
                if (row.bls_tax_state == 1) {
                    str1 += '<td>' + '(' + blp_row.blp_cgst_p + '%) ' + number_format(roundNum(blp_row.blp_cgst, 2), 2) + '</td>';
                    str1 += '<td>' + '(' + blp_row.blp_sgst_p + '%) ' + number_format(roundNum(blp_row.blp_sgst, 2), 2) + '</td>';
                    str1 += '<td>' + number_format(roundNum(blp_row.blp_gross_amt, 2), 2) + '</td>';
                }

                // Inter-State (IGST)
                else if (row.bls_tax_state == 2) {
                    str1 += '<td>' + '(' + blp_row.blp_igst_p + '%) ' + number_format(roundNum(blp_row.blp_igst, 2), 2) + '</td>';
                    str1 += '<td>' + number_format(roundNum(blp_row.blp_gross_amt, 2), 2) + '</td>';
                }
            }

            str1 += '</tr>';

        });

        str1 += '               </tbody>';

        str1 += '               <tfoot><tr><th colspan="5">TOTAL</th><th class="text-right">' + number_format(roundNum(row.bls_amt_total, 2), 2) + '</th>';

        if (row.bls_taxable == 1) {
            // Intra-State (CGST|SGST)
            if (row.bls_tax_state == 1) {
                str1 += '<th class="text-right">' + number_format(roundNum(row.bls_cgst_total, 2), 2) + '</th> <th class="text-right">' + number_format(roundNum(row.bls_sgst_total, 2), 2) + '</th> <th class="text-right">' + number_format(roundNum(row.bls_gross_total, 2), 2) + '</th>';
            }
            // Inter-State (IGST)
            else if (row.bls_tax_state == 2) {
                str1 += '<th class="text-right">' + number_format(roundNum(row.bls_igst_total, 2), 2) + '</th><th class="text-right">' + number_format(roundNum(row.bls_gross_total, 2), 2) + '</th>';
            }
        }

        str1 += '               </tr></tfoot>';
        str1 += '           </table></div>'; // <.div class="table-responsive p-0 dv-prda4">
        str1 += "       </div>"; //<.div class="col-10">

        str1 += "       <div class='col-lg-2 col-12'>";// style='padding: 0 0 0 3px;vertical-align: top;'
        str1 += '           <table class="prda4-amt"><tbody>';
        if (row.bls_taxable == 1)
            str1 += '<tr><td><div class="hd">KFC</div></td><td>' + number_format(roundNum(row.bls_cess_total, 2), 2) + '</td></tr>';

        str1 += '           <tr><td><div class="hd">Gross Discount</div></td><td>' + number_format(roundNum(row.bls_gross_disc, 2), 2) + '</td></tr>';
        str1 += '           <tr><td><div class="hd">Round Off</div></td><td>' + number_format(roundNum(row.bls_round, 2), 2) + '</td></tr>';
        str1 += '           <tr><td><div class="hd">Net Amount</div></td><td>' + number_format(roundNum(row.bls_net_amount, 2), 2) + '</td></tr>';
        str1 += '           <tr><td><div class="hd">Paid</div></td><td>' + number_format(roundNum(row.bls_paid, 2), 2) + '</td></tr>';
        str1 += '           <tr><td><div class="hd">Balance</div></td><td>' + number_format(roundNum(row.bls_balance, 2), 2) + '</td></tr>';
        str1 += '           </tbody></table>';
        str1 += "       </div>"; // <.div class='col-2'>
        str1 += "   </div>";// <.div class="row">
        str1 += "</div>";// <.div class='card-body'>




        str1 += '<div class="dv-bill-convert card-footer"> ';
        str1 += '   <div class="row"> ';
        str1 += '       <div class="col-6"> ';
        if (row.bls_status == ACTIVE)
            str1 += getFootPchsButtons(row.bls_id);
        str1 += "       </div>"; // <div class='col-6'>
        str1 += '       <div class="col-6 text-right"> ';
        if (row.bls_status == ACTIVE)
            str1 += getFootSlsButtons(row.bls_id);
        str1 += "       </div>"; // <div class='col-6'>
        str1 += "   </div>"; // <div class='row'>
        str1 += "</div>"; // <div class='card-footer'>


        str1 += "</div>"; // <div class='prda4 card'>
        str1 += "</div></div>"; // <div class='col-12 a4-main-col my-4'><div class='row m-0'>

        // totQty
        totAmt = bcadd(totAmt, row.bls_amt_total, 5);
        totCGST = bcadd(totCGST, row.bls_cgst_total, 5);
        totSGST = bcadd(totSGST, row.bls_sgst_total, 5);
        totIGSt = bcadd(totIGSt, row.bls_igst_total, 5);
        totGrsAmt = bcadd(totGrsAmt, row.bls_gross_total, 5);
        totKFC = bcadd(totKFC, row.bls_cess_total, 5);
        totGrsDisc = bcadd(totGrsDisc, row.bls_gross_disc, 5);
        totRound = bcadd(totRound, row.bls_round, 5);
        totNetAmt = bcadd(totNetAmt, row.bls_net_amount, 5);
        totPaid = bcadd(totPaid, row.bls_paid, 5);
        totBal = bcadd(totBal, row.bls_balance, 5);
    });
    tbl1.html(str1);

    // ADDING TOTAL
    $('#tbl_bls_a4_footer .tot-qty').html(roundNum(totQty, 2));
    $('#tbl_bls_a4_footer .tot-amt').html(roundNum(totAmt, 2));
    $('#tbl_bls_a4_footer .tot-cgst').html(roundNum(totCGST, 2));
    $('#tbl_bls_a4_footer .tot-sgst').html(roundNum(totSGST, 2));
    $('#tbl_bls_a4_footer .tot-igst').html(roundNum(totIGSt, 2));
    $('#tbl_bls_a4_footer .tot-grs-amt').html(roundNum(totGrsAmt, 2));
    $('#tbl_bls_a4_footer .tot-kfc').html(roundNum(totKFC, 2));
    $('#tbl_bls_a4_footer .tot-grs-disc').html(roundNum(totGrsDisc, 2));
    $('#tbl_bls_a4_footer .tot-round').html(roundNum(totRound, 2));
    $('#tbl_bls_a4_footer .tot-net-amt').html(roundNum(totNetAmt, 2));
    $('#tbl_bls_a4_footer .tot-paid').html(roundNum(totPaid, 2));
    $('#tbl_bls_a4_footer .tot-bal').html(roundNum(totBal, 2));
}

function createA4ExportTable(res, tbl2, tbl3) {

    tbl2.find("tbody").empty();
    tbl2.find(" > tfoot").empty();
    tbl3.find("tbody").empty();
    tbl3.find(" > tfoot").empty();

    var sno = 0;
    var prdNo = 0;
    var str2 = ""; // For export (print) table @  views\bills\list_display_a4_export.php
    var str3 = ""; // For export (excel) table @  views\bills\list_display_a4_export.php

    var totQty = 0, totAmt = 0, totCGST = 0, totSGST = 0, totIGSt = 0, totGrsAmt = 0, totKFC = 0, totGrsDisc = 0, totRound = 0, totNetAmt = 0, totPaid = 0, totBal = 0;

    $.each(res.bill_data, function (i, row) {
        prdNo = 0;
        sno += 1;

        str2 += "<tr>";
        str3 += "<tr>";

        str2 += "<td colspan='2' style='padding-top: 20px;vertical-align: text-bottom;'>";
        str3 += "<td colspan='2'>";

        str2 += '<div class="rtoff-slno">' + sno + '</div>';
        str3 += '<b>SlNo: </b>' + sno;
        var billNo = row.bill_no.blb_prefix + row.bill_no.bln_name + row.bill_no.blb_sufix;
        str2 += '<span class="rtoff">Bill No:  </span><span class="rtoff-x">' + billNo + '</span>';
        str3 += ' <b>Bill No:</b> ' + billNo;

        str2 += '<span class="rtoff">Date:  </span><span class="rtoff-x">' + row.bls_date + '</span>';
        str3 += ' <b>Date:</b> ' + row.bls_date;

        str2 += '<span class="rtoff">From:  </span><span class="rtoff-x">' + row.from + '</span>';
        str3 += ' <b>From:</b> ' + row.from;

        str2 += '<span class="rtoff">To:  </span><span class="rtoff-x">' + row.to + '</span>';
        str3 += ' <b>To:</b> ' + row.to;
        str2 += "</td>";
        str3 += "</td>";

        str2 += "</tr>";
        str3 += "</tr>";

        str2 += "<tr>";
        str3 += "<tr>";


        str2 += "<td style='padding:0'>";
        str3 += "<td style='padding:0'>";

        str2 += '<table class="prda4-export"><thead><tr><th>#</th>  <th>Godown</th> <th>Product</th> <th>Quantity</th> <th>Rate <small> (With Tax)</small></th> <th>Amount <small> (Without Tax)</small></th>';
        str3 += '<table border="1"><thead><tr><th>No</th>  <th>Godown</th> <th>Product</th> <th>Quantity</th> <th>Rate</th> <th>Amount</th>';

        // If Taxable
        if (row.bls_taxable == 1) {
            // Intra-State (CGST|SGST)
            if (row.bls_tax_state == 1) {
                str2 += '<th>CGST</th> <th>SGST</th> <th>Gross Amount</th>';
                str3 += '<th>CGST</th> <th>SGST</th> <th>Gross Amount</th>';
            }

            // Inter-State (IGST)
            else if (row.bls_tax_state == 2) {
                str2 += '<th>IGST</th> <th>Gross Amount</th>';
                str3 += '<th>IGST</th> <th>Gross Amount</th>';
            }
        }

        str2 += '</tr></thead><tbody>';
        str3 += '</tr></thead><tbody>';

        $.each(row.blp_data, function (j, blp_row) {
            prdNo++;
            totQty = bcadd(totQty, blp_row.blp_qty, 5);

            str2 += '<tr>';
            str3 += '<tr>';

            str2 += '<td>' + prdNo + '</td>';
            str2 += '<td>' + blp_row.gdn_name + '</td>';
            var batch = blp_row.pdbch_name ? ' (BATCH NO: ' + blp_row.pdbch_name + ')' : '';
            str2 += '<td>' + blp_row.prd_name + batch + '</td>';
            str2 += '<td>' + parseFloat(blp_row.blp_qty) + ' ' + blp_row.unt_name + '</td>';
            str2 += '<td class="text-right">' + number_format(roundNum(blp_row.blp_trate, 2), 2) + '</td>';
            str2 += '<td class="text-right">' + number_format(roundNum(blp_row.blp_amount, 2), 2) + '</td>';

            str3 += '<td>' + prdNo + '</td>';
            str3 += '<td>' + blp_row.gdn_name + '</td>';
            str3 += '<td>' + blp_row.prd_name + '</td>';
            str3 += '<td>' + parseFloat(blp_row.blp_qty) + ' ' + blp_row.unt_name + '</td>';
            str3 += '<td>' + number_format(roundNum(blp_row.blp_trate, 2), 2) + '</td>';
            str3 += '<td>' + number_format(roundNum(blp_row.blp_amount, 2), 2) + '</td>';

            if (row.bls_taxable == 1) {

                // Intra-State (CGST|SGST)
                if (row.bls_tax_state == 1) {
                    str2 += '<td class="text-right">' + '(' + blp_row.blp_cgst_p + '%) ' + number_format(roundNum(blp_row.blp_cgst, 2), 2) + '</td>';
                    str2 += '<td class="text-right">' + '(' + blp_row.blp_sgst_p + '%) ' + number_format(roundNum(blp_row.blp_sgst, 2), 2) + '</td>';
                    str2 += '<td class="text-right">' + number_format(roundNum(blp_row.blp_gross_amt, 2), 2) + '</td>';

                    str3 += '<td>' + '(' + blp_row.blp_cgst_p + '%) ' + number_format(roundNum(blp_row.blp_cgst, 2), 2) + '</td>';
                    str3 += '<td>' + '(' + blp_row.blp_sgst_p + '%) ' + number_format(roundNum(blp_row.blp_sgst, 2), 2) + '</td>';
                    str3 += '<td>' + number_format(roundNum(blp_row.blp_gross_amt, 2), 2) + '</td>';
                }

                // Inter-State (IGST)
                else if (row.bls_tax_state == 2) {
                    str2 += '<td class="text-right">' + '(' + blp_row.blp_igst_p + '%) ' + number_format(roundNum(blp_row.blp_igst, 2), 2) + '</td>';
                    str2 += '<td class="text-right">' + number_format(roundNum(blp_row.blp_gross_amt, 2), 2) + '</td>';

                    str3 += '<td>' + '(' + blp_row.blp_igst_p + '%) ' + number_format(roundNum(blp_row.blp_igst, 2), 2) + '</td>';
                    str3 += '<td>' + number_format(roundNum(blp_row.blp_gross_amt, 2), 2) + '</td>';
                }
            }

            str2 += '</tr>';
            str3 += '</tr>';

        });

        str2 += '</tbody>';
        str3 += '</tbody>';

        str2 += '<tfoot><tr><th colspan="5">TOTAL</th><th class="text-right">' + number_format(roundNum(row.bls_amt_total, 2), 2) + '</th>';
        str3 += '<tfoot><tr><th colspan="5">TOTAL</th><th>' + number_format(roundNum(row.bls_amt_total, 2), 2) + '</th>';

        if (row.bls_taxable == 1) {
            // Intra-State (CGST|SGST)
            if (row.bls_tax_state == 1) {
                str2 += '<th class="text-right">' + number_format(roundNum(row.bls_cgst_total, 2), 2) + '</th> <th class="text-right">' + number_format(roundNum(row.bls_sgst_total, 2), 2) + '</th> <th class="text-right">' + number_format(roundNum(row.bls_gross_total, 2), 2) + '</th>';

                str3 += '<th>' + number_format(roundNum(row.bls_cgst_total, 2), 2) + '</th> <th>' + number_format(roundNum(row.bls_sgst_total, 2), 2) + '</th> <th>' + number_format(roundNum(row.bls_gross_total, 2), 2) + '</th>';

            }
            // Inter-State (IGST)
            else if (row.bls_tax_state == 2) {
                str2 += '<th class="text-right">' + number_format(roundNum(row.bls_igst_total, 2), 2) + '</th><th class="text-right">' + number_format(roundNum(row.bls_gross_total, 2), 2) + '</th>';
                str3 += '<th>' + number_format(roundNum(row.bls_igst_total, 2), 2) + '</th><th>' + number_format(roundNum(row.bls_gross_total, 2), 2) + '</th>';
            }
        }

        str2 += '</tr></tfoot>';
        str3 += '</tr></tfoot>';

        str2 += '</table>';
        str3 += '</table>';

        str2 += "</td>";
        str3 += "</td>";

        str2 += "<td>";
        str3 += "<td>";

        if (row.bls_taxable == 1) {
            str2 += '<div class="inln"><div>KFC: </div><div>' + number_format(roundNum(row.bls_cess_total, 2), 2) + '</div></div>';
            str3 += '<div><b>KFC:</b> ' + number_format(roundNum(row.bls_cess_total, 2), 2) + '</div>';
        }

        str2 += '<div class="inln"><div>Gross Discount: </div><div>' + number_format(roundNum(row.bls_gross_disc, 2), 2) + '</div></div>';
        str3 += '<div><b>Gross Discount:</b> ' + number_format(roundNum(row.bls_gross_disc, 2), 2) + '</div>';

        str2 += '<div class="inln"><div>Round Off: </div><div>' + number_format(roundNum(row.bls_round, 2), 2) + '</div></div>';
        str3 += '<div><b>Round Off:</b> ' + number_format(roundNum(row.bls_round, 2), 2) + '</div>';

        str2 += '<div class="inln"><div>Net Amount: </div><div>' + number_format(roundNum(row.bls_net_amount, 2), 2) + '</div></div>';
        str3 += '<div><b>Net Amount:</b> ' + number_format(roundNum(row.bls_net_amount, 2), 2) + '</div>';

        str2 += '<div class="inln"><div>Paid: </div><div>' + number_format(roundNum(row.bls_paid, 2), 2) + '</div></div>';
        str3 += '<div><b>Paid:</b> ' + number_format(roundNum(row.bls_paid, 2), 2) + '</div>';

        str2 += '<div class="inln"><div>Balance: </div><div>' + number_format(roundNum(row.bls_balance, 2), 2) + '</div></div>';
        str3 += '<div><b>Balance:</b> ' + number_format(roundNum(row.bls_balance, 2), 2) + '</div>';

        str2 += "</td>";
        str3 += "</td>";

        str2 += "</tr>";
        str3 += "</tr>";

        // Adding an extra row to get a blank space between bills (For Export Excel Only)
        str3 += "<tr><td colspan='2'></td></tr>";

        // totQty
        totAmt = bcadd(totAmt, row.bls_amt_total, 5);
        totCGST = bcadd(totCGST, row.bls_cgst_total, 5);
        totSGST = bcadd(totSGST, row.bls_sgst_total, 5);
        totIGSt = bcadd(totIGSt, row.bls_igst_total, 5);
        totGrsAmt = bcadd(totGrsAmt, row.bls_gross_total, 5);
        totKFC = bcadd(totKFC, row.bls_cess_total, 5);
        totGrsDisc = bcadd(totGrsDisc, row.bls_gross_disc, 5);
        totRound = bcadd(totRound, row.bls_round, 5);
        totNetAmt = bcadd(totNetAmt, row.bls_net_amount, 5);
        totPaid = bcadd(totPaid, row.bls_paid, 5);
        totBal = bcadd(totBal, row.bls_balance, 5);
    });

    // tbl2.find("tbody").html(str2);
    tbl3.find("tbody").html(str3);

    // ADDING TOTAL
    str2 += "<tr><th colspan='2'><div class='foot-tot-title'>TOTAL</div></th></tr>";
    str2 += "<tr><td colspan='2'>";
    str2 += "<div class='foot-tot'>Quantity: " + roundNum(totQty, 2) + " Units</div>";
    str2 += "<div class='foot-tot'> Amount: " + roundNum(totAmt, 2) + "</div>";
    str2 += "<div class='foot-tot'>CGST: " + roundNum(totCGST, 2) + "</div>";
    str2 += "<div class='foot-tot'>SGS: " + roundNum(totSGST, 2) + "</div>";
    str2 += "<div class='foot-tot'>IGST: " + roundNum(totIGSt, 2) + "</div>";
    str2 += "<div class='foot-tot'>Gross Amount: " + roundNum(totGrsAmt, 2) + "</div>";
    str2 += "<div class='foot-tot'>KFC: " + roundNum(totKFC, 2) + "</div>";
    str2 += "<div class='foot-tot'>Gross Discount: " + roundNum(totGrsDisc, 2) + "</div>";
    str2 += "<div class='foot-tot'>Round Off: " + roundNum(totRound, 2) + "</div>";
    str2 += "<div class='foot-tot'>Net Amount: " + roundNum(totNetAmt, 2) + "</div>";
    str2 += "<div class='foot-tot'>Paid: " + roundNum(totPaid, 2) + "</div>";
    str2 += "<div class='foot-tot'>Balance: " + roundNum(totBal, 2) + "</div>";
    str2 += "</td></tr>";
    tbl2.find("tbody").html(str2);
    //tbl2.find(" > tfoot").html(str2);

    str3 = "<tr><th colspan='2'><b>TOTAL</b></th></tr>";
    str3 += "<tr><td colspan='2'>";
    str3 += "<b>Quantity: </b>" + roundNum(totQty, 2) + " Units; ";
    str3 += "<b> Amount: </b>" + roundNum(totAmt, 2) + "; ";
    str3 += "<b>CGST: </b>" + roundNum(totCGST, 2) + "; ";
    str3 += "<b>SGS: </b>" + roundNum(totSGST, 2) + "; ";
    str3 += "<b>IGST: </b>" + roundNum(totIGSt, 2) + "; ";
    str3 += "<b>Gross Amount: </b>" + roundNum(totGrsAmt, 2) + "; ";
    str3 += "<b>KFC: </b>" + roundNum(totKFC, 2) + "; ";
    str3 += "<b>Gross Discount: </b>" + roundNum(totGrsDisc, 2) + "; ";
    str3 += "<b>Round Off: </b>" + roundNum(totRound, 2) + "; ";
    str3 += "<b>Net Amount: </b>" + roundNum(totNetAmt, 2) + "; ";
    str3 += "<b>Paid: </b>" + roundNum(totPaid, 2) + "; ";
    str3 += "<b>Balance: </b>" + roundNum(totBal, 2) + "; ";
    str3 += "</td></tr>";
    tbl3.find(" > tfoot").html(str3);
}