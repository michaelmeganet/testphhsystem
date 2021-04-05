<?php

function get_DSRMaterialType($grade,$com) {
    $mattab1 = 'material';
    $mattab2 = 'material2020';
    if (checkTableExists($mattab2) == 'YES') {
        $tab = $mattab2;
    } else {
        $tab = $mattab1;
    }
    $qr = "SELECT * FROM $tab WHERE materialcode = '$grade' AND com = '$com'";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)){
        $mattype = $result['materialtype'];
        return $mattype;
    }else{
        $mattype = 'other';
    }
}

function get_nextDSRPeriod($year, $month) {
    $nextdat = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
    $nextDSRPeriod = sprintf("%02d", substr($nextdat, 2, 2)) . sprintf("%02d", substr($nextdat, 5, 2));
    ;
    return $nextDSRPeriod;
}

function get_nextMYDate($year, $month) {
//    $nextdat = date('Y-m-d',mktime(0, 0, 0, $month, 1, $year));
    $nextdat = date('m-Y', mktime(0, 0, 0, $month, 1, $year));
//    $nextDSRPeriod = sprintf("%02d", substr($nextdat, 2, 2)) . sprintf("%02d", substr($nextdat, 5, 2));;
//    return $nextDSRPeriod;
    return $nextdat;
}

