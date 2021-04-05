<?php


function getPeriod() {
    $objDate = new DateNow();
    $currentPeriod_int = $objDate->intPeriod();
    $currentPeriod_str = $objDate->strPeriod();

    $EndYYYYmm = 2001;
    $objPeriod = new generatePeriod($currentPeriod_int, $EndYYYYmm);
    $setofPeriod = $objPeriod->generatePeriod3();

    return $setofPeriod;
}

function getDayList() {
    $period = $received_data->period;
    $year = intval('20' . substr($period, 0, 2));
    $month = intval(substr($period, -2));
    $dayList = cal_days_in_month(CAL_GREGORIAN, $month, $year);
#echo "days = $dayList\n";
    $curDate = intval(date('d'));
    if ($curDate > $dayList) {
        $curDate = $dayList;
    }
//        echo "curDate = $curDate\n";
    $out_arr = array('numberofdays' => $dayList, 'currentday' => $curDate);
    return $out_arr;
}

function getDailySalesRecord() {
    $period = $received_data->period;
    $day = $received_data->day;
    $day = sprintf('%02d', $day);
    $year = '20' . substr($period, 0, 2);
    $month = substr($period, -2);
    $month = sprintf('%02d', $month);
    $dateYMD = $year . '-' . $month . '-' . $day;
#echo "dateYMD = $dateYMD\n";
//get the salesperson list
    $salesreport_arr = array();
    try {
        $salespersonList = get_SalesPersonList();
        if ($salespersonList == 'empty') {
            Throw new Exception('Cannot find SalesPerson List');
        }
        $i = 0;
        $totalcounttransaction = 0;
        $totalamounttransaction = 0;
        foreach ($salespersonList as $salesperson_row) {
            $aid = $salesperson_row['aid'];
            $name = $salesperson_row['name'];
            $currency = $salesperson_row['currency'];
            $currency_datarow = get_Currency($currency);
            if ($currency_datarow == 'empty') {
                $currIndicator = 'RM';
                $currDecimal = '.';
                $currComma = ',';
            } else {
                $currIndicator = $currency_datarow['currencysymbol'];
                $currDecimal = $currency_datarow['decimalsymbol'];
                $currComma = $currency_datarow['commasymbol'];
            }
            $salesreport_arr[$i]['No'] = $i + 1;
            $salesreport_arr[$i]['aid'] = $aid;
            $salesreport_arr[$i]['name'] = $name;
//                echo "testing for $name \n";
//get value and transaction amount
            $transactDetails = get_TransactionCountAndAmount($period, $day, $aid);
            $totaltransaction = $transactDetails['totaltransaction'];
            $totalamount = $transactDetails['totalamount'];
            $salesreport_arr[$i]['totaltransaction'] = $totaltransaction;
            $salesreport_arr[$i]['totalamount'] = $currIndicator . " " . number_format($totalamount, 2, $currDecimal, $currComma);
            $totalcounttransaction += $totaltransaction;
            $totalamounttransaction += $totalamount;

            $i++;
//                echo "\n------===------\n";
        }
        $out_arr = array('status' => 'ok', 'detail' => $salesreport_arr, 'total_all_transaction' => $totalcounttransaction, 'total_all_amount' => $totalamounttransaction);
    } catch (Exception $e) {
        $out_arr = array('status' => 'error', 'msg' => $e->getMessage());
    }
    return $out_arr;
}

function get_distinct_salesperson($array, $key) { //https://www.php.net/manual/en/function.array-unique.php
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[] = $val[$key];
            $temp_array[]['aid'] = $val[$key];
        }
        $i++;
    }

    foreach ($temp_array as $index => $row) {
        $ck_aid = $row['aid'];
        $ck_count = 0;
        foreach ($array as $val) {
            if ($val['aid_cus'] == $ck_aid) {
                $ck_count++;
            }
        }
        $temp_array[$index]['count'] = $ck_count;
    }
    return $temp_array;
}

