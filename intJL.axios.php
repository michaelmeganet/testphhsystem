<?php

include_once 'class/dbh.inc.php';
include_once 'class/phhdate.inc.php';
include_once 'class/variables.inc.php';
include_once 'class/quotation.inc.php';
include_once 'class/customers.inc.php';
include_once 'class/joblistwork.inc.php';

$received_data = json_decode(file_get_contents("php://input"));

$data_output = array();
$action = $received_data->action;

function parseJobcode($jobcode) {
    $jclength = strlen($jobcode);
    #echo "jc_length = $jc_length.\n";
    #echo "strpos [ = " . strpos($jobcode, '[') . '\n';
    if (strpos($jobcode, '[') === 0) {
        //this is new jobcode
        #echo "new jobcode\n";
        $endpos = strpos($jobcode, ']');
        $cleanJobCode = trim(substr($jobcode, 1, $endpos - 1));
    } else {
        #echo "old jobcode\n";
        //this is old jobcode
        if ($jclength <= 28) {
            # echo "ok\n";
            $cleanJobCode = trim($jobcode);
        } else {
            # echo "fail\n";
            return 'fail';
        }
    }
    #echo "cleanjobcode = $cleanJobCode<br>";
    if (strlen($cleanJobCode) == 28 || strlen($cleanJobCode) == 24) {
        $len = strlen($cleanJobCode) - 5;
        $cleanJobCode = substr($cleanJobCode, 0, $len);
        #echo "cleanjobcode = $cleanJobCode<br>" . strlen($cleanJobCode);
    }
    if (strlen($cleanJobCode) == 19 || strlen($cleanJobCode) == 23) {
        return $cleanJobCode;
    } else {
        return 'fail';
    }
}

switch ($action) {
    case 'parseJobCode':
        $jobcode = $received_data->jobcode;
        $parseJobCode = parseJobcode($jobcode);
        if ($parseJobCode != 'fail') {
            $resp = array('status' => 'ok', 'msg' => $parseJobCode);
        } else {
            $resp = array('status' => 'error', 'msg' => 'Cannot parse Jobcode, Please Check the format');
        }
        echo json_encode($resp);
        break;
    case 'getJoblistDetail':
        $jobcode = parseJobcode($received_data->jobcode);
        try {
            if ($jobcode == 'fail') {
                throw new Exception('Cannot parse Jobcode, Please Check the Format');
            }
            $objJW2 = new JOB_WORK_2($jobcode);
            if ($objJW2->get_sid() == null) {
                throw new Exception('Cannot find records for ' . $jobcode . '.');
            } else {
                $sid = $objJW2->get_sid();
                $period = $objJW2->get_period();
                $sch_details = get_SchedulingDetailsBySidFromLocal($period, $sid);
                $out_arr = array('status' => 'ok', 'schPeriod' => $period, 'schDetail' => $sch_details);
            }
        } catch (Exception $ex) {
            $out_arr = array('status' => 'error', 'msg' => $ex->getMessage());
        }
        echo json_encode($out_arr);
        break;
    case 'generateIntermediateJL':
        $qid = $received_data->qid;
        $quono = $received_data->quono;
        $jobcode = $received_data->jobcode;
        #$origin_period = $received_data->origin_period;
        $intData = json_decode(json_encode($received_data->intData), true);
        var_dump($received_data);
        print_r($received_data);
        $objJW2 = new JOB_WORK_2($jobcode);
        $sid = $objJW2->get_sid();
        $period = $objJW2->get_period();
        $sch_details = get_SchedulingDetailsBySidFromLocal($period, $sid);
        $quo_details = get_QuotationDetailsByQidFromLocal($quono, $qid);
        $objPeriod = new Period();
        $currPeriod = $objPeriod->getcurrentPeriod();
        $int_quono = get_int_quono($currPeriod);
        echo "int_quono = $int_quono\n";
        print_r($quo_details);

        echo "test";

        break;
}

function get_int_quono($period) {
    $com = "pst";
    $quotab = "quotation_" . $com . "_" . $period;
    $qr = "SELECT DISTINCT quono FROM $quotab WHERE quono LIKE 'PRD%' ORDER BY quono DESC";
    $objSQL = new SQL($qr);
    echo "sql = $qr\n";
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        $quorunningno = (int) substr($result['quono'], -3) + 1;
    } else {
        $quorunningno = 1;
    }
    $int_quono = "PRD " . $period . " " . sprintf("%03d", $quorunningno);
    return $int_quono;
}

function get_SchedulingDetailsBySidFromLocal($period, $sid) {
    $tblname = 'production_scheduling_' . $period;
    $qr = "SELECT * FROM $tblname WHERE sid = $sid AND status = 'active'";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

/*
  function get_OrderlistDetailsByJoblistFromLocal($period, $joblist) {
  $com = "pst";
  $tblname = 'orderlist_'.$com.'_'.$period;
  $qr = "SELECT * FROM $tblname WHERE sid = $sid AND status = 'active'";
  $objSQL = new SQL($qr);
  $result = $objSQL->getResultOneRowArray();
  if (!empty($result)) {
  return $result;
  } else {
  return 'empty';
  }
  }
 * 
 */

function get_QuotationDetailsByQidFromLocal($quono, $qid) {
    $com = "pst";
    $period = substr($quono,4,4);
    $tblname = 'quotation_' . $com . "_" . $period;
    $qr = "SELECT * FROM $tblname WHERE qid = $qid AND quono = '$quono'";
    echo "qr = $qr\n";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

/*
function generate_intermediateArray($ori_quo_dataset,$int_data, $int_quono) {
    $dat = $ori_quo_dataset;
    $quoArray = array(
    'bid' => $dat['bid'],
    'currency' => $dat['currency'],
    'quono' => $int_quono,
    'company' => ,
    'pagetype' =>,
    'custype' =>,
    'cusstatus' =>,
    'cid' =>,
    'accno' =>,
    'date' =>,
    'terms' =>,
    'item' =>,
    'quantity' =>,
    'grade' =>,
    'mdt' =>,
    'mdw' =>,
    'mdl' =>,
    'fdt' =>,
    'fdw' =>,
    'fdl' =>,
    'process' =>,
    'mat' =>,
    'pmach' =>,
    'cncmach' =>,
    'other' =>,
    'unitprice' =>,
    'amount' =>,
    'discount' =>,
    'vat' =>,
    'gst' =>,
    'ftz' =>,
    'amountmat' =>,
    'discountmat' =>,
    'gstmat' =>,
    'totalamountmat' =>,
    'amountpmach' =>,
    'discountpmach' =>,
    'gstpmach' =>,
    'totalamountpmach' =>,
    'amountcncmach' =>,
    'discountcncmach' =>,
    'gstcncmach' =>,
    'totalamountcncmach' =>,
    'amountother' =>,
    'discountother' =>,
    'gstother' =>,
    'totalamountother' =>,
    'totalamount' =>,
    'mat_disc' =>,
    'pmach_disc' =>,
    'aid_quo' =>,
    'aid_cus' =>,
    'datetimeissue' =>,
    'odissue' =>,
    );
    return $quoArray;
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

