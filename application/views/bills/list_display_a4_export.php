<!-- 
    Class '.dv-data-table.active .print-table' is for export (print/excel) 
    Where '.dv-data-table.active' @ list.php
    -->

<h2 class="bg-warning text-center">TO EXPORT PRINT</h2>
<div>
    <style type="text/css">
        #tbl_bls_a4_print {
            color: #000;
        }

        #tbl_bls_a4_print th,
        #tbl_bls_a4_print td {
            padding: 2px;
            vertical-align: top;
        }

        #tbl_bls_a4_print .rtoff {
            font-weight: bolder;
        }

        #tbl_bls_a4_print .rtoff-x {
            max-width: 200px;
            display: inline-block;
        }


        #tbl_bls_a4_print .rtoff-slno {
            display: inline-block;
            font-size: 25px;
            font-weight: bold;
        }

        #tbl_bls_a4_print .rtoff:not(:first-child) {
            padding-left: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        #tbl_bls_a4_print .prda4-export {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
        }

        #tbl_bls_a4_print .prda4-export .text-right {
            text-align: right;
        }

        #tbl_bls_a4_print .inln {
            clear: both;
        }

        #tbl_bls_a4_print .inln div:first-child {
            text-align: left;
            float: left;
        }

        #tbl_bls_a4_print .inln div:last-child {
            text-align: right;
            float: right;
        }

        #tbl_bls_a4_print .prda4-export th,
        #tbl_bls_a4_print .prda4-export td {
            border: 1px solid black;
        }


        #tbl_bls_a4_print .foot-tot-title {
            border-bottom: 2px solid black;
            color: red;
            font-size: 30px;
            font-weight: bolder;
            text-align: center;
        }

        #tbl_bls_a4_print .foot-tot {
            padding: 2px 3px;
            display: inline-block;
            border: 1px solid #000;
            border-radius: 5px;
            margin: 5px;
            font-size: 20px;
            font-weight: bolder;
        }
    </style>
    <table class="print-table" id="tbl_bls_a4_print" data-exportTitle_1="<?= $client['clnt_print_name'] ?>" data-exportTitle_2="BILLS LIST" data-exportTitle_3="List of bills">

        <tbody></tbody>

    </table>
</div>


<hr>


<h2 class="bg-warning text-center">TO EXPORT EXCEL</h2>
<!-- data-file-format attribute can have two values, EXCEL/CSV. It is used when export to excel. -->
<table class="print-table-excel" id="tbl_bls_a4_excel" data-file-format="EXCEL" data-exportTitle_1="<?= $client['clnt_print_name'] ?>" data-exportTitle_2="BILLS LIST" data-exportTitle_3="List of bills">

    <tbody></tbody>
    <tfoot></tfoot>

</table>