function get_PriceByDateQuonoAid($DSRDetails_dataset) {
    $amountmat = 0;
    $discountmat = 0;
    $gstmat = 0;
    $amountsubtotalmat = 0;
    $amountpmach = 0;
    $discountpmach = 0;
    $gstpmach = 0;
    $amountsubtotalpmach = 0;
    $amountcncmach = 0;
    $discountcncmach = 0;
    $gstcncmach = 0;
    $amountsubtotalcncmach = 0;
    $amountother = 0;
    $discountother = 0;
    $gstother = 0;
    $amountsubtotalother = 0;
    $totalamount = 0;
    foreach ($DSRDetails_dataset as $DSRDetails_datarow) {
//Get the values of each item of orderlist
        $dsr_amountmat = (float) $DSRDetails_datarow['amountmat'];
        $dsr_discountmat = (float) $DSRDetails_datarow['discountmat'];
        $dsr_gstmat = (float) $DSRDetails_datarow['gstmat'];
        $dsr_subtotalmat = $dsr_amountmat - $dsr_discountmat + $dsr_gstmat;

        $dsr_amountpmach = (float) $DSRDetails_datarow['amountpmach'];
        $dsr_discountpmach = (float) $DSRDetails_datarow['discountpmach'];
        $dsr_gstpmach = (float) $DSRDetails_datarow['gstpmach'];
        $dsr_subtotalpmach = $dsr_amountpmach - $dsr_discountpmach + $dsr_gstpmach;

        $dsr_amountcncmach = (float) $DSRDetails_datarow['amountcncmach'];
        $dsr_discountcncmach = (float) $DSRDetails_datarow['discountcncmach'];
        $dsr_gstcncmach = (float) $DSRDetails_datarow['gstcncmach'];
        $dsr_subtotalcncmach = $dsr_amountcncmach - $dsr_discountcncmach + $dsr_gstcncmach;

        $dsr_amountother = (float) $DSRDetails_datarow['amountother'];
        $dsr_discountother = (float) $DSRDetails_datarow['discountother'];
        $dsr_gstother = (float) $DSRDetails_datarow['gstother'];
        $dsr_subtotalother = $dsr_amountother - $dsr_discountother + $dsr_gstother;

//Sum each items into one variable//
        $amountmat += $dsr_amountmat;
        $discountmat += $dsr_discountmat;
        $gstmat += $dsr_gstmat;
        /**/$amountsubtotalmat += $dsr_subtotalmat;
        $amountpmach += $dsr_amountpmach;
        $discountpmach += $dsr_discountpmach;
        $gstpmach += $dsr_gstpmach;
        /**/$amountsubtotalpmach += $dsr_subtotalpmach;
        $amountcncmach += $dsr_amountcncmach;
        $discountcncmach += $dsr_discountcncmach;
        $gstcncmach += $dsr_gstcncmach;
        /**/$amountsubtotalcncmach += $dsr_subtotalcncmach;
        $amountother += $dsr_amountother;
        $discountother += $dsr_discountother;
        $gstother += $dsr_gstother;
        /**/$amountsubtotalother += $dsr_subtotalother;
    }
    echo "\$amountmat = $amountmat<br>";
    echo "\$discountmat = $discountmat<br>";
    echo "\$gstmat = $gstmat<br>";
    echo "\$amountsubtotalmat = $amountsubtotalmat<br>";
    echo "\$amountpmach = $amountpmach<br>";
    echo "\$discountpmach = $discountpmach<br>";
    echo "\$gstpmach = $gstpmach<br>";
    echo "\$amountsubtotalpmach = $amountsubtotalpmach<br>";
    echo "\$amountcncmach = $amountcncmach<br>";
    echo "\$discountcncmach = $discountcncmach<br>";
    echo "\$gstcncmach = $gstcncmach<br>";
    echo "\$amountsubtotalcncmach = $amountsubtotalcncmach<br>";
    echo "\$amountother = $amountother<br>";
    echo "\$discountother = $discountother<br>";
    echo "\$gstother = $gstother<br>";
    echo "\$amountsubtotalother = $amountsubtotalother<br>";
    $totalamount = ($amountsubtotalmat + $amountsubtotalpmach + $amountsubtotalcncmach + $amountsubtotalother);
    echo "<b>\$totalamount = $totalamount<br></b>";

    $priceList = array(
        'amountmat' => $amountmat,
        'discountmat' => $discountmat,
        'gstmat' => $gstmat,
        'amountsubtotalmat' => $amountsubtotalmat,
        'amountpmach' => $amountpmach,
        'discountpmach' => $discountpmach,
        'gstpmach' => $gstpmach,
        'amountsubtotalpmach' => $amountsubtotalpmach,
        'amountcncmach' => $amountcncmach,
        'discountcncmach' => $discountcncmach,
        'gstcncmach' => $gstcncmach,
        'amountsubtotalcncmach' => $amountsubtotalcncmach,
        'amountother' => $amountother,
        'discountother' => $discountother,
        'gstother' => $gstother,
        'amountsubtotalother' => $amountsubtotalother,
        'totalamount' => $totalamount
    );

    return $priceList;
}