function get_adminDetail($aid) {
    $qr = "SELECT * FROM admin WHERE aid = $aid AND status <> 'deleted' ";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

function get_SalesPersonList() {
    $tab = 'admin';
    $qr = "SELECT * FROM $tab WHERE issalesperson = 'yes' AND status NOT LIKE 'deleted' ORDER BY salesperson_order ASC";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

function get_Currency($cid) {
    $qr = "SELECT * FROM currency WHERE cid = $cid";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

function get_DailySalesQuonoBySalesperson($period, $day, $aid) {
    //Fetch cutoff time
    $qrCO = "SELECT * FROM cutoff_time WHERE description = 'ol_cutoff'";
    $objSQLCO = new SQL($qrCO);
    $resultCO = $objSQLCO->getResultOneRowArray();
    if (!empty($resultCO)) {
        $CO_start = (string) $resultCO['start'];
        $CO_end = (string) $resultCO['end'];
//        echo "$CO_start  /  $CO_end\n";
    }
    $year = intval('20' . substr($period, 0, 2));
    $month = intval(substr($period, -2));
    $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
    $dateTime = $date . " " . $CO_end;

    $prevDate = date('Y-m-d', mktime(0, 0, 0, $month, $day - 1, $year));
    $prevDateTime = $prevDate . " " . $CO_start;
    $prevDateMonth = date('Y-m-d', mktime(0, 0, 0, $month - 1, 1, $year));
    $prevPeriod = substr($prevDateMonth, 2, 2) . substr($prevDateMonth, 5, 2);

    $nextDate = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
    $nextDateMonth = date('Y-m-d', mktime(0, 0, 0, $month + 1, 28, $year));
    $nextPeriod = substr($nextDate, 2, 2) . substr($nextDateMonth, 5, 2);
//    echo "Date = $Date\n";
//    echo "PrevDate = $prevDate; ";
//    echo "prevPeriod = $prevPeriod\n";
//    echo "NextDate = $nextDate; ";
//    echo "nextPeriod = $nextPeriod\n";
    //GENERATE TABLES
    $ordtabPST = "orderlistnew_pst_$period";
    $nextordtabPST = "orderlistnew_pst_$nextPeriod";
    $prevordtabPST = "orderlistnew_pst_$prevPeriod";

    $ordtabPSVPMB = "orderlistnew_psvpmb_$period";
    $nextordtabPSVPMB = "orderlistnew_psvpmb_$nextPeriod";
    $prevordtabPSVPMB = "orderlistnew_psvpmb_$prevPeriod";
    
    if (strtolower($aid) != 'all'){
        $aid_qrext = "AND aid_cus = $aid ";
    }else{
        $aid_qrext = " ";
    }

    $qrCurPST = "SELECT DISTINCT quono,currency,bid,cid,aid_cus "
            . "FROM $ordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "$aid_qrext "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrNextPST = "SELECT DISTINCT quono,currency,bid,cid,aid_cus "
            . "FROM $nextordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "$aid_qrext "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrPrevPST = "SELECT DISTINCT quono,currency,bid,cid,aid_cus "
            . "FROM $prevordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "$aid_qrext "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    //check if next month table exists or not
    //Tested on 01042021
    //UNION multiple Queries, and sum them at the same time:
    //https://stackoverflow.com/questions/2387734/a-simple-way-to-sum-a-result-from-union-in-mysql
    //Group them distinctly by Quono and CID :
    //https://stackoverflow.com/questions/2421388/using-group-by-on-multiple-columns
    if (checkTableExists($nextordtabPST) == 'YES') {
        $qrPDSPST = "SELECT * "
                . " FROM( " . $qrNextPST . " UNION ALL " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem "
                . "ORDER BY aid_cus";
    } else {
        $qrPDSPST = "SELECT * "
                . " FROM( " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem "
                . "ORDER BY aid_cus";
    }
    $objSQLPDSPST = new SQL($qrPDSPST);
    $PDSPSTQuonoSet = $objSQLPDSPST->getResultRowArray();
    return $PDSPSTQuonoSet;
}

function get_DSRRecordsByQuonoCid($period, $day, $quono, $cid, $aid) {//Fetch cutoff time
    $qrCO = "SELECT * FROM cutoff_time WHERE description = 'ol_cutoff'";
    $objSQLCO = new SQL($qrCO);
    $resultCO = $objSQLCO->getResultOneRowArray();
    if (!empty($resultCO)) {
        $CO_start = (string) $resultCO['start'];
        $CO_end = (string) $resultCO['end'];
//        echo "$CO_start  /  $CO_end\n";
    }
    $year = intval('20' . substr($period, 0, 2));
    $month = intval(substr($period, -2));
    $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
    $dateTime = $date . " " . $CO_end;

    $prevDate = date('Y-m-d', mktime(0, 0, 0, $month, $day - 1, $year));
    $prevDateTime = $prevDate . " " . $CO_start;
    $prevDateMonth = date('Y-m-d', mktime(0, 0, 0, $month - 1, 1, $year));
    $prevPeriod = substr($prevDateMonth, 2, 2) . substr($prevDateMonth, 5, 2);

    $nextDate = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
    $nextDateMonth = date('Y-m-d', mktime(0, 0, 0, $month + 1, 28, $year));
    $nextPeriod = substr($nextDate, 2, 2) . substr($nextDateMonth, 5, 2);
//    echo "Date = $Date\n";
//    echo "PrevDate = $prevDate; ";
//    echo "prevPeriod = $prevPeriod\n";
//    echo "NextDate = $nextDate; ";
//    echo "nextPeriod = $nextPeriod\n";
    //GENERATE TABLES
    $ordtabPST = "orderlistnew_pst_$period";
    $nextordtabPST = "orderlistnew_pst_$nextPeriod";
    $prevordtabPST = "orderlistnew_pst_$prevPeriod";

    $ordtabPSVPMB = "orderlistnew_psvpmb_$period";
    $nextordtabPSVPMB = "orderlistnew_psvpmb_$nextPeriod";
    $prevordtabPSVPMB = "orderlistnew_psvpmb_$prevPeriod";

    if (strtolower($aid) != 'all'){
        $aid_qrext = "AND aid_cus = $aid ";
    }else{
        $aid_qrext = " ";
    }
    
    $qrCurPST = "SELECT * "
            . "FROM $ordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "$aid_qrext "
            . "AND quono = '$quono' "
            . "AND cid = $cid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrNextPST = "SELECT * "
            . "FROM $nextordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "$aid_qrext "
            . "AND quono = '$quono' "
            . "AND cid = $cid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrPrevPST = "SELECT * "
            . "FROM $prevordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "$aid_qrext "
            . "AND quono = '$quono' "
            . "AND cid = $cid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    //check if next month table exists or not
    //Tested on 01042021
    //UNION multiple Queries, and sum them at the same time:
    //https://stackoverflow.com/questions/2387734/a-simple-way-to-sum-a-result-from-union-in-mysql
    //Group them distinctly by Quono and CID :
    //https://stackoverflow.com/questions/2421388/using-group-by-on-multiple-columns
    if (checkTableExists($nextordtabPST) == 'YES') {
        $qrPDSPST = $qrNextPST . " UNION ALL " . $qrCurPST . " UNION ALL " . $qrPrevPST;
    } else {
        $qrPDSPST = $qrCurPST . " UNION ALL " . $qrPrevPST;
    }
    $objSQLPDSPST = new SQL($qrPDSPST);
    $PDSPSTDetails = $objSQLPDSPST->getResultRowArray();
    return $PDSPSTDetails;
}

function get_TransactionCountAndAmount($period, $day, $aid) {
    //Fetch cutoff time
    $qrCO = "SELECT * FROM cutoff_time WHERE description = 'ol_cutoff'";
    $objSQLCO = new SQL($qrCO);
    $resultCO = $objSQLCO->getResultOneRowArray();
    if (!empty($resultCO)) {
        $CO_start = (string) $resultCO['start'];
        $CO_end = (string) $resultCO['end'];
//        echo "$CO_start  /  $CO_end\n";
    }
    $year = intval('20' . substr($period, 0, 2));
    $month = intval(substr($period, -2));
    $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
    $dateTime = $date . " " . $CO_end;

    $prevDate = date('Y-m-d', mktime(0, 0, 0, $month, $day - 1, $year));
    $prevDateTime = $prevDate . " " . $CO_start;
    $prevDateMonth = date('Y-m-d', mktime(0, 0, 0, $month - 1, 1, $year));
    $prevPeriod = substr($prevDateMonth, 2, 2) . substr($prevDateMonth, 5, 2);

    $nextDate = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
    $nextDateMonth = date('Y-m-d', mktime(0, 0, 0, $month + 1, 28, $year));
    $nextPeriod = substr($nextDate, 2, 2) . substr($nextDateMonth, 5, 2);
//    echo "Date = $Date\n";
//    echo "PrevDate = $prevDate; ";
//    echo "prevPeriod = $prevPeriod\n";
//    echo "NextDate = $nextDate; ";
//    echo "nextPeriod = $nextPeriod\n";
    //GENERATE TABLES
    $ordtabPST = "orderlistnew_pst_$period";
    $nextordtabPST = "orderlistnew_pst_$nextPeriod";
    $prevordtabPST = "orderlistnew_pst_$prevPeriod";

    $ordtabPSVPMB = "orderlistnew_psvpmb_$period";
    $nextordtabPSVPMB = "orderlistnew_psvpmb_$nextPeriod";
    $prevordtabPSVPMB = "orderlistnew_psvpmb_$prevPeriod";

    //BEGIN TOTAL TRANSACTION COUNT

    $qrCurPST = "SELECT DISTINCT quono "
            . "FROM $ordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00'";
    $qrNextPST = "SELECT DISTINCT quono "
            . "FROM $nextordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00'";
    $qrPrevPST = "SELECT DISTINCT quono "
            . "FROM $prevordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00'";
    //check if next month table exists or not
    //Tested on 01042021
    //UNION multiple Queries, and Count them  at the same time:
    //https://stackoverflow.com/questions/2387734/a-simple-way-to-sum-a-result-from-union-in-mysql
    if (checkTableExists($nextordtabPST) == 'YES') {
        $qrPDSPST = "SELECT COUNT(*) FROM( " . $qrNextPST . " UNION ALL " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
    } else {
        $qrPDSPST = "SELECT COUNT(*) FROM( " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
    }
    $objSQLPDSPST = new SQL($qrPDSPST);
    $PDSPSTCount = $objSQLPDSPST->getRowCount();
    #echo "PST QR = $qrPDSPST\n";
//    echo "Transaction in PST = $PDSPSTCount\n";

    $qrCurPSVPMB = "SELECT DISTINCT quono "
            . "FROM $ordtabPSVPMB "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00'";
    $qrNextPSVPMB = "SELECT DISTINCT quono "
            . "FROM $nextordtabPSVPMB "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00'";
    $qrPrevPSVPMB = "SELECT DISTINCT quono "
            . "FROM $prevordtabPSVPMB "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00'";
    //check if next month table exists or not
    if (checkTableExists($nextordtabPSVPMB) == 'YES') {
        $qrPDSPSVPMB = "SELECT COUNT(*) FROM( " . $qrNextPSVPMB . " UNION ALL " . $qrCurPSVPMB . " UNION ALL " . $qrPrevPSVPMB . ") AS tem";
    } else {
        $qrPDSPSVPMB = "SELECT COUNT(*) FROM( " . $qrCurPSVPMB . " UNION ALL " . $qrPrevPSVPMB . ") AS tem";
    }
    $objSQLPDSPSVPMB = new SQL($qrPDSPSVPMB);
    $PDSPSVPMBCount = $objSQLPDSPSVPMB->getRowCount();
    #echo "PSVPMB QR = $qrPDSPSVPMB\n";
//    echo "Transaction in PSVPMB = $PDSPSVPMBCount\n";

    $totaltransaction = intval($PDSPSTCount) + intval($PDSPSVPMBCount);
    //END TOTAL TRANSACTION COUNT
    //BEGIN TOTAL AMOUNT SUM

    $qrCurPST = "SELECT * "
            . "FROM $ordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrNextPST = "SELECT * "
            . "FROM $nextordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrPrevPST = "SELECT * "
            . "FROM $prevordtabPST "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    //check if next month table exists or not
    //Tested on 01042021
    //UNION multiple Queries, and sum them at the same time:
    //https://stackoverflow.com/questions/2387734/a-simple-way-to-sum-a-result-from-union-in-mysql
    if (checkTableExists($nextordtabPST) == 'YES') {
        $qrPDSPST = "SELECT "
                . "SUM(amountmat) as amountmat, SUM(discountmat) as discountmat, SUM(gstmat) as gstmat, "
                . "SUM(amountpmach) as amountpmach, SUM(discountpmach) as discountpmach, SUM(gstpmach) as gstpmach, "
                . "SUM(amountcncmach) as amountcncmach, SUM(discountcncmach) as discountcncmach, SUM(gstcncmach) as gstcncmach, "
                . "SUM(amountother) as amountother, SUM(discountother) as discountother, SUM(gstother) as gstother"
                . " FROM( " . $qrNextPST . " UNION ALL " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
    } else {
        $qrPDSPST = "SELECT "
                . "SUM(amountmat) as amountmat, SUM(discountmat) as discountmat, SUM(gstmat) as gstmat, "
                . "SUM(amountpmach) as amountpmach, SUM(discountpmach) as discountpmach, SUM(gstpmach) as gstpmach, "
                . "SUM(amountcncmach) as amountcncmach, SUM(discountcncmach) as discountcncmach, SUM(gstcncmach) as gstcncmach, "
                . "SUM(amountother) as amountother, SUM(discountother) as discountother, SUM(gstother) as gstother"
                . " FROM( " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
    }
    $objSQLPDSPST = new SQL($qrPDSPST);
//    echo "PST QR = $qrPDSPST\n";
    $PDSPSTSumRecord = $objSQLPDSPST->getResultOneRowArray();
    if (!empty($PDSPSTSumRecord)) {
        $pst_price_mat = (float) $PDSPSTSumRecord['amountmat'] - (float) $PDSPSTSumRecord['discountmat'] + (float) $PDSPSTSumRecord['gstmat'];
        $pst_price_pmach = (float) $PDSPSTSumRecord['amountpmach'] - (float) $PDSPSTSumRecord['discountpmach'] + (float) $PDSPSTSumRecord['gstpmach'];
        $pst_price_cncmach = (float) $PDSPSTSumRecord['amountcncmach'] - (float) $PDSPSTSumRecord['discountcncmach'] + (float) $PDSPSTSumRecord['gstcncmach'];
        $pst_price_other = (float) $PDSPSTSumRecord['amountother'] - (float) $PDSPSTSumRecord['discountother'] + (float) $PDSPSTSumRecord['gstother'];
    } else {
        $pst_price_mat = 0;
        $pst_price_pmach = 0;
        $pst_price_cncmach = 0;
        $pst_price_other = 0;
    }

    $qrCurPSVPMB = "SELECT * "
            . "FROM $ordtabPSVPMB "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrNextPSVPMB = "SELECT * "
            . "FROM $nextordtabPSVPMB "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    $qrPrevPSVPMB = "SELECT * "
            . "FROM $prevordtabPSVPMB "
            . "WHERE datetimeissue_ol >= '$prevDateTime' "
            . "AND datetimeissue_ol <= '$dateTime' "
            . "AND aid_cus = $aid "
            . "AND `ivdate` <> '0000-00-00' "
            . "AND `status` = 'active' ";
    //check if next month table exists or not
    if (checkTableExists($nextordtabPSVPMB) == 'YES') {
        $qrPDSPSVPMB = "SELECT "
                . "SUM(amountmat) as amountmat, SUM(discountmat) as discountmat, SUM(gstmat) as gstmat, "
                . "SUM(amountpmach) as amountpmach, SUM(discountpmach) as discountpmach, SUM(gstpmach) as gstpmach, "
                . "SUM(amountcncmach) as amountcncmach, SUM(discountcncmach) as discountcncmach, SUM(gstcncmach) as gstcncmach, "
                . "SUM(amountother) as amountother, SUM(discountother) as discountother, SUM(gstother) as gstother"
                . " FROM( " . $qrNextPSVPMB . " UNION ALL " . $qrCurPSVPMB . " UNION ALL " . $qrPrevPSVPMB . ") AS tem";
    } else {
        $qrPDSPSVPMB = "SELECT "
                . "SUM(amountmat) as amountmat, SUM(discountmat) as discountmat, SUM(gstmat) as gstmat, "
                . "SUM(amountpmach) as amountpmach, SUM(discountpmach) as discountpmach, SUM(gstpmach) as gstpmach, "
                . "SUM(amountcncmach) as amountcncmach, SUM(discountcncmach) as discountcncmach, SUM(gstcncmach) as gstcncmach, "
                . "SUM(amountother) as amountother, SUM(discountother) as discountother, SUM(gstother) as gstother"
                . " FROM( " . $qrCurPSVPMB . " UNION ALL " . $qrPrevPSVPMB . ") AS tem";
    }
    $objSQLPDSPSVPMB = new SQL($qrPDSPSVPMB);
    
//    echo "PSVPMB QR = $qrPDSPSVPMB\n";
    $PDSPSVPMBSumRecord = $objSQLPDSPSVPMB->getResultOneRowArray();
    if (!empty($PDSPSVPMBSumRecord)) {
        $psvpmb_price_mat = (float) $PDSPSVPMBSumRecord['amountmat'] - (float) $PDSPSVPMBSumRecord['discountmat'] + (float) $PDSPSVPMBSumRecord['gstmat'];
        $psvpmb_price_pmach = (float) $PDSPSVPMBSumRecord['amountpmach'] - (float) $PDSPSVPMBSumRecord['discountpmach'] + (float) $PDSPSVPMBSumRecord['gstpmach'];
        $psvpmb_price_cncmach = (float) $PDSPSVPMBSumRecord['amountcncmach'] - (float) $PDSPSVPMBSumRecord['discountcncmach'] + (float) $PDSPSVPMBSumRecord['gstcncmach'];
        $psvpmb_price_other = (float) $PDSPSVPMBSumRecord['amountother'] - (float) $PDSPSVPMBSumRecord['discountother'] + (float) $PDSPSVPMBSumRecord['gstother'];
    } else {
        $psvpmb_price_mat = 0;
        $psvpmb_price_pmach = 0;
        $psvpmb_price_cncmach = 0;
        $psvpmb_price_other = 0;
    }

    $totalmat = $pst_price_mat + $psvpmb_price_mat;
    $totalpmach = $pst_price_pmach + $psvpmb_price_pmach;
    $totalcncmach = $pst_price_cncmach + $psvpmb_price_cncmach;
    $totalother = $pst_price_other + $psvpmb_price_other;

    $totalamount = $totalmat + $totalpmach + $totalcncmach + $totalother;
//    echo "pstmat = $pst_price_mat, psvpmbmat = $psvpmb_price_mat, total = $totalmat\n";
//    echo "pstpmach = $pst_price_pmach, psvpmbpmach = $psvpmb_price_pmach, total = $totalpmach\n";
//    echo "pstcncmach = $pst_price_cncmach, psvpmbcncmach = $psvpmb_price_cncmach, total = $totalcncmach\n";
//    echo "pstother = $pst_price_other, psvpmbother = $psvpmb_price_other, total = $totalother\n";
//    echo "totalamount = $totalamount\n";
    
    $result_arr = array('totaltransaction' => $totaltransaction, 'totalamount' => $totalamount);
    return $result_arr;
}

function checkTableExists($matTable) {
    $qr = "SHOW TABLES LIKE '%$matTable%'";
    $objSQL = new SQL($qr);
    $results = $objSQL->getResultOneRowArray();
    if (!empty($results)) {
        return 'YES';
    } else {
        return 'NO';
    }
}
