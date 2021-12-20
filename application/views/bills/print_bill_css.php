<style type="text/css">
    .bill-container {
        width: <?= $container_width ?>px;
        padding-left: <?= $container_px ?>px;
        padding-right: <?= $container_px ?>px;
        padding-top: <?= $container_py ?>px;
        padding-bottom: <?= $container_py ?>px;
        margin-left: <?= $container_ml ?>px;
        margin-top: <?= $container_mt ?>px;
        border: 1px solid #000;
    }

    .company {
        font-size: <?= $com_font_size ?>px;
        font-weight: bolder;
        text-align: center;
    }

    .bill-container .upper-part {
        width: 97%;
        padding: 10px 0;
    }

    .bill-container .upper-part #uprtbl {
        width: 100%;
    }

    .bill-container .upper-part #uprtbl td {
        width: 50%;
        padding: 5px 10px;
    }

    .bill-container .upper-part #uprtbl tr:first-child td {
        /* border-top: 1px solid #000; */
    }

    .bill-container .upper-part #uprtbl tr:last-child td {
        /* border-bottom: 1px solid #000; */
    }

    .bill-container .upper-part #uprtbl tr td:first-child {
        /* border-right: 1px solid #000; */
    }

    .bill-container .middle-part {
        width: 100%;
    }

    .tc {
        text-align: center;
    }

    .prd-table {
        width: 100%;
        border-collapse: collapse;
    }

    .prd-table th {
        text-align: center;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }

    .prd-table td {
        border-right: 1px solid #000;
        padding: 2px 5px;
    }

    .prd-table tr td:last-child {
        border-right: none;
    }


    .prd-table tr:last-child td {
        border-bottom: 1px solid #000;
    }

    .bottom-part {
        width: 100%;
    }

    #ftrtbl {
        width: 100%;
    }

    #ftrtbl tr td {
        width: 50%;
        padding: 5px 10px;
    }

    #ftrtbl tr td:first-child {
        text-align: left;
    }

    #ftrtbl tr td:last-child {
        text-align: right;
    }
</style>