function insert_test_dailyReportTable($tab, $datarow) {
    $qr = "INSERT INTO $tab SET ";
    $arrCount = count($datarow);
    $cnt = 0;
    foreach ($datarow as $key => $val) {
        $cnt ++;
        $qr .= " $key =:$key ";
        if ($cnt != $arrCount) {
            $qr .= " , ";
        }
    }
    $objSQL = new SQLBINDPARAM($qr, $datarow);
    $insResult = $objSQL->InsertData2();
    if ($insResult == 'insert ok!'){
        return 'ok';
    }else{
        return 'fail';
    }
    
}

function generate_test_dailyReportTable($tab) {
    $chkTab = checkTableExists($tab);
    if ($chkTab == 'YES') {
        echo "$tab already exists. Truncating previous table first....<br>";
        $qrTrunc = "TRUNCATE TABLE $tab";
        $objSQLTrunc = new SQL($qrTrunc);
        $result = $objSQLTrunc->ExecuteQuery();
        if ($result == 'execute ok!') {
            echo "Successfully emptied old records in $tab<br>";
        } else {
            echo "Failed to empty $tab.<br>";
            exit();
        }
    } else {
        echo "$tab cannot be found. Creating the table....<br>";
        $qrCRTable = "CREATE TABLE `$tab` (
                        `dsrid` INT NOT NULL AUTO_INCREMENT,
                        `date` DATE NOT NULL,
                        `quono` VARCHAR(50) NOT NULL,
                        `aid_cus` INT(10) NOT NULL DEFAULT 0,
                        `salesperson` VARCHAR(50) NOT NULL,
                        `currency` INT(2) NOT NULL DEFAULT 0,
                        `amountmat` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `discountmat` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `gstmat` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `amountsubtotalmat` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `amountpmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `discountpmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `gstpmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `amountsubtotalpmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `amountcncmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `discountcncmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `gstcncmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `amountsubtotalcncmach` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `amountother` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `discountother` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `gstother` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `amountsubtotalother` FLOAT(20,2) NOT NULL DEFAULT 0,
                        `totalamount` FLOAT(20,2) NOT NULL DEFAULT 0,
                        INDEX `dsrid` (`dsrid`)
                )
                COLLATE='utf8mb4_general_ci'
                ;
                ";
        $objSQLCRTab = new SQL($qrCRTable);
        $result = $objSQLCRTab->ExecuteQuery();
        if ($result == 'execute ok!') {
            echo "Successfully created $tab...<br>";
        } else {
            echo "Failed creating $tab....<br>";
        }
    }
}
