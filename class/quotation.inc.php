<?php

include_once("dbh.inc.php");
include_once("variables.inc.php");

//function redirect($url) {
//    ob_start();
//    header('Location: '.$url);
//    ob_end_flush();
//    die();
//}

Class QUO_REMARKS {

    private $period;
    private $com;
    private $bid;
    private $cid;
    private $remarks1;
    private $remarks2;
    private $remarks3;
    private $remarks4;
    private $data_array = array();

    function __construct($postdata) {
        $this->extract_postdata($postdata);
        $this->create_data_array();
    }

    function extract_postdata($postdata) {
        $this->set_period($postdata['post_period']);
        $this->set_quono($postdata['quono']);
        $this->set_com($postdata['com']);
        $this->set_bid($postdata['bid']);
        $this->set_cid($postdata['post_cid']);
        $this->set_remarks1($postdata['remarks1']);
        $this->set_remarks2($postdata['remarks2']);
        $this->set_remarks3($postdata['remarks3']);
        $this->set_remarks4($postdata['remarks4']);
    }

    function create_data_array() {
        $data_array = array(
            'bid' => $this->get_bid(),
            'quono' => $this->get_quono(),
            'cid' => $this->get_cid(),
            'remarks1' => $this->get_remarks1(),
            'remarks2' => $this->get_remarks2(),
            'remarks3' => $this->get_remarks3(),
            'remarks4' => $this->get_remarks4()
        );
        $this->set_data_array($data_array);
    }

    function create_remarks() {
        $period = trim($this->get_period());
        $com = trim(strtolower($this->get_com()));
        $quoremtab = 'quotation_remarks_' . $com . '_' . $period;
        $checkExists = $this->check_table_exists($quoremtab);
        if ($checkExists != 'exist') {
            $createResult = $this->create_remark_table($quoremtab);
            echo $createResult . "<br>";
        }
        $data_array = $this->get_data_array();
        $qr = "INSERT INTO $quoremtab SET ";
        $cnt = 0;
        $arrCount = count($data_array);
        foreach ($data_array as $rowKey => $rowVal) {
            $cnt++;
            $qr .= " $rowKey=:$rowKey ";
            if ($arrCount != $cnt) {
                $qr .= " , ";
            }
        }
#echo $qr;
        $objSQL = new SQLBINDPARAM($qr, $data_array);
        $result = $objSQL->InsertData2();
#echo "$result<br>";
        if ($result == 'insert ok!') {
            $info = 'Insert Successful!';
        } else {
            $info = 'Insert Failed';
        }
        return $info;
    }

    function check_table_exists($quoremtab) {
        $com = $this->get_com();
        $period = $this->get_period();
        $qr = "SHOW TABLES LIKE '$quoremtab'";
        $objSQL = new SQL($qr);
        $results = $objSQL->getResultOneRowArray();
        if ($results) {
            return 'exist';
        } else {
            return 'not exist';
        }
    }

    function create_remark_table($quoremtab) {
        $com = $this->get_com();
        $period = $this->get_period();
        $qr = "CREATE TABLE IF NOT EXISTS `$quoremtab` (
                        `bid` INT(10) UNSIGNED NOT NULL,
                        `quono` VARCHAR(20) NOT NULL,
                        `cid` INT(10) UNSIGNED NOT NULL,
                        `remarks1` VARCHAR(100) NULL DEFAULT NULL,
                        `remarks2` VARCHAR(100) NULL DEFAULT NULL,
                        `remarks3` VARCHAR(100) NULL DEFAULT NULL,
                        `remarks4` VARCHAR(100) NULL DEFAULT NULL
                )
                COLLATE='utf8_general_ci'
                ENGINE=MyISAM
                ;

                ";
        $objSQL = new SQL($qr);
        $result = $objSQL->ExecuteQuery();
        if ($result == 'execute ok!') {
            return 'Table Created';
        } else {
            return 'Fail to create table';
        }
    }

    function set_data_array($input) {
        $this->data_array = $input;
    }

    function get_data_array() {
        return $this->data_array;
    }

    function set_period($input) {
        $this->period = $input;
    }

    function set_quono($input) {
        $this->quono = $input;
    }

    function set_com($input) {
        $this->com = $input;
    }

    function set_bid($input) {
        $this->bid = $input;
    }

    function set_cid($input) {
        $this->cid = $input;
    }

    function set_remarks1($input) {
        $this->remarks1 = $input;
    }

    function set_remarks2($input) {
        $this->remarks2 = $input;
    }

    function set_remarks3($input) {
        $this->remarks3 = $input;
    }

    function set_remarks4($input) {
        $this->remarks4 = $input;
    }

    function get_period() {
        return $this->period;
    }

    function get_quono() {
        return $this->quono;
    }

    function get_com() {
        return $this->com;
    }

    function get_bid() {
        return $this->bid;
    }

    function get_cid() {
        return $this->cid;
    }

    function get_remarks1() {
        return $this->remarks1;
    }

    function get_remarks2() {
        return $this->remarks2;
    }

    function get_remarks3() {
        return $this->remarks3;
    }

    function get_remarks4() {
        return $this->remarks4;
    }

}

Class QUONO_LIST {

    private $id;
    private $bid;
    private $currency;
    private $quono;
    private $quotab;
    private $period;
    private $Shape_Code;
    private $category;
    private $specialShapeOrder;
    private $tabletype;
    private $company;
    private $cusstatus;
    private $custype;
    private $pagetype;
    private $cid;
    private $accno;
    private $date;
    private $terms;
    private $item;
    private $quantity;
    private $grade;
    private $mdt;
    private $mdw;
    private $mdl;
    private $dim_desp;
    private $fdt;
    private $fdw;
    private $fdl;
    private $finishing_dim_desp;
    private $process;
    private $mat;
    private $pmach;
    private $cncmach;
    private $other;
    private $ftz;
    private $amountmat;
    private $discountmat;
    private $gstmat;
    private $totalamountmat;
    private $amountpmach;
    private $discountpmach;
    private $gstpmach;
    private $totalamountpmach;
    private $amountcncmach;
    private $discountcncmach;
    private $gstcncmach;
    private $totalamountcncmach;
    private $amountother;
    private $discountother;
    private $gstother;
    private $totalamountother;
    private $totalamount;
    private $aid_quo;
    private $aid_cus;
    private $datetimeissue;
    private $volumeperunit;
    private $weightperunit;
    private $totalweight;
    private $density;
    private $priceperKG;
    private $QuonolistArray;
    private $quonolist_tbl;
    private $postdata;
    private $customerdata;

    function __construct($postdata, $customerdata) {
        $this->postdata = $postdata;
        $this->customerdata = $customerdata;
        $this->extractarray();
        $QuonolistArray = $this->createQuonolistArray();
        $this->set_QuonolistArray($QuonolistArray);
        $this->set_quonolist_table('quono_list');
#echo "<pre style='color:black;'>";
#echo "Lists Quono List Array : <br>";
#print_r($this->get_QuonolistArray());
#echo "</pre>";
    }

    function insertQuonolist() {
        $tblname = $this->get_quonolist_table();
        $QuonolistArray = $this->get_QuonolistArray();
        $qrIns = "INSERT INTO $tblname SET ";
#echo "Table Select : $tblname<br>";
        $cnt = 0;
        $arrCount = count($QuonolistArray);
        foreach ($QuonolistArray as $quonolistkey => $quonolistdata) {
            $cnt++;
            $qrIns .= " $quonolistkey=:$quonolistkey ";
            if ($arrCount != $cnt) {
                $qrIns .= " , ";
            }
        }
#echo $qrIns;
        $objSQL = new SQLBINDPARAM($qrIns, $QuonolistArray);
        $result = $objSQL->InsertData2();
        if ($result == 'insert ok!') {
            $info = 'Insert Successful!';
        } else {
            $info = 'Insert Failed';
        }
        return $info;
    }

    function createQuonolistArray() {
        $QuonolistArray = array(
            'bid' => $this->get_bid(),
            'currency' => $this->get_currency(),
            'quono' => $this->get_quono(),
            'quotab' => $this->get_quotab(),
            'period' => $this->get_period(),
            'Shape_Code' => $this->get_Shape_Code(),
            'category' => $this->get_category(),
            'specialShapeOrder' => $this->get_specialShapeOrder(),
            'tabletype' => $this->get_tabletype(),
            'company' => $this->get_company(),
            'cusstatus' => $this->get_cusstatus(),
            'custype' => $this->get_custype(),
            'pagetype' => $this->get_pagetype(),
            'cid' => $this->get_cid(),
            'accno' => $this->get_accno(),
            'date' => $this->get_date(),
            'terms' => $this->get_terms(),
            'item' => $this->get_item(),
            'quantity' => $this->get_quantity(),
            'grade' => $this->get_grade(),
            'mdt' => $this->get_mdt(),
            'mdw' => $this->get_mdw(),
            'mdl' => $this->get_mdl(),
            'dim_desp' => $this->get_dim_desp(),
            'fdt' => $this->get_fdt(),
            'fdw' => $this->get_fdw(),
            'fdl' => $this->get_fdl(),
            'finishing_dim_desp' => $this->get_finishing_dim_desp(),
            'process' => $this->get_process(),
            'mat' => $this->get_mat(),
            'pmach' => $this->get_pmach(),
            'cncmach' => $this->get_cncmach(),
            'other' => $this->get_other(),
            'ftz' => $this->get_ftz(),
            'amountmat' => $this->get_amountmat(),
            'discountmat' => $this->get_discountmat(),
            'gstmat' => $this->get_gstmat(),
            'totalamountmat' => $this->get_totalamountmat(),
            'amountpmach' => $this->get_amountpmach(),
            'discountpmach' => $this->get_discountpmach(),
            'gstpmach' => $this->get_gstpmach(),
            'totalamountpmach' => $this->get_totalamountpmach(),
            'amountcncmach' => $this->get_amountcncmach(),
            'discountcncmach' => $this->get_discountcncmach(),
            'gstcncmach' => $this->get_gstcncmach(),
            'totalamountcncmach' => $this->get_totalamountcncmach(),
            'amountother' => $this->get_amountother(),
            'discountother' => $this->get_discountother(),
            'gstother' => $this->get_gstother(),
            'totalamountother' => $this->get_totalamountother(),
            'totalamount' => $this->get_totalamount(),
            'aid_quo' => $this->get_aid_quo(),
            'aid_cus' => $this->get_aid_cus(),
            'datetimeissue' => $this->get_datetimeissue(),
            'volumeperunit' => $this->get_volumeperunit(),
            'weightperunit' => $this->get_weightperunit(),
            'totalweight' => $this->get_totalweight(),
            'density' => $this->get_density(),
            'priceperKG' => $this->get_priceperKG(),
        );
        return $QuonolistArray;
    }

    function extractarray() {
        $postdata = $this->postdata;
        $customerdata = $this->customerdata;

        $this->set_bid($postdata['bid']); //v
        $this->set_currency($customerdata['currency']);
        $this->set_quono($postdata['quono']); //v
        $this->set_quotab($postdata['quotab']); //v
        $this->set_period($postdata['post_period']); //v
        $this->set_Shape_Code($postdata['Shape_Code']); //v
        $this->set_category($postdata['category']); //v
        $this->set_specialShapeOrder($postdata['specialShapeOrder']);
        $this->set_tabletype($postdata['tabletype']); //v
        $this->set_company(strtolower($postdata['com'])); //v
        $this->set_cusstatus($customerdata['status']);
        $this->set_custype($postdata['custype']); //v
        $this->set_pagetype('normal');                      //NOT YET IMPLEMENTED, CHANGE THIS LATER
        $this->set_cid($postdata['post_cid']);  //v
        $this->set_accno($customerdata['accno']);
        $this->set_date(date('Y-m-d'));
        $this->set_terms($customerdata['terms']);
        $this->set_item($postdata['itemno']);    //v
        $this->set_quantity($postdata['quantity']); //v
        $this->set_grade($postdata['mat']); //v
        $this->set_mdt($postdata['mdt']); //v
        $this->set_mdw($postdata['mdw']); //v
        $this->set_mdl($postdata['mdl']); //v
        $this->set_dim_desp($postdata['dim_desc']); //v
        $this->set_fdt($postdata['fdt']); //v
        $this->set_fdw($postdata['fdw']); //v
        $this->set_fdl($postdata['fdl']); //v
        $this->set_finishing_dim_desp($postdata['finishing_dim_desc']);   //v
        $this->set_process($postdata['pmid']); //v
        $this->set_mat($postdata['pricePerPCS']); //v       //Price per Unit
        $this->set_pmach($postdata['pmachprice']); //v      //Pmach per Unit
        $this->set_cncmach($postdata['cncprice']); //v      //CNC per unit
        $this->set_other($postdata['otherprice']); //v      //Other per Unit
        $this->set_ftz('SR');
        $this->set_amountmat($postdata['totalprice']); //v  //price * quantity
        $this->set_discountmat($postdata['totaldiscountmat']); //v   //discount * quantity
        $this->set_gstmat($postdata['totalgstmat']);                  //gst * quantity
        $this->set_totalamountmat($postdata['subtotalmat']);
        $this->set_amountpmach($postdata['totalpmachprice']);
        $this->set_discountpmach($postdata['totaldiscountpmach']);
        $this->set_gstpmach($postdata['totalgstpmach']);
        $this->set_totalamountpmach($postdata['subtotalpmachprice']);
        $this->set_amountcncmach($postdata['totalcncprice']);
        $this->set_discountcncmach($postdata['totaldiscountcnc']);
        $this->set_gstcncmach($postdata['totalgstcnc']);
        $this->set_totalamountcncmach($postdata['subtotalcncprice']);
        $this->set_amountother($postdata['otherprice']);
        $this->set_discountother($postdata['totaldiscountother']);
        $this->set_gstother($postdata['totalgstother']);
        $this->set_totalamountother($postdata['subtotalotherprice']);
        $this->set_totalamount($postdata['totalamount']);
        $this->set_aid_quo($postdata['aid']);
        $this->set_aid_cus($customerdata['aid_cus']);
        $this->set_datetimeissue(date('Y-m-d G:i:s'));
        $this->set_volumeperunit($postdata['volume']);
        $this->set_weightperunit($postdata['weight']);
        $this->set_totalweight($postdata['totalweight']);
        $this->set_density((float) $postdata['density']);
        $this->set_priceperKG($postdata['pricePerKG']);
    }

    function set_QuonolistArray($input) {
        $this->QuonolistArray = $input;
    }

    function get_QuonolistArray() {
        return $this->QuonolistArray;
    }

    function set_quonolist_table($input) {
        $this->quonolist_tbl = $input;
    }

    function get_quonolist_table() {
        return $this->quonolist_tbl;
    }

    function set_id($input) {
        $this->id = $input;
    }

    function get_id() {
        return $this->id;
    }

    function set_bid($input) {
        $this->bid = $input;
    }

    function get_bid() {
        return $this->bid;
    }

    function set_currency($input) {
        $this->currency = $input;
    }

    function get_currency() {
        return $this->currency;
    }

    function set_quono($input) {
        $this->quono = $input;
    }

    function get_quono() {
        return $this->quono;
    }

    function set_quotab($input) {
        $this->quotab = $input;
    }

    function get_quotab() {
        return $this->quotab;
    }

    function set_period($input) {
        $this->period = $input;
    }

    function get_period() {
        return $this->period;
    }

    function set_Shape_Code($input) {
        $this->Shape_Code = $input;
    }

    function get_Shape_Code() {
        return $this->Shape_Code;
    }

    function set_category($input) {
        $this->category = $input;
    }

    function get_category() {
        return $this->category;
    }

    function set_specialShapeOrder($input) {
        $this->specialShapeOrder = $input;
    }

    function get_specialShapeOrder() {
        return $this->specialShapeOrder;
    }

    function set_tabletype($input) {
        $this->tabletype = $input;
    }

    function get_tabletype() {
        return $this->tabletype;
    }

    function set_company($input) {
        $this->company = $input;
    }

    function get_company() {
        return $this->company;
    }

    function set_cusstatus($input) {
        $this->cusstatus = $input;
    }

    function get_cusstatus() {
        return $this->cusstatus;
    }

    function set_custype($input) {
        $this->custype = $input;
    }

    function get_custype() {
        return $this->custype;
    }

    function set_pagetype($input) {
        $this->pagetype = $input;
    }

    function get_pagetype() {
        return $this->pagetype;
    }

    function set_cid($input) {
        $this->cid = $input;
    }

    function get_cid() {
        return $this->cid;
    }

    function set_accno($input) {
        $this->accno = $input;
    }

    function get_accno() {
        return $this->accno;
    }

    function set_date($input) {
        $this->date = $input;
    }

    function get_date() {
        return $this->date;
    }

    function set_terms($input) {
        $this->terms = $input;
    }

    function get_terms() {
        return $this->terms;
    }

    function set_item($input) {
        $this->item = $input;
    }

    function get_item() {
        return $this->item;
    }

    function set_quantity($input) {
        $this->quantity = $input;
    }

    function get_quantity() {
        return $this->quantity;
    }

    function set_grade($input) {
        $this->grade = $input;
    }

    function get_grade() {
        return $this->grade;
    }

    function set_mdt($input) {
        $this->mdt = $input;
    }

    function get_mdt() {
        return $this->mdt;
    }

    function set_mdw($input) {
        $this->mdw = $input;
    }

    function get_mdw() {
        return $this->mdw;
    }

    function set_mdl($input) {
        $this->mdl = $input;
    }

    function get_mdl() {
        return $this->mdl;
    }

    function set_dim_desp($input) {
        $this->dim_desp = $input;
    }

    function get_dim_desp() {
        return $this->dim_desp;
    }

    function set_fdt($input) {
        $this->fdt = $input;
    }

    function get_fdt() {
        return $this->fdt;
    }

    function set_fdw($input) {
        $this->fdw = $input;
    }

    function get_fdw() {
        return $this->fdw;
    }

    function set_fdl($input) {
        $this->fdl = $input;
    }

    function get_fdl() {
        return $this->fdl;
    }

    function set_finishing_dim_desp($input) {
        $this->finishing_dim_desp = $input;
    }

    function get_finishing_dim_desp() {
        return $this->finishing_dim_desp;
    }

    function set_process($input) {
        $this->process = $input;
    }

    function get_process() {
        return $this->process;
    }

    function set_mat($input) {
        $this->mat = $input;
    }

    function get_mat() {
        return $this->mat;
    }

    function set_pmach($input) {
        $this->pmach = $input;
    }

    function get_pmach() {
        return $this->pmach;
    }

    function set_cncmach($input) {
        $this->cncmach = $input;
    }

    function get_cncmach() {
        return $this->cncmach;
    }

    function set_other($input) {
        $this->other = $input;
    }

    function get_other() {
        return $this->other;
    }

    function set_ftz($input) {
        $this->ftz = $input;
    }

    function get_ftz() {
        return $this->ftz;
    }

    function set_amountmat($input) {
        $this->amountmat = $input;
    }

    function get_amountmat() {
        return $this->amountmat;
    }

    function set_discountmat($input) {
        $this->discountmat = $input;
    }

    function get_discountmat() {
        return $this->discountmat;
    }

    function set_gstmat($input) {
        $this->gstmat = $input;
    }

    function get_gstmat() {
        return $this->gstmat;
    }

    function set_totalamountmat($input) {
        $this->totalamountmat = $input;
    }

    function get_totalamountmat() {
        return $this->totalamountmat;
    }

    function set_amountpmach($input) {
        $this->amountpmach = $input;
    }

    function get_amountpmach() {
        return $this->amountpmach;
    }

    function set_discountpmach($input) {
        $this->discountpmach = $input;
    }

    function get_discountpmach() {
        return $this->discountpmach;
    }

    function set_gstpmach($input) {
        $this->gstpmach = $input;
    }

    function get_gstpmach() {
        return $this->gstpmach;
    }

    function set_totalamountpmach($input) {
        $this->totalamountpmach = $input;
    }

    function get_totalamountpmach() {
        return $this->totalamountpmach;
    }

    function set_amountcncmach($input) {
        $this->amountcncmach = $input;
    }

    function get_amountcncmach() {
        return $this->amountcncmach;
    }

    function set_discountcncmach($input) {
        $this->discountcncmach = $input;
    }

    function get_discountcncmach() {
        return $this->discountcncmach;
    }

    function set_gstcncmach($input) {
        $this->gstcncmach = $input;
    }

    function get_gstcncmach() {
        return $this->gstcncmach;
    }

    function set_totalamountcncmach($input) {
        $this->totalamountcncmach = $input;
    }

    function get_totalamountcncmach() {
        return $this->totalamountcncmach;
    }

    function set_amountother($input) {
        $this->amountother = $input;
    }

    function get_amountother() {
        return $this->amountother;
    }

    function set_discountother($input) {
        $this->discountother = $input;
    }

    function get_discountother() {
        return $this->discountother;
    }

    function set_gstother($input) {
        $this->gstother = $input;
    }

    function get_gstother() {
        return $this->gstother;
    }

    function set_totalamountother($input) {
        $this->totalamountother = $input;
    }

    function get_totalamountother() {
        return $this->totalamountother;
    }

    function set_totalamount($input) {
        $this->totalamount = $input;
    }

    function get_totalamount() {
        return $this->totalamount;
    }

    function set_aid_quo($input) {
        $this->aid_quo = $input;
    }

    function get_aid_quo() {
        return $this->aid_quo;
    }

    function set_aid_cus($input) {
        $this->aid_cus = $input;
    }

    function get_aid_cus() {
        return $this->aid_cus;
    }

    function set_datetimeissue($input) {
        $this->datetimeissue = $input;
    }

    function get_datetimeissue() {
        return $this->datetimeissue;
    }

    function set_volumeperunit($input) {
        $this->volumeperunit = $input;
    }

    function get_volumeperunit() {
        return $this->volumeperunit;
    }

    function set_weightperunit($input) {
        $this->weightperunit = $input;
    }

    function get_weightperunit() {
        return $this->weightperunit;
    }

    function set_totalweight($input) {
        $this->totalweight = $input;
    }

    function get_totalweight() {
        return $this->totalweight;
    }

    function set_density($input) {
        $this->density = $input;
    }

    function get_density() {
        return $this->density;
    }

    function set_priceperKG($input) {
        $this->priceperKG = $input;
    }

    function get_priceperKG() {
        return $this->priceperKG;
    }

}

class REV_QUONO extends QUONO_LIST {

    private $quonolist_tbl;
    private $rev_parent;

    function __construct($postdata) {
        $this->postdata = $postdata;
        $this->extractarray();
        $QuonolistArray = $this->createQuonolistArray();
        $this->set_QuonolistArray($QuonolistArray);
        $this->set_quonolist_table('quono_list');
#echo "<pre style='color:black;'>";
#echo "Lists Quono List Array : <br>";
#print_r($this->get_QuonolistArray());
#echo "</pre>";
    }

    function createQuonolistArray() {
        $QuonolistArray = array(
            'bid' => $this->get_bid(),
            'currency' => $this->get_currency(),
            'quono' => $this->get_quono(),
            'quotab' => $this->get_quotab(),
            'period' => $this->get_period(),
            'Shape_Code' => $this->get_Shape_Code(),
            'category' => $this->get_category(),
            'specialShapeOrder' => $this->get_specialShapeOrder(),
            'tabletype' => $this->get_tabletype(),
            'company' => $this->get_company(),
            'cusstatus' => $this->get_cusstatus(),
            'custype' => $this->get_custype(),
            'pagetype' => $this->get_pagetype(),
            'cid' => $this->get_cid(),
            'accno' => $this->get_accno(),
            'date' => $this->get_date(),
            'terms' => $this->get_terms(),
            'item' => $this->get_item(),
            'quantity' => $this->get_quantity(),
            'grade' => $this->get_grade(),
            'mdt' => $this->get_mdt(),
            'mdw' => $this->get_mdw(),
            'mdl' => $this->get_mdl(),
            'dim_desp' => $this->get_dim_desp(),
            'fdt' => $this->get_fdt(),
            'fdw' => $this->get_fdw(),
            'fdl' => $this->get_fdl(),
            'finishing_dim_desp' => $this->get_finishing_dim_desp(),
            'process' => $this->get_process(),
            'mat' => $this->get_mat(),
            'pmach' => $this->get_pmach(),
            'cncmach' => $this->get_cncmach(),
            'other' => $this->get_other(),
            'ftz' => $this->get_ftz(),
            'amountmat' => $this->get_amountmat(),
            'discountmat' => $this->get_discountmat(),
            'gstmat' => $this->get_gstmat(),
            'totalamountmat' => $this->get_totalamountmat(),
            'amountpmach' => $this->get_amountpmach(),
            'discountpmach' => $this->get_discountpmach(),
            'gstpmach' => $this->get_gstpmach(),
            'totalamountpmach' => $this->get_totalamountpmach(),
            'amountcncmach' => $this->get_amountcncmach(),
            'discountcncmach' => $this->get_discountcncmach(),
            'gstcncmach' => $this->get_gstcncmach(),
            'totalamountcncmach' => $this->get_totalamountcncmach(),
            'amountother' => $this->get_amountother(),
            'discountother' => $this->get_discountother(),
            'gstother' => $this->get_gstother(),
            'totalamountother' => $this->get_totalamountother(),
            'totalamount' => $this->get_totalamount(),
            'aid_quo' => $this->get_aid_quo(),
            'aid_cus' => $this->get_aid_cus(),
            'datetimeissue' => $this->get_datetimeissue(),
            'volumeperunit' => $this->get_volumeperunit(),
            'weightperunit' => $this->get_weightperunit(),
            'totalweight' => $this->get_totalweight(),
            'density' => $this->get_density(),
            'priceperKG' => $this->get_priceperKG(),
            'rev_parent' => $this->get_rev_parent(),
        );
        return $QuonolistArray;
    }

    function extractarray() {
        $postdata = $this->postdata;

        $this->set_bid($postdata['bid']); //v
        $this->set_currency($postdata['currency']);
        $this->set_quono($postdata['quono']); //v
        $this->set_quotab($postdata['quotab']); //v
        $this->set_period($postdata['period']); //v
        $this->set_Shape_Code($postdata['Shape_Code']); //v
        $this->set_category($postdata['category']); //v
        $this->set_specialShapeOrder($postdata['specialShapeOrder']);
        $this->set_tabletype($postdata['tabletype']); //v
        $this->set_company(strtolower($postdata['company'])); //v
        $this->set_cusstatus($postdata['cusstatus']);
        $this->set_custype($postdata['custype']); //v
        $this->set_pagetype($postdata['pagetype']);                      //NOT YET IMPLEMENTED, CHANGE THIS LATER
        $this->set_cid($postdata['cid']);  //v
        $this->set_accno($postdata['accno']);
        $this->set_date(date('Y-m-d'));
        $this->set_terms($postdata['terms']);
        $this->set_item($postdata['item']);    //v
        $this->set_quantity($postdata['quantity']); //v
        $this->set_grade($postdata['grade']); //v
        $this->set_mdt($postdata['mdt']); //v
        $this->set_mdw($postdata['mdw']); //v
        $this->set_mdl($postdata['mdl']); //v
        $this->set_dim_desp($postdata['dim_desp']); //v
        $this->set_fdt($postdata['fdt']); //v
        $this->set_fdw($postdata['fdw']); //v
        $this->set_fdl($postdata['fdl']); //v
        $this->set_finishing_dim_desp($postdata['finishing_dim_desp']);   //v
        $this->set_process($postdata['process']); //v
        $this->set_mat($postdata['mat']); //v       //Price per Unit
        $this->set_pmach($postdata['pmach']); //v      //Pmach per Unit
        $this->set_cncmach($postdata['cncmach']); //v      //CNC per unit
        $this->set_other($postdata['other']); //v      //Other per Unit
        $this->set_ftz('ftz');
        $this->set_amountmat($postdata['amountmat']); //v  //price * quantity
        $this->set_discountmat($postdata['discountmat']); //v   //discount * quantity
        $this->set_gstmat($postdata['gstmat']);                  //gst * quantity
        $this->set_totalamountmat($postdata['totalamountmat']);
        $this->set_amountpmach($postdata['amountpmach']);
        $this->set_discountpmach($postdata['discountpmach']);
        $this->set_gstpmach($postdata['gstpmach']);
        $this->set_totalamountpmach($postdata['totalamountpmach']);
        $this->set_amountcncmach($postdata['amountcncmach']);
        $this->set_discountcncmach($postdata['discountcncmach']);
        $this->set_gstcncmach($postdata['gstcncmach']);
        $this->set_totalamountcncmach($postdata['totalamountcncmach']);
        $this->set_amountother($postdata['amountother']);
        $this->set_discountother($postdata['discountother']);
        $this->set_gstother($postdata['gstother']);
        $this->set_totalamountother($postdata['totalamountother']);
        $this->set_totalamount($postdata['totalamount']);
        $this->set_aid_quo($postdata['aid_quo']);
        $this->set_aid_cus($postdata['aid_cus']);
        $this->set_datetimeissue(date('Y-m-d G:i:s'));
        $this->set_volumeperunit($postdata['volumeperunit']);
        $this->set_weightperunit($postdata['weightperunit']);
        $this->set_totalweight($postdata['totalweight']);
        $this->set_density((float) $postdata['density']);
        $this->set_priceperKG($postdata['priceperKG']);
        $this->set_rev_parent($postdata['rev_parent']);
    }

    function set_rev_parent($input) {
        $this->rev_parent = $input;
    }

    function get_rev_parent() {
        return $this->rev_parent;
    }

}

class QUO_DEL {

//List of variables needed in Quotation_$com_$period;
    private $qid;
    private $bid;
    private $currency;
    private $quono;
    private $company;
    private $pagetype;
    private $custype;
    private $cusstatus;
    private $cid;
    private $accno;
    private $date;
    private $terms;
    private $item;
    private $quantity;
    private $grade;
    private $mdt;
    private $mdw;
    private $mdl;
    private $fdt;
    private $fdw;
    private $fdl;
    private $process;
    private $mat;
    private $pmach;
    private $cncmach;
    private $other;
    private $unitprice;
    private $amount;
    private $discount;
    private $vat;
    private $gst;
    private $ftz;
    private $amountmat;
    private $discountmat;
    private $gstmat;
    private $totalamountmat;
    private $amountpmach;
    private $discountpmach;
    private $gstpmach;
    private $totalamountpmach;
    private $amountcncmach;
    private $discountcncmach;
    private $gstcncmach;
    private $totalamountcncmach;
    private $amountother;
    private $discountother;
    private $gstother;
    private $totalamountother;
    private $totalamount;
    private $mat_disc;
    private $pmach_disc;
    private $aid_quo;
    private $aid_cus;
    private $datetimeissue;
    private $odissue;
    private $remarks1;
    private $remarks2;
    private $remarks3;
    private $remarks4;
    private $deleteby;
    private $datetimedelete_quo;
    private $quodeltab;
    private $quo_del_array;

    function __construct($quodata, $quoremarkdata, $quodeltab) {
        $this->extractarray($quodata, $quoremarkdata);
        $quoArray = $this->createQuoDelArray();
        $this->set_quo_del_array($quoArray);
        $this->quodeltab = $quodeltab;
        #echo "<pre style='color:black;'>";
        #echo "Lists Quotation Array : <br>";
        #print_r($this->get_quoArray());
        #echo "</pre>";
    }

    function insertQuotationDelete() {
        #$quotab = 'quotationnew_pst_2007'; //$this->quotab;
        $quotab = $this->quodeltab;
        $quoArray = $this->get_quo_del_array();
        #print_r($quoArray);
        #echo "Table select : $quotab<br>";
        $checkExists = $this->check_table_exists($quotab);
        if ($checkExists != 'exist') {
            $createResult = $this->create_quotationdel_table($quotab);
            #echo $createResult . "<br>";
        }
        $qrIns = "INSERT INTO $quotab SET ";
        $cnt = 0;
        $arrCount = count($quoArray);
        foreach ($quoArray as $quokey => $quodata) {
            $cnt++;
            $qrIns .= " $quokey=:$quokey ";
            if ($arrCount != $cnt) {
                $qrIns .= " , ";
            }
        }
        #echo "qrIns = $qrIns<br>";
        $objsql = new SQLBINDPARAM($qrIns, $quoArray);
        $result = $objsql->InsertData2();
        if ($result == 'insert ok!') {
            $info = 'Insert Successful!';
        } else {
            $info = 'Insert Failed';
        }
        return $info;
    }

    function check_table_exists($quonewtab) {
        $qr = "SHOW TABLES LIKE '$quonewtab'";
        $objSQL = new SQL($qr);
        $results = $objSQL->getResultOneRowArray();
        if ($results) {
            return 'exist';
        } else {
            return 'not exist';
        }
    }

    function create_quotationdel_table($quonewtab) {
        $qr = "CREATE TABLE IF NOT EXISTS `$quonewtab` (
                    `qdid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `qid` INT(10) UNSIGNED NOT NULL,
                    `bid` INT(10) UNSIGNED NOT NULL,
                    `currency` INT(10) NOT NULL,
                    `quono` VARCHAR(20) NOT NULL,
                    `Shape_Code` VARCHAR(20) NOT NULL,
                    `Category` VARCHAR(20) NOT NULL,
                    `tabletype` VARCHAR(5) NOT NULL,
                    `company` VARCHAR(10) NOT NULL,
                    `pagetype` VARCHAR(15) NOT NULL,
                    `custype` VARCHAR(15) NULL DEFAULT NULL,
                    `cusstatus` VARCHAR(10) NULL DEFAULT NULL,
                    `cid` INT(10) UNSIGNED NOT NULL,
                    `accno` VARCHAR(8) NULL DEFAULT NULL,
                    `date` DATE NOT NULL,
                    `terms` VARCHAR(30) NOT NULL,
                    `item` VARCHAR(5) NOT NULL,
                    `quantity` INT(10) NOT NULL,
                    `grade` VARCHAR(30) NOT NULL,
                    `mdt` VARCHAR(15) NULL DEFAULT NULL,
                    `mdw` VARCHAR(15) NULL DEFAULT NULL,
                    `mdl` VARCHAR(15) NULL DEFAULT NULL,
                    `dim_desp` VARCHAR(50) NULL DEFAULT NULL,
                    `fdt` VARCHAR(15) NULL DEFAULT NULL,
                    `fdw` VARCHAR(15) NULL DEFAULT NULL,
                    `fdl` VARCHAR(15) NULL DEFAULT NULL,
                    `finishing_dim_desp` VARCHAR(50) NULL DEFAULT NULL,
                    `process` VARCHAR(20) NULL DEFAULT NULL,
                    `mat` DECIMAL(20,2) NOT NULL,
                    `pmach` DECIMAL(20,2) NULL DEFAULT NULL,
                    `cncmach` DECIMAL(20,2) NULL DEFAULT NULL,
                    `other` DECIMAL(20,2) NULL DEFAULT NULL,
                    `unitprice` DECIMAL(20,2) NOT NULL,
                    `amount` DECIMAL(20,2) NOT NULL,
                    `discount` DECIMAL(20,2) NULL DEFAULT NULL,
                    `vat` DECIMAL(20,2) NULL DEFAULT NULL,
                    `gst` DECIMAL(20,2) NULL DEFAULT NULL,
                    `ftz` VARCHAR(5) NULL DEFAULT NULL,
                    `amountmat` DECIMAL(10,2) NULL DEFAULT NULL,
                    `discountmat` DECIMAL(10,2) NULL DEFAULT NULL,
                    `gstmat` DECIMAL(10,2) NULL DEFAULT NULL,
                    `totalamountmat` DECIMAL(10,2) NULL DEFAULT NULL,
                    `amountpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `discountpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `gstpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `totalamountpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `amountcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `discountcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `gstcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `totalamountcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                    `amountother` DECIMAL(10,2) NULL DEFAULT NULL,
                    `discountother` DECIMAL(10,2) NULL DEFAULT NULL,
                    `gstother` DECIMAL(10,2) NULL DEFAULT NULL,
                    `totalamountother` DECIMAL(10,2) NULL DEFAULT NULL,
                    `totalamount` DECIMAL(20,2) NOT NULL,
                    `mat_disc` DECIMAL(5,2) NULL DEFAULT NULL,
                    `pmach_disc` DECIMAL(5,2) NULL DEFAULT NULL,
                    `aid_quo` INT(10) UNSIGNED NOT NULL,
                    `aid_cus` INT(10) UNSIGNED NOT NULL,
                    `datetimeissue` DATETIME NOT NULL,
                    `odissue` VARCHAR(10) NOT NULL DEFAULT 'no',
                    `rev_parent` VARCHAR(20) NULL DEFAULT NULL,
                    `rev_child` VARCHAR(20) NULL DEFAULT NULL, 
                    `remarks1` VARCHAR(100) NULL DEFAULT NULL,
                    `remarks2` VARCHAR(100) NULL DEFAULT NULL,
                    `remarks3` VARCHAR(100) NULL DEFAULT NULL,
                    `remarks4` VARCHAR(100) NULL DEFAULT NULL,
                    `deleteby` INT(10) UNSIGNED NOT NULL,
                    `datetimedelete_quo` DATE NOT NULL,
                    PRIMARY KEY (`qdid`)
                    )
                    COLLATE='utf8_general_ci'
                    ENGINE=MyISAM
                    AUTO_INCREMENT=1
                    ;
                ";
        $objSQL = new SQL($qr);
        $result = $objSQL->ExecuteQuery();
        if ($result == 'execute ok!') {
            return 'Table Created';
        } else {
            return 'Fail to create table';
        }
    }

    function createQuoDelArray() {
        $quoDelArray = array(
            'qid' => $this->get_qid(),
            'bid' => $this->get_bid(),
            'currency' => $this->get_currency(),
            'quono' => $this->get_quono(),
            'Shape_Code' => $this->get_Shape_Code(),
            'Category' => $this->get_Category(),
            'tabletype' => $this->get_tabletype(),
            'company' => $this->get_company(),
            'pagetype' => $this->get_pagetype(),
            'custype' => $this->get_custype(),
            'cusstatus' => $this->get_cusstatus(),
            'cid' => $this->get_cid(),
            'accno' => $this->get_accno(),
            'date' => $this->get_date(),
            'terms' => $this->get_terms(),
            'item' => $this->get_item(),
            'quantity' => $this->get_quantity(),
            'grade' => $this->get_grade(),
            'mdt' => $this->get_mdt(),
            'mdw' => $this->get_mdw(),
            'mdl' => $this->get_mdl(),
            'dim_desp' => $this->get_dim_desp(),
            'fdt' => $this->get_fdt(),
            'fdw' => $this->get_fdw(),
            'fdl' => $this->get_fdl(),
            'finishing_dim_desp' => $this->get_finishing_dim_desp(),
            'process' => $this->get_process(),
            'mat' => $this->get_mat(),
            'pmach' => $this->get_pmach(),
            'cncmach' => $this->get_cncmach(),
            'other' => $this->get_other(),
            'unitprice' => $this->get_unitprice(),
            'amount' => $this->get_amount(),
            'discount' => $this->get_discount(),
            'vat' => $this->get_vat(),
            'gst' => $this->get_gst(),
            'ftz' => $this->get_ftz(),
            'amountmat' => $this->get_amountmat(),
            'discountmat' => $this->get_discountmat(),
            'gstmat' => $this->get_gstmat(),
            'totalamountmat' => $this->get_totalamountmat(),
            'amountpmach' => $this->get_amountpmach(),
            'discountpmach' => $this->get_discountpmach(),
            'gstpmach' => $this->get_gstpmach(),
            'totalamountpmach' => $this->get_totalamountpmach(),
            'amountcncmach' => $this->get_amountcncmach(),
            'discountcncmach' => $this->get_discountcncmach(),
            'gstcncmach' => $this->get_gstcncmach(),
            'totalamountcncmach' => $this->get_totalamountcncmach(),
            'amountother' => $this->get_amountother(),
            'discountother' => $this->get_discountother(),
            'gstother' => $this->get_gstother(),
            'totalamountother' => $this->get_totalamountother(),
            'totalamount' => $this->get_totalamount(),
            'mat_disc' => $this->get_mat_disc(),
            'pmach_disc' => $this->get_pmach_disc(),
            'aid_quo' => $this->get_aid_quo(),
            'aid_cus' => $this->get_aid_cus(),
            'datetimeissue' => $this->get_datetimeissue(),
            'odissue' => $this->get_odissue(),
            'rev_parent' => $this->get_rev_parent(),
            'rev_child' => $this->get_rev_child(),
            'deleteby' => $this->get_deleteby(),
            'remarks1' => $this->get_remarks1(),
            'remarks2' => $this->get_remarks2(),
            'remarks3' => $this->get_remarks3(),
            'remarks4' => $this->get_remarks4(),
            'datetimedelete_quo' => $this->get_datetimedelete_quo()
        );
        return $quoDelArray;
    }

    function extractarray($quodata, $quoremarkdata) {
//extract post data to scope variables.
        $this->set_qid($quodata['qid']);
        $this->set_bid($quodata['bid']);
        $this->set_currency($quodata['currency']);
        $this->set_quono($quodata['quono']);
        $this->set_Shape_Code($quodata['Shape_Code']);
        $this->set_Category($quodata['Category']);
        $this->set_tabletype($quodata['tabletype']);
        $this->set_company($quodata['company']);
        $this->set_pagetype($quodata['pagetype']);
        $this->set_custype($quodata['custype']);
        $this->set_cusstatus($quodata['cusstatus']);
        $this->set_cid($quodata['cid']);
        $this->set_accno($quodata['accno']);
        $this->set_date($quodata['date']);
        $this->set_terms($quodata['terms']);
        $this->set_item($quodata['item']);
        $this->set_quantity($quodata['quantity']);
        $this->set_grade($quodata['grade']);
        $this->set_mdt($quodata['mdt']);
        $this->set_mdw($quodata['mdw']);
        $this->set_mdl($quodata['mdl']);
        $this->set_dim_desp($quodata['dim_desp']);
        $this->set_fdt($quodata['fdt']);
        $this->set_fdw($quodata['fdw']);
        $this->set_fdl($quodata['fdl']);
        $this->set_finishing_dim_desp($quodata['finishing_dim_desp']);
        $this->set_process($quodata['process']);
        $this->set_mat($quodata['mat']);
        $this->set_pmach($quodata['pmach']);
        $this->set_cncmach($quodata['cncmach']);
        $this->set_other($quodata['other']);
        $this->set_unitprice($quodata['unitprice']);
        $this->set_amount($quodata['amount']);
        $this->set_discount($quodata['discount']);
        $this->set_vat($quodata['vat']);
        $this->set_gst($quodata['gst']);
        $this->set_ftz($quodata['ftz']);
        $this->set_amountmat($quodata['amountmat']);
        $this->set_discountmat($quodata['discountmat']);
        $this->set_gstmat($quodata['gstmat']);
        $this->set_totalamountmat($quodata['totalamountmat']);
        $this->set_amountpmach($quodata['amountpmach']);
        $this->set_discountpmach($quodata['discountpmach']);
        $this->set_gstpmach($quodata['gstpmach']);
        $this->set_totalamountpmach($quodata['totalamountpmach']);
        $this->set_amountcncmach($quodata['amountcncmach']);
        $this->set_discountcncmach($quodata['discountcncmach']);
        $this->set_gstcncmach($quodata['gstcncmach']);
        $this->set_totalamountcncmach($quodata['totalamountcncmach']);
        $this->set_amountother($quodata['amountother']);
        $this->set_discountother($quodata['discountother']);
        $this->set_gstother($quodata['gstother']);
        $this->set_totalamountother($quodata['totalamountother']);
        $this->set_totalamount($quodata['totalamount']);
        $this->set_mat_disc($quodata['mat_disc']);
        $this->set_pmach_disc($quodata['pmach_disc']);
        $this->set_aid_quo($quodata['aid_quo']);
        $this->set_aid_cus($quodata['aid_cus']);
        $this->set_datetimeissue($quodata['datetimeissue']);
        $this->set_odissue($quodata['odissue']);
        $this->set_rev_parent($quodata['rev_parent']);
        $this->set_rev_child($quodata['rev_child']);
        $this->set_deleteby($quodata['deleteby']);
        $this->set_remarks1($quoremarkdata['remarks1']);
        $this->set_remarks2($quoremarkdata['remarks2']);
        $this->set_remarks3($quoremarkdata['remarks3']);
        $this->set_remarks4($quoremarkdata['remarks4']);
        $this->set_datetimedelete_quo(date("Y-m-d"));
    }

    function set_quo_del_array($input){
        $this->quo_del_array = $input;
    }
    
    function get_quo_del_array(){
        return $this->quo_del_array;
    }
    
    function set_qid($input) {
        $this->qid = $input;
    }

    function set_bid($input) {
        $this->bid = $input;
    }

    function set_currency($input) {
        $this->currency = $input;
    }

    function set_quono($input) {
        $this->quono = $input;
    }

    function set_Shape_Code($input) {
        $this->Shape_Code = $input;
    }

    function set_Category($input) {
        $this->Category = $input;
    }

    function set_tabletype($input) {
        $this->tabletype = $input;
    }

    function set_company($input) {
        $this->company = $input;
    }

    function set_pagetype($input) {
        $this->pagetype = $input;
    }

    function set_custype($input) {
        $this->custype = $input;
    }

    function set_cusstatus($input) {
        $this->cusstatus = $input;
    }

    function set_cid($input) {
        $this->cid = $input;
    }

    function set_accno($input) {
        $this->accno = $input;
    }

    function set_date($input) {
        $this->date = $input;
    }

    function set_terms($input) {
        $this->terms = $input;
    }

    function set_item($input) {
        $this->item = $input;
    }

    function set_quantity($input) {
        $this->quantity = $input;
    }

    function set_grade($input) {
        $this->grade = $input;
    }

    function set_mdt($input) {
        $this->mdt = $input;
    }

    function set_mdw($input) {
        $this->mdw = $input;
    }

    function set_mdl($input) {
        $this->mdl = $input;
    }

    function set_dim_desp($input) {
        $this->dim_desp = $input;
    }

    function set_fdt($input) {
        $this->fdt = $input;
    }

    function set_fdw($input) {
        $this->fdw = $input;
    }

    function set_fdl($input) {
        $this->fdl = $input;
    }

    function set_finishing_dim_desp($input) {
        $this->finishing_dim_desp = $input;
    }

    function set_process($input) {
        $this->process = $input;
    }

    function set_mat($input) {
        $this->mat = $input;
    }

    function set_pmach($input) {
        $this->pmach = $input;
    }

    function set_cncmach($input) {
        $this->cncmach = $input;
    }

    function set_other($input) {
        $this->other = $input;
    }

    function set_unitprice($input) {
        $this->unitprice = $input;
    }

    function set_amount($input) {
        $this->amount = $input;
    }

    function set_discount($input) {
        $this->discount = $input;
    }

    function set_vat($input) {
        $this->vat = $input;
    }

    function set_gst($input) {
        $this->gst = $input;
    }

    function set_ftz($input) {
        $this->ftz = $input;
    }

    function set_amountmat($input) {
        $this->amountmat = $input;
    }

    function set_discountmat($input) {
        $this->discountmat = $input;
    }

    function set_gstmat($input) {
        $this->gstmat = $input;
    }

    function set_totalamountmat($input) {
        $this->totalamountmat = $input;
    }

    function set_amountpmach($input) {
        $this->amountpmach = $input;
    }

    function set_discountpmach($input) {
        $this->discountpmach = $input;
    }

    function set_gstpmach($input) {
        $this->gstpmach = $input;
    }

    function set_totalamountpmach($input) {
        $this->totalamountpmach = $input;
    }

    function set_amountcncmach($input) {
        $this->amountcncmach = $input;
    }

    function set_discountcncmach($input) {
        $this->discountcncmach = $input;
    }

    function set_gstcncmach($input) {
        $this->gstcncmach = $input;
    }

    function set_totalamountcncmach($input) {
        $this->totalamountcncmach = $input;
    }

    function set_amountother($input) {
        $this->amountother = $input;
    }

    function set_discountother($input) {
        $this->discountother = $input;
    }

    function set_gstother($input) {
        $this->gstother = $input;
    }

    function set_totalamountother($input) {
        $this->totalamountother = $input;
    }

    function set_totalamount($input) {
        $this->totalamount = $input;
    }

    function set_mat_disc($input) {
        $this->mat_disc = $input;
    }

    function set_pmach_disc($input) {
        $this->pmach_disc = $input;
    }

    function set_aid_quo($input) {
        $this->aid_quo = $input;
    }

    function set_aid_cus($input) {
        $this->aid_cus = $input;
    }

    function set_datetimeissue($input) {
        $this->datetimeissue = $input;
    }

    function set_odissue($input) {
        $this->odissue = $input;
    }

    function set_rev_parent($input) {
        $this->rev_parent = $input;
    }

    function set_rev_child($input) {
        $this->rev_child = $input;
    }

    function set_deleteby($input) {
        $this->deleteby = $input;
    }

    function set_remarks1($input) {
        $this->remarks1 = $input;
    }

    function set_remarks2($input) {
        $this->remarks2 = $input;
    }

    function set_remarks3($input) {
        $this->remarks3 = $input;
    }

    function set_remarks4($input) {
        $this->remarks4 = $input;
    }

    function set_datetimedelete_quo($input) {
        $this->datetimedelete_quo = $input;
    }

    function get_qid() {
        return $this->qid;
    }

    function get_bid() {
        return $this->bid;
    }

    function get_currency() {
        return $this->currency;
    }

    function get_quono() {
        return $this->quono;
    }

    function get_Shape_Code() {
        return $this->Shape_Code;
    }

    function get_Category() {
        return $this->Category;
    }

    function get_tabletype() {
        return $this->tabletype;
    }

    function get_company() {
        return $this->company;
    }

    function get_pagetype() {
        return $this->pagetype;
    }

    function get_custype() {
        return $this->custype;
    }

    function get_cusstatus() {
        return $this->cusstatus;
    }

    function get_cid() {
        return $this->cid;
    }

    function get_accno() {
        return $this->accno;
    }

    function get_date() {
        return $this->date;
    }

    function get_terms() {
        return $this->terms;
    }

    function get_item() {
        return $this->item;
    }

    function get_quantity() {
        return $this->quantity;
    }

    function get_grade() {
        return $this->grade;
    }

    function get_mdt() {
        return $this->mdt;
    }

    function get_mdw() {
        return $this->mdw;
    }

    function get_mdl() {
        return $this->mdl;
    }

    function get_dim_desp() {
        return $this->dim_desp;
    }

    function get_fdt() {
        return $this->fdt;
    }

    function get_fdw() {
        return $this->fdw;
    }

    function get_fdl() {
        return $this->fdl;
    }

    function get_finishing_dim_desp() {
        return $this->finishing_dim_desp;
    }

    function get_process() {
        return $this->process;
    }

    function get_mat() {
        return $this->mat;
    }

    function get_pmach() {
        return $this->pmach;
    }

    function get_cncmach() {
        return $this->cncmach;
    }

    function get_other() {
        return $this->other;
    }

    function get_unitprice() {
        return $this->unitprice;
    }

    function get_amount() {
        return $this->amount;
    }

    function get_discount() {
        return $this->discount;
    }

    function get_vat() {
        return $this->vat;
    }

    function get_gst() {
        return $this->gst;
    }

    function get_ftz() {
        return $this->ftz;
    }

    function get_amountmat() {
        return $this->amountmat;
    }

    function get_discountmat() {
        return $this->discountmat;
    }

    function get_gstmat() {
        return $this->gstmat;
    }

    function get_totalamountmat() {
        return $this->totalamountmat;
    }

    function get_amountpmach() {
        return $this->amountpmach;
    }

    function get_discountpmach() {
        return $this->discountpmach;
    }

    function get_gstpmach() {
        return $this->gstpmach;
    }

    function get_totalamountpmach() {
        return $this->totalamountpmach;
    }

    function get_amountcncmach() {
        return $this->amountcncmach;
    }

    function get_discountcncmach() {
        return $this->discountcncmach;
    }

    function get_gstcncmach() {
        return $this->gstcncmach;
    }

    function get_totalamountcncmach() {
        return $this->totalamountcncmach;
    }

    function get_amountother() {
        return $this->amountother;
    }

    function get_discountother() {
        return $this->discountother;
    }

    function get_gstother() {
        return $this->gstother;
    }

    function get_totalamountother() {
        return $this->totalamountother;
    }

    function get_totalamount() {
        return $this->totalamount;
    }

    function get_mat_disc() {
        return $this->mat_disc;
    }

    function get_pmach_disc() {
        return $this->pmach_disc;
    }

    function get_aid_quo() {
        return $this->aid_quo;
    }

    function get_aid_cus() {
        return $this->aid_cus;
    }

    function get_datetimeissue() {
        return $this->datetimeissue;
    }

    function get_odissue() {
        return $this->odissue;
    }

    function get_rev_parent() {
        return $this->rev_parent;
    }

    function get_rev_child() {
        return $this->rev_child;
    }

    function get_deleteby() {
        return $this->deleteby;
    }

    function get_remarks1() {
        return $this->remarks1;
    }

    function get_remarks2() {
        return $this->remarks2;
    }

    function get_remarks3() {
        return $this->remarks3;
    }

    function get_remarks4() {
        return $this->remarks4;
    }

    function get_datetimedelete_quo() {
        return $this->datetimedelete_quo;
    }

}

class CreateQuotation2 {

//List of variables needed in Quotation_$com_$period;

    private $qid;           //auto increment, no need to use
    private $bid;           //$_POST['bid']
    private $currency;      //$customer_data['currency']
    private $quono;         //$_POST['quono']
    private $Shape_Code;    //$_POST['Shape_Code']
    private $category;      //$_POST['category']
    private $tabletype;     //$_POST['tabletype']
    private $company;       //$_POST['com']
    private $pagetype;      //??????
    private $custype;       //$_POST['custype']
    private $cusstatus;     //$customer_data['status']
    private $cid;           //$_POST['post_cid']
    private $accno;         //$customer_data['accno']
    private $date;          //date(YYYY-MM-DD)
    private $terms;         //$customer_data['terms']
    private $item;          //$_POST['itemno'];
    private $quantity;      //$_POST['quantity']
    private $grade;         //$_POST['mat']
    private $mdt;           //$_POST['mdt']
    private $mdw;           //$_POST['mdw']
    private $mdl;           //$_POST['mdl']
    private $dim_desp;      //$_POST['dim_desc]
    private $fdt;           //$_POST['fdt']
    private $fdw;           //$_POST['fdw']
    private $fdl;           //$_POST['fdl']
    private $finishing_dim_desp; //$_POST['finishing_dim_desc']
    private $process;       //$_POST['pmid']
    private $mat;           //$_POST['pricePerPCS']
    private $pmach;         //$_POST['pmachprice']
    private $cncmach;       //$_POST['cncprice']
    private $other;         //$_POST['otherprice']
    private $unitprice;     //Didn't find where this from
    private $amount;        //Didn't find where this from
    private $discount;      //Didn't find where this from
    private $vat;           //Didn't find where this from
    private $gst;           //Didn't find where this from
    private $ftz;           //Didn't find where this from; original val : SR
    private $amountmat;     //$_POST['totalprice'] -> pricePerPCS * quantity
    private $discountmat;   //$_POST['discountmat']
    private $gstmat;        //$_POST['gstmat']
    private $totalamountmat; //$_POST['subtotalmat']
    private $amountpmach;   //$_POST['totalpmachprice']
    private $discountpmach; //$_POST['discountpmach']
    private $gstpmach;      //$_POST['gstpmach']
    private $totalamountpmach;  //$_POST['subtotalpmach']
    private $amountcncmach;     //$_POST['totalcncprice']
    private $discountcncmach;   //$_POST['discountcnc']
    private $gstcncmach;        //$_POST['gstcnc']
    private $totalamountcncmach;    //$_POST['subtotalcnc']
    private $amountother;           //Check regarding this.
    private $discountother;         //$_POST['discountother']
    private $gstother;              //$_POST['gstother']
    private $totalamountother;      //$_POST['subtotalother']
    private $totalamount;           //$_POST['subtotalamount']
    private $mat_disc;          //Didn't find where this from
    private $pmach_disc;        //Didn't find where this from
    private $aid_quo;           //This is the User who issued the Quotation
    private $aid_cus;           //$customer_data['aid_cus']
    private $datetimeissue;     //date now
    private $odissue;           //orderlist issue, default : no
    private $rev_parent;
    private $rev_child;
    private $quoArray; //Quotation Array for Insertion;
    private $quotab;

    function __construct($postdata, $quotab) {
        $this->extractarray($postdata);
        $quoArray = $this->createQuoArray();
        $this->set_quoArray($quoArray);
        $this->quotab = $quotab;
#echo "<pre style='color:black;'>";
#echo "Lists Quotation Array : <br>";
#print_r($this->get_quoArray());
#echo "</pre>";
    }

    function insertQuotation() {
#$quotab = 'quotationnew_pst_2007'; //$this->quotab;
        $quotab = $this->quotab;
        $quoArray = $this->get_quoArray();
        #print_r($quoArray);
#echo "Table select : $quotab<br>";
        $checkExists = $this->check_table_exists($quotab);
        if ($checkExists != 'exist') {
            $createResult = $this->create_quotationnew_table($quotab);
#echo $createResult . "<br>";
        }
        $qrIns = "INSERT INTO $quotab SET ";
        $cnt = 0;
        $arrCount = count($quoArray);
        foreach ($quoArray as $quokey => $quodata) {
            $cnt++;
            $qrIns .= " $quokey=:$quokey ";
            if ($arrCount != $cnt) {
                $qrIns .= " , ";
            }
        }
#echo "qrIns = $qrIns<br>";
        $objsql = new SQLBINDPARAM($qrIns, $quoArray);
        $result = $objsql->InsertData2();
        if ($result == 'insert ok!') {
            $info = 'Insert Successful!';
        } else {
            $info = 'Insert Failed';
        }
        return $info;
    }

    function check_table_exists($quonewtab) {
        $qr = "SHOW TABLES LIKE '$quonewtab'";
        $objSQL = new SQL($qr);
        $results = $objSQL->getResultOneRowArray();
        if ($results) {
            return 'exist';
        } else {
            return 'not exist';
        }
    }

    function create_quotationnew_table($quonewtab) {
        $qr = "CREATE TABLE IF NOT EXISTS `$quonewtab` (
                        `qid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `bid` INT(10) UNSIGNED NOT NULL,
                        `currency` INT(10) NOT NULL,
                        `quono` VARCHAR(20) NOT NULL,
                        `Shape_Code` VARCHAR(20) NOT NULL,
                        `Category` VARCHAR(20) NOT NULL,
                        `tabletype` VARCHAR(5) NOT NULL,
                        `company` VARCHAR(10) NOT NULL,
                        `pagetype` VARCHAR(15) NOT NULL,
                        `custype` VARCHAR(15) NULL DEFAULT NULL,
                        `cusstatus` VARCHAR(10) NULL DEFAULT NULL,
                        `cid` INT(10) UNSIGNED NOT NULL,
                        `accno` VARCHAR(8) NULL DEFAULT NULL,
                        `date` DATE NOT NULL,
                        `terms` VARCHAR(30) NOT NULL,
                        `item` VARCHAR(5) NOT NULL,
                        `quantity` INT(10) NOT NULL,
                        `grade` VARCHAR(30) NOT NULL,
                        `mdt` VARCHAR(15) NULL DEFAULT NULL,
                        `mdw` VARCHAR(15) NULL DEFAULT NULL,
                        `mdl` VARCHAR(15) NULL DEFAULT NULL,
                        `dim_desp` VARCHAR(50) NULL DEFAULT NULL,
                        `fdt` VARCHAR(15) NULL DEFAULT NULL,
                        `fdw` VARCHAR(15) NULL DEFAULT NULL,
                        `fdl` VARCHAR(15) NULL DEFAULT NULL,
                        `finishing_dim_desp` VARCHAR(50) NULL DEFAULT NULL,
                        `process` VARCHAR(20) NULL DEFAULT NULL,
                        `mat` DECIMAL(20,2) NOT NULL,
                        `pmach` DECIMAL(20,2) NULL DEFAULT NULL,
                        `cncmach` DECIMAL(20,2) NULL DEFAULT NULL,
                        `other` DECIMAL(20,2) NULL DEFAULT NULL,
                        `unitprice` DECIMAL(20,2) NOT NULL,
                        `amount` DECIMAL(20,2) NOT NULL,
                        `discount` DECIMAL(20,2) NULL DEFAULT NULL,
                        `vat` DECIMAL(20,2) NULL DEFAULT NULL,
                        `gst` DECIMAL(20,2) NULL DEFAULT NULL,
                        `ftz` VARCHAR(5) NULL DEFAULT NULL,
                        `amountmat` DECIMAL(10,2) NULL DEFAULT NULL,
                        `discountmat` DECIMAL(10,2) NULL DEFAULT NULL,
                        `gstmat` DECIMAL(10,2) NULL DEFAULT NULL,
                        `totalamountmat` DECIMAL(10,2) NULL DEFAULT NULL,
                        `amountpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `discountpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `gstpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `totalamountpmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `amountcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `discountcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `gstcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `totalamountcncmach` DECIMAL(10,2) NULL DEFAULT NULL,
                        `amountother` DECIMAL(10,2) NULL DEFAULT NULL,
                        `discountother` DECIMAL(10,2) NULL DEFAULT NULL,
                        `gstother` DECIMAL(10,2) NULL DEFAULT NULL,
                        `totalamountother` DECIMAL(10,2) NULL DEFAULT NULL,
                        `totalamount` DECIMAL(20,2) NOT NULL,
                        `mat_disc` DECIMAL(5,2) NULL DEFAULT NULL,
                        `pmach_disc` DECIMAL(5,2) NULL DEFAULT NULL,
                        `aid_quo` INT(10) UNSIGNED NOT NULL,
                        `aid_cus` INT(10) UNSIGNED NOT NULL,
                        `datetimeissue` DATETIME NOT NULL,
                        `odissue` VARCHAR(10) NOT NULL DEFAULT 'no',
                        `rev_parent` VARCHAR(20) NULL DEFAULT NULL,
                        `rev_child` VARCHAR(20) NULL DEFAULT NULL,
                        PRIMARY KEY (`qid`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=MyISAM
                AUTO_INCREMENT=2016
                ;
                ";
        $objSQL = new SQL($qr);
        $result = $objSQL->ExecuteQuery();
        if ($result == 'execute ok!') {
            return 'Table Created';
        } else {
            return 'Fail to create table';
        }
    }

    function createQuoArray() {
        $quoArray = array(
            'bid' => $this->get_bid(),
            'currency' => $this->get_currency(),
            'quono' => $this->get_quono(),
            'Shape_Code' => $this->get_Shape_Code(),
            'Category' => $this->get_Category(),
            'tabletype' => $this->get_tabletype(),
            'company' => $this->get_company(),
            'pagetype' => $this->get_pagetype(),
            'custype' => $this->get_custype(),
            'cusstatus' => $this->get_cusstatus(),
            'cid' => $this->get_cid(),
            'accno' => $this->get_accno(),
            'date' => $this->get_date(),
            'terms' => $this->get_terms(),
            'item' => $this->get_item(),
            'quantity' => $this->get_quantity(),
            'grade' => $this->get_grade(),
            'mdt' => $this->get_mdt(),
            'mdw' => $this->get_mdw(),
            'mdl' => $this->get_mdl(),
            'dim_desp' => $this->get_dim_desp(),
            'fdt' => $this->get_fdt(),
            'fdw' => $this->get_fdw(),
            'fdl' => $this->get_fdl(),
            'finishing_dim_desp' => $this->get_finishing_dim_desp(),
            'process' => $this->get_process(),
            'mat' => $this->get_mat(),
            'pmach' => $this->get_pmach(),
            'cncmach' => $this->get_cncmach(),
            'other' => $this->get_other(),
            'unitprice' => $this->get_unitprice(),
            'amount' => $this->get_amount(),
            'discount' => $this->get_discount(),
            'vat' => $this->get_vat(),
            'gst' => $this->get_gst(),
            'ftz' => $this->get_ftz(),
            'amountmat' => $this->get_amountmat(),
            'discountmat' => $this->get_discountmat(),
            'gstmat' => $this->get_gstmat(),
            'totalamountmat' => $this->get_totalamountmat(),
            'amountpmach' => $this->get_amountpmach(),
            'discountpmach' => $this->get_discountpmach(),
            'gstpmach' => $this->get_gstpmach(),
            'totalamountpmach' => $this->get_totalamountpmach(),
            'amountcncmach' => $this->get_amountcncmach(),
            'discountcncmach' => $this->get_discountcncmach(),
            'gstcncmach' => $this->get_gstcncmach(),
            'totalamountcncmach' => $this->get_totalamountcncmach(),
            'amountother' => $this->get_amountother(),
            'discountother' => $this->get_discountother(),
            'gstother' => $this->get_gstother(),
            'totalamountother' => $this->get_totalamountother(),
            'totalamount' => $this->get_totalamount(),
            'mat_disc' => $this->get_mat_disc(),
            'pmach_disc' => $this->get_pmach_disc(),
            'aid_quo' => $this->get_aid_quo(),
            'aid_cus' => $this->get_aid_cus(),
            'datetimeissue' => $this->get_datetimeissue(),
            'odissue' => $this->get_odissue(),
            'rev_parent' => $this->get_rev_parent(),
            'rev_child' => $this->get_rev_child()
        );
        return $quoArray;
    }

    function extractarray($postdata) {
//extract post data to scope variables.
        $this->set_bid($postdata['bid']);
        $this->set_currency($postdata['currency']);
        $this->set_quono($postdata['quono']);
        $this->set_Shape_Code($postdata['Shape_Code']);
        $this->set_category($postdata['category']);
        $this->set_tabletype($postdata['tabletype']);
        $this->set_company($postdata['company']);
        $this->set_pagetype($postdata['pagetype']); //Change this later when pagetyp has been implemented
        $this->set_custype($postdata['custype']);
        $this->set_cusstatus($postdata['cusstatus']);
        $this->set_cid($postdata['cid']);
        $this->set_accno($postdata['accno']);
        $this->set_date($postdata['date']);
        $this->set_terms($postdata['terms']);
        $this->set_item($postdata['item']);
        $this->set_quantity($postdata['quantity']);
        $this->set_grade($postdata['grade']);
        $this->set_mdt($postdata['mdt']);
        $this->set_mdw($postdata['mdw']);
        $this->set_mdl($postdata['mdl']);
        $this->set_dim_desp($postdata['dim_desp']);
        $this->set_fdt($postdata['fdt']);
        $this->set_fdw($postdata['fdw']);
        $this->set_fdl($postdata['fdl']);
        $this->set_finishing_dim_desp($postdata['finishing_dim_desp']);
        $this->set_process($postdata['process']);
        $this->set_mat($postdata['mat']);
        $this->set_pmach($postdata['pmach']);
        $this->set_cncmach($postdata['cncmach']);
        $this->set_other($postdata['other']);
        $this->set_unitprice(floatval(0));  //check this, where this data from
        $this->set_amount(floatval(0));     //check this, where this data from
        $this->set_discount(floatval(0));   //check this, where this data from
        $this->set_vat(floatval(0));        //check this, where this data from
        $this->set_gst(floatval(0));        //check this, where this data from
        $this->set_ftz($postdata['ftz']);               //check this, where this data from
        $this->set_amountmat($postdata['amountmat']); //pricePerPCS * quantity            
        $this->set_discountmat($postdata['discountmat']);
        $this->set_gstmat($postdata['gstmat']); //changed to VAT from GST
        $this->set_totalamountmat($postdata['totalamountmat']);
        $this->set_amountpmach($postdata['amountpmach']);
        $this->set_discountpmach($postdata['discountpmach']);
        $this->set_gstpmach($postdata['gstpmach']);
        $this->set_totalamountpmach($postdata['totalamountpmach']);
        $this->set_amountcncmach($postdata['amountcncmach']);
        $this->set_discountcncmach($postdata['discountcncmach']);
        $this->set_gstcncmach($postdata['gstcncmach']);
        $this->set_totalamountcncmach($postdata['totalamountcncmach']);
        $this->set_amountother($postdata['amountother']);
        $this->set_discountother($postdata['discountother']);
        $this->set_gstother($postdata['gstother']);
        $this->set_totalamountother($postdata['totalamountother']);
        $this->set_totalamount($postdata['totalamount']);
        $this->set_mat_disc(floatval(0));       //check this, where this data from
        $this->set_pmach_disc(floatval(0));     //check this, where this data from
        $this->set_aid_quo($postdata['aid_quo']);
        $this->set_aid_cus($postdata['aid_cus']);
        $this->set_datetimeissue($postdata['datetimeissue']);
        $this->set_odissue('no');
        $this->set_rev_parent($postdata['rev_parent']);
        $this->set_rev_child($postdata['rev_child']);
        /*
          echo "Show datas from quotation.inc.php : <br>";
          echo 'qid =' . $this->get_qid() . '<br>';
          echo 'bid =' . $this->get_bid() . '<br>';
          echo 'currency =' . $this->get_currency() . '<br>';
          echo 'quono =' . $this->get_quono() . '<br>';
          echo 'Shape_Code =' . $this->get_Shape_Code() . '<br>';
          echo 'Category =' . $this->get_Category() . '<br>';
          echo 'tabletype =' . $this->get_tabletype() . '<br>';
          echo 'company =' . $this->get_company() . '<br>';
          echo 'pagetype =' . $this->get_pagetype() . '<br>';
          echo 'custype =' . $this->get_custype() . '<br>';
          echo 'cusstatus =' . $this->get_cusstatus() . '<br>';
          echo 'cid =' . $this->get_cid() . '<br>';
          echo 'accno =' . $this->get_accno() . '<br>';
          echo 'date =' . $this->get_date() . '<br>';
          echo 'terms =' . $this->get_terms() . '<br>';
          echo 'item =' . $this->get_item() . '<br>';
          echo 'quantity =' . $this->get_quantity() . '<br>';
          echo 'grade =' . $this->get_grade() . '<br>';
          echo 'mdt =' . $this->get_mdt() . '<br>';
          echo 'mdw =' . $this->get_mdw() . '<br>';
          echo 'mdl =' . $this->get_mdl() . '<br>';
          echo 'dim_desp =' . $this->get_dim_desp() . '<br>';
          echo 'fdt =' . $this->get_fdt() . '<br>';
          echo 'fdw =' . $this->get_fdw() . '<br>';
          echo 'fdl =' . $this->get_fdl() . '<br>';
          echo 'finishing_dim_desp =' . $this->get_finishing_dim_desp() . '<br>';
          echo 'process =' . $this->get_process() . '<br>';
          echo 'mat =' . $this->get_mat() . '<br>';
          echo 'pmach =' . $this->get_pmach() . '<br>';
          echo 'cncmach =' . $this->get_cncmach() . '<br>';
          echo 'other =' . $this->get_other() . '<br>';
          echo 'unitprice =' . $this->get_unitprice() . '<br>';
          echo 'amount =' . $this->get_amount() . '<br>';
          echo 'discount =' . $this->get_discount() . '<br>';
          echo 'vat =' . $this->get_vat() . '<br>';
          echo 'gst =' . $this->get_gst() . '<br>';
          echo 'ftz =' . $this->get_ftz() . '<br>';
          echo 'amountmat =' . $this->get_amountmat() . '<br>';
          echo 'discountmat =' . $this->get_discountmat() . '<br>';
          echo 'gstmat =' . $this->get_gstmat() . '<br>';
          echo 'totalamountmat =' . $this->get_totalamountmat() . '<br>';
          echo 'amountpmach =' . $this->get_amountpmach() . '<br>';
          echo 'discountpmach =' . $this->get_discountpmach() . '<br>';
          echo 'gstpmach =' . $this->get_gstpmach() . '<br>';
          echo 'totalamountpmach =' . $this->get_totalamountpmach() . '<br>';
          echo 'amountcncmach =' . $this->get_amountcncmach() . '<br>';
          echo 'discountcncmach =' . $this->get_discountcncmach() . '<br>';
          echo 'gstcncmach =' . $this->get_gstcncmach() . '<br>';
          echo 'totalamountcncmach =' . $this->get_totalamountcncmach() . '<br>';
          echo 'amountother =' . $this->get_amountother() . '<br>';
          echo 'discountother =' . $this->get_discountother() . '<br>';
          echo 'gstother =' . $this->get_gstother() . '<br>';
          echo 'totalamountother =' . $this->get_totalamountother() . '<br>';
          echo 'totalamount =' . $this->get_totalamount() . '<br>';
          echo 'mat_disc =' . $this->get_mat_disc() . '<br>';
          echo 'pmach_disc =' . $this->get_pmach_disc() . '<br>';
          echo 'aid_quo =' . $this->get_aid_quo() . '<br>';
          echo 'aid_cus =' . $this->get_aid_cus() . '<br>';
          echo 'datetimeissue =' . $this->get_datetimeissue() . '<br>';
          echo 'odissue =' . $this->get_odissue() . '<br>';
         * 
         */
    }

    function set_quoArray($input) {
        $this->quoArray = $input;
    }

    function get_quoArray() {
        return $this->quoArray;
    }

    function set_rev_parent($input) {
        $this->rev_parent = $input;
    }

    function get_rev_parent() {
        return $this->rev_parent;
    }

    function set_rev_child($input) {
        $this->rev_child = $input;
    }

    function get_rev_child() {
        return $this->rev_child;
    }

    function set_qid($input) {
        $this->qid = $input;
    }

    function get_qid() {
        return $this->qid;
    }

    function set_bid($input) {
        $this->bid = $input;
    }

    function get_bid() {
        return $this->bid;
    }

    function set_currency($input) {
        $this->currency = $input;
    }

    function get_currency() {
        return $this->currency;
    }

    function set_quono($input) {
        $this->quono = $input;
    }

    function get_quono() {
        return $this->quono;
    }

    function set_Shape_Code($input) {
        $this->Shape_Code = $input;
    }

    function get_Shape_Code() {
        return $this->Shape_Code;
    }

    function set_category($input) {
        $this->category = $input;
    }

    function get_category() {
        return $this->category;
    }

    function set_tabletype($input) {
        $this->tabletype = $input;
    }

    function get_tabletype() {
        return $this->tabletype;
    }

    function set_company($input) {
        $this->company = $input;
    }

    function get_company() {
        return $this->company;
    }

    function set_pagetype($input) {
        $this->pagetype = $input;
    }

    function get_pagetype() {
        return $this->pagetype;
    }

    function set_custype($input) {
        $this->custype = $input;
    }

    function get_custype() {
        return $this->custype;
    }

    function set_cusstatus($input) {
        $this->cusstatus = $input;
    }

    function get_cusstatus() {
        return $this->cusstatus;
    }

    function set_cid($input) {
        $this->cid = $input;
    }

    function get_cid() {
        return $this->cid;
    }

    function set_accno($input) {
        $this->accno = $input;
    }

    function get_accno() {
        return $this->accno;
    }

    function set_date($input) {
        $this->date = $input;
    }

    function get_date() {
        return $this->date;
    }

    function set_terms($input) {
        $this->terms = $input;
    }

    function get_terms() {
        return $this->terms;
    }

    function set_item($input) {
        $this->item = $input;
    }

    function get_item() {
        return $this->item;
    }

    function set_quantity($input) {
        $this->quantity = $input;
    }

    function get_quantity() {
        return $this->quantity;
    }

    function set_grade($input) {
        $this->grade = $input;
    }

    function get_grade() {
        return $this->grade;
    }

    function set_mdt($input) {
        $this->mdt = $input;
    }

    function get_mdt() {
        return $this->mdt;
    }

    function set_mdw($input) {
        $this->mdw = $input;
    }

    function get_mdw() {
        return $this->mdw;
    }

    function set_mdl($input) {
        $this->mdl = $input;
    }

    function get_mdl() {
        return $this->mdl;
    }

    function set_dim_desp($input) {
        $this->dim_desp = $input;
    }

    function get_dim_desp() {
        return $this->dim_desp;
    }

    function set_fdt($input) {
        $this->fdt = $input;
    }

    function get_fdt() {
        return $this->fdt;
    }

    function set_fdw($input) {
        $this->fdw = $input;
    }

    function get_fdw() {
        return $this->fdw;
    }

    function set_fdl($input) {
        $this->fdl = $input;
    }

    function get_fdl() {
        return $this->fdl;
    }

    function set_finishing_dim_desp($input) {
        $this->finishing_dim_desp = $input;
    }

    function get_finishing_dim_desp() {
        return $this->finishing_dim_desp;
    }

    function set_process($input) {
        $this->process = $input;
    }

    function get_process() {
        return $this->process;
    }

    function set_mat($input) {
        $this->mat = $input;
    }

    function get_mat() {
        return $this->mat;
    }

    function set_pmach($input) {
        $this->pmach = $input;
    }

    function get_pmach() {
        return $this->pmach;
    }

    function set_cncmach($input) {
        $this->cncmach = $input;
    }

    function get_cncmach() {
        return $this->cncmach;
    }

    function set_other($input) {
        $this->other = $input;
    }

    function get_other() {
        return $this->other;
    }

    function set_unitprice($input) {
        $this->unitprice = $input;
    }

    function get_unitprice() {
        return $this->unitprice;
    }

    function set_amount($input) {
        $this->amount = $input;
    }

    function get_amount() {
        return $this->amount;
    }

    function set_discount($input) {
        $this->discount = $input;
    }

    function get_discount() {
        return $this->discount;
    }

    function set_vat($input) {
        $this->vat = $input;
    }

    function get_vat() {
        return $this->vat;
    }

    function set_gst($input) {
        $this->gst = $input;
    }

    function get_gst() {
        return $this->gst;
    }

    function set_ftz($input) {
        $this->ftz = $input;
    }

    function get_ftz() {
        return $this->ftz;
    }

    function set_amountmat($input) {
        $this->amountmat = $input;
    }

    function get_amountmat() {
        return $this->amountmat;
    }

    function set_discountmat($input) {
        $this->discountmat = $input;
    }

    function get_discountmat() {
        return $this->discountmat;
    }

    function set_gstmat($input) {
        $this->gstmat = $input;
    }

    function get_gstmat() {
        return $this->gstmat;
    }

    function set_totalamountmat($input) {
        $this->totalamountmat = $input;
    }

    function get_totalamountmat() {
        return $this->totalamountmat;
    }

    function set_amountpmach($input) {
        $this->amountpmach = $input;
    }

    function get_amountpmach() {
        return $this->amountpmach;
    }

    function set_discountpmach($input) {
        $this->discountpmach = $input;
    }

    function get_discountpmach() {
        return $this->discountpmach;
    }

    function set_gstpmach($input) {
        $this->gstpmach = $input;
    }

    function get_gstpmach() {
        return $this->gstpmach;
    }

    function set_totalamountpmach($input) {
        $this->totalamountpmach = $input;
    }

    function get_totalamountpmach() {
        return $this->totalamountpmach;
    }

    function set_amountcncmach($input) {
        $this->amountcncmach = $input;
    }

    function get_amountcncmach() {
        return $this->amountcncmach;
    }

    function set_discountcncmach($input) {
        $this->discountcncmach = $input;
    }

    function get_discountcncmach() {
        return $this->discountcncmach;
    }

    function set_gstcncmach($input) {
        $this->gstcncmach = $input;
    }

    function get_gstcncmach() {
        return $this->gstcncmach;
    }

    function set_totalamountcncmach($input) {
        $this->totalamountcncmach = $input;
    }

    function get_totalamountcncmach() {
        return $this->totalamountcncmach;
    }

    function set_amountother($input) {
        $this->amountother = $input;
    }

    function get_amountother() {
        return $this->amountother;
    }

    function set_discountother($input) {
        $this->discountother = $input;
    }

    function get_discountother() {
        return $this->discountother;
    }

    function set_gstother($input) {
        $this->gstother = $input;
    }

    function get_gstother() {
        return $this->gstother;
    }

    function set_totalamountother($input) {
        $this->totalamountother = $input;
    }

    function get_totalamountother() {
        return $this->totalamountother;
    }

    function set_totalamount($input) {
        $this->totalamount = $input;
    }

    function get_totalamount() {
        return $this->totalamount;
    }

    function set_mat_disc($input) {
        $this->mat_disc = $input;
    }

    function get_mat_disc() {
        return $this->mat_disc;
    }

    function set_pmach_disc($input) {
        $this->pmach_disc = $input;
    }

    function get_pmach_disc() {
        return $this->pmach_disc;
    }

    function set_aid_quo($input) {
        $this->aid_quo = $input;
    }

    function get_aid_quo() {
        return $this->aid_quo;
    }

    function set_aid_cus($input) {
        $this->aid_cus = $input;
    }

    function get_aid_cus() {
        return $this->aid_cus;
    }

    function set_datetimeissue($input) {
        $this->datetimeissue = $input;
    }

    function get_datetimeissue() {
        return $this->datetimeissue;
    }

    function set_odissue($input) {
        $this->odissue = $input;
    }

    function get_odissue() {
        return $this->odissue;
    }

}

class Register extends Dbh {
    /* Private attribute, cannot be accessed directly */

    private $name;
    private $mat;
    private $matname;
    private $thick;
    private $width;
    private $length;
    private $volume;
    private $weight;
    private $density;
    private $quantity;
    private $cust_type;
    private $cid;
    private $period;
    private $quotab;
    private $priceperKG;
    private $unitprice;
    private $totalprice;
    private $totalweight;
    private $process;
    private $processcode;
    private $cncmach;
    private $others;
    private $calculate_pmach;
    private $fthick;
    private $fwidth;
    private $flength;
    private $processname;
    private $pmach;
    private $pmachsum;

    /* Constructor */

    public function __construct() {
#echo "Register Class instatianced.<br>";

        $this->name = '';
        $this->mat = '';
        $this->matname = '';
        $this->thick = 0;
        $this->width = 0;
        $this->length = 0;
        $this->volume = 0;
        $this->weight = 0;
        $this->density = 0;
        $this->quantity = 0;
        $this->cust_type = '';
        $this->cid = 0;
        $this->period = '';
        $this->quotab = '';
        $this->priceperKG = 0.00;
        $this->unitprice = 0.00;
        $this->totalprice = 0.00;
        $this->totalweight = 0.00;
        $this->process = 0;
        $this->processcode = 0;
        $this->others = 0.00;
        $this->calculate_pmach = '';
        $this->fthick = 0.00;
        $this->fwidth = 0.00;
        $this->flength = 0.00;
        $this->processname = 0.00;
        $this->pmach = 0.00;
        $this->pmachsum = 0.00;
    }

    public function set_pmachsum($new_name) {
        $this->pmachsum = $new_name;
    }

    public function get_pmachsum() {
        return $this->pmachsum;
    }

    public function set_pmach($new_name) {
        $this->pmach = $new_name;
    }

    public function get_pmach() {
        return $this->pmach;
    }

    public function set_processname($new_name) {
        $this->processname = $new_name;
    }

    public function get_processname() {
        return $this->processname;
    }

    public function set_calculate_pmach($new_name) {
        $this->calculate_pmach = $new_name;
    }

    public function get_calculate_pmach() {
        return $this->calculate_pmach;
    }

    public function set_others($new_name) {
        $this->others = $new_name;
    }

    public function get_others() {
        return $this->others;
    }

    public function set_processcode($new_name) {

        $this->processcode = $new_name;
    }

    public function get_processcode() {
        return $this->processcode;
    }

    public function set_process($new_name) {

        $this->process = $new_name;
    }

    public function get_process() {
        return $this->process;
    }

    public function set_quotab($new_name) {

        $this->quotab = $new_name;
    }

    public function get_quotab() {
        return $this->quotab;
    }

    public function set_period($new_name) {

        $this->period = $new_name;
    }

    public function get_period() {
        return $this->period;
    }

    public function set_cid($new_name) {

        $this->cid = $new_name;
    }

    public function get_cid() {
        return $this->cid;
    }

    public function set_quantity($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->quantity = $new_name;
//}
    }

    public function get_quantity() {
        return $this->quantity;
    }

    /* Getter function to read the attribute */

    public function get_name() {
        return $this->name;
    }

    /* Setter function to change the attribute */

    public function set_name($new_name) {
        if ($this->is_valid_name($new_name)) {
            $this->name = $new_name;
        }
    }

    public function set_mat($new_name) {// material code
//		if ($this->is_valid_name($new_name))
//		{
        $this->mat = $new_name;
//}
    }

    public function get_mat() {
        return $this->mat;
    }

    public function set_matname($new_name) {// material code
//		if ($this->is_valid_name($new_name))
//		{
        $this->matname = $new_name;
//}
    }

    public function get_matname() {
        return $this->matname;
    }

    /* Setter function to change the attribute */

    public function set_thick($new_name) {
        $this->thick = $new_name;
    }

    public function get_thick() {
        return $this->thick;
    }

    public function set_width($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->width = $new_name;
//}
    }

    public function get_width() {
        return $this->width;
    }

    public function set_length($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->length = $new_name;
//}
    }

    public function get_length() {
        return $this->length;
    }

    public function set_volume($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->volume = $new_name;
//}
    }

    public function get_volume() {
        return $this->volume;
    }

    public function set_weight($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->weight = $new_name;
//}
    }

    public function get_weight() {
        return $this->weight;
    }

    public function set_totalweight($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->totalweight = $new_name;
//}
    }

    public function get_totalweight() {
        return $this->totalweight;
    }

    public function set_cust_type($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->cust_type = $new_name;
//}
    }

    public function get_cust_type() {
        return $this->cust_type;
    }

    public function set_density($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->density = $new_name;
//}
    }

    public function get_density() {
        return $this->density;
    }

    public function set_priceperKG($new_name) {
//		if ($this->is_valid_name($new_name))
//		{
        $this->priceperKG = $new_name;
//}
    }

    public function get_priceperKG() {
        return $this->priceperKG;
    }

    public function set_unitprice($new_name) {

        $this->unitprice = $new_name;
    }

    public function get_unitprice() {
        return $this->unitprice;
    }

    public function set_totalprice($new_name) {

        $this->totalprice = $new_name;
    }

    public function get_totalprice() {
        return $this->totalprice;
    }

    /* Setter function to change the attribute */

    public function set_fthick($new_name) {

        $this->fthick = $new_name;
    }

    public function get_fthick() {
        return $this->fthick;
    }

    public function set_fwidth($new_name) {

        $this->fwidth = $new_name;
    }

    public function get_fwidth() {
        return $this->fwidth;
    }

    public function set_flength($new_name) {

        $this->flength = $new_name;
    }

    public function get_flength() {
        return $this->flength;
    }

    /* Checks if the name is valid */

    private function is_valid_name($name) {
        $valid = TRUE;

        /* Just checks if the string length is between 3 and 16 */
        if (mb_strlen($name) < 3) {
            $valid = FALSE;
//echo "\$name = $name is not valid, mb_strlen(\$name) = "
//   . "$mb_strlen($name) <br>";
        } else if (mb_strlen($name) > 16) {
            $valid = FALSE;
//echo "\$name = $name is not valid, mb_strlen(\$name) = "
//    . ".$mb_strlen($name) <br>";
        }
//echo "$name is ".$valid."<br>";

        return $valid;
    }

    public function list_all_parameter() {

        $cid = $this->cid;
        $period = $this->period;
        $pmach = $this->pmach;
        $pmachsum = $this->pmachsum;
        $process = $this->process;
        $mat = $this->mat;
        $matname = $this->matname;
        $thick = $this->thick;
        $width = $this->width;
        $length = $this->length;
        $volume = $this->volume;
        $weight = $this->weight;
        $density = $this->density;
        $quantity = $this->quantity;
        $cust_type = $this->cust_type;
        $priceperKG = $this->priceperKG;
        $unitprice = $this->unitprice;
        $totalprice = $this->totalprice;
        $totalweight = $this->totalweight;
        $process = $this->process;
        $processcode = $this->processcode;
        $cncmach = $this->cncmach;
        $others = $this->others;
        $calculate_pmach = $this->calculate_pmach;
        $fthick = $this->fthick;
        $fwidth = $this->fwidth;
        $flength = $this->flength;


        $result = "\$cid = $cid, \$period = $period, \$mat = $mat , \$matname = $matname, \$quantity = $quantity<br> "
                . "\$thick = $thick , \$width = $width, \$length = $length <br>"
                . "\$volume = $volume , \$weight = $weight, \$density = $density "
                . "\$priceperKG = $priceperKG,  \$unitprice = $unitprice"
                . "\$totalprice =$totalprice , \$totalweight = $totalweight"
                . "\$process = $process, $processcode = $processcode"
                . "\$cncmach = $cncmach, \$others = $others"
                . "\$calculate_pmach = $calculate_pmach, \$fthick = $fthick "
                . "\$fwidth = $fwidth, \$flength = $flength "
                . "\$pmach = $pmach, \$pmachsum = $pmachsum <br> ";
//            echo "\$thick = $thick , \$width = $width, \$length = $length <br> ";
//            echo "\$volume = $volume , \$weight = $weight, \$density = $density <br>";
//
//            echo $result ;
        $resultArray = array
            (
            "cid" => $cid,
            "period" => $period,
            "mat" => $mat,
            "matname" => $matname,
            "quantity" => $quantity,
            "thick" => $thick,
            "width" => $width,
            "length" => $length,
            "volume" => $volume,
            "weight" => $weight,
            "density" => $density,
            "cust_type" => $cust_type,
            "priceperKG" => $priceperKG,
            "priceperKG" => $priceperKG,
            "unitprice" => $unitprice,
            "totalprice" => $totalprice,
            "totalweight" => $totalweight,
            "process" => $process,
            "processcode" => $processcode,
            "cncmach" => $cncmach,
            "others" => $others,
            "calculate_pmach" => $calculate_pmach,
            "fthick" => $fthick,
            "fwidth" => $fwidth,
            "flength" => $flength,
            "pmach" => $pmach,
            "pmachsum" => $pmachsum
        );
        return $resultArray;
    }

}

class CreateQuotation extends Dbh {

    protected $cid;
    protected $period;
    protected $quotab;

    public function __construct($period, $cid, $quotab) {


        $this->period = $period;
        $this->cid = $cid;
        $this->quotab = $quotab;

// echo "object CreateQuotation have been initialed <br>";
// echo "\$period = $period, \$cid = $cid , \$quotab = $quotab <br>";
    }

//    $post_data=array()

    public function getNewQuotationBucket() {

        $sql = "SELECT * FROM quotation_pst_1911 WHERE cid = 21089 ";

        $objArray = new SQL($sql);

        $resultArray = $objArray->getResultOneRowArray();

        return $resultArray;

//        print_r($resultArray);
    }

    public function create_quotation_set() {

        $period = $this->period;
        $cid = $this->cid;
        $quotab = $this->quotab;

//echo "<br>####################################################################<br>";
        include 'arrays-in-array.php'; // generate an empty array $quotationArray
    }

}

class Quotation {
## properties

    protected $getPostData;
    protected $cid;
    protected $period;
    protected $quotab;
    public $qid;

    public function __construct($period, $cid) {

//$this->getPostData = [];
        $this->period = $period;
        $this->cid = $cid;
#$quotab = 'quotation_pst_' . $period;
//$quotab = 'quotationnew_pst_' . $period;
        $quotab = "quono_list";
        $this->setQuotab($quotab);
//$this->quotab = $quotab;
#echo "Quotation Class instantiated <br>";
#echo "\$period = $period, \$cid = $cid , \$quotab = $quotab <br>";


        $sqlQidCheck = "SELECT count(qid) FROM $quotab ORDER BY qid DESC";
        $objQidCheck = new SQL($sqlQidCheck);

        $recordCount = $objQidCheck->getRowCount();
        if ($recordCount == 0) {
            $qid = 1;
        } else if ($recordCount > 0) {

            $sqlQid = "SELECT qid FROM $quotab ORDER BY qid DESC";
            $objQid = new SQL($sqlQid);
            $qidresult = $objQid->getResultOneRowArray();
            $qid = $qidresult['qid'];
            $qid++;
        } else {

            $qid = 0;
        }
        $this->qid = $qid;
    }

    public function setQuotab($input) {

        $this->quotab = $input;
    }

    public function getQuotab() {


        return $this->quotab;
    }

    public function quotation_list() {

        $period = $this->period;
        $cid = $this->cid;
        $quotab = $this->quotab;
//        $sqldistinct =
        $sql = "SELECT DISTINCT quono, cid, date , name, rev_parent, issued_to_quotation, od_issue FROM $quotab "
                . " LEFT JOIN admin"
                . " ON admin.aid = $quotab.aid_quo"
                . "  WHERE (rev_child = '' OR rev_child IS NULL) AND cid = $cid AND quono LIKE '%$period%' ";
//$sql = "SELECT * FROM $quotab WHERE cid = $cid ORDER BY qid asc ";
        $objSQL = new SQL($sql);
        echo "\$sql = $sql <br>";
        $result = $objSQL->getResultRowArray();


        return $result;
    }

    public function quotation_list_numrows() {

        $period = $this->period;
        $cid = $this->cid;
        $quotab = $this->quotab;


        $sql = "SELECT count(*) FROM $quotab WHERE cid = $cid ";
        $objSQL = new SQL($sql);
//echo "\$sql = $sql <br>";
// $result=  $this->conn->query($sql);
        $result = $objSQL->getRowCount();
//echo "material_list_numrows have $result record(s).<br>";
        return $result;
    }

    public function __destruct() {
        
    }

}

Class QUONO extends Quotation {

    protected $quono;
    protected $co_code;
    protected $runingno;
    protected $period;
    protected $cid;
    protected $bid;
    protected $date;
    protected $quotab;
    protected $company;
    protected $quonotest;

    public function __construct($quotab, $cid, $bid, $period, $company, $quono) {

        parent::__construct($period, $cid);
#echo "QUONO Class instantiated<br> ";
//        $this->quotab = $quotab;
        $this->cid = $cid;
        $cid = (int) $cid;
        $this->bid = $bid;
        $bid = (int) $bid;
//        $this->period= $period;
        $this->company = $company;
        $this->quono = $quono;
#echo "in the constructor of QUONO class, \$quono = $quono <br>";
        $tempquono = "quono_list";
        $quonotest = '';

#get $co_code
        $custab = "customer_" . strtolower($company);
        $sql = "SELECT * from $custab WHERE cid = $cid";
        $objCus = new SQL($sql);
        $resultArray = $objCus->getResultOneRowArray();
        $co_code = $resultArray['co_code'];
        $co_code = preg_replace('/\s+/', '', $co_code);
        $this->co_code = $co_code;
#set $this->co_code
        $period = preg_replace('/\s+/', '', $period);

//        echo "\$co_code = $co_code , \$period =$period <br>";
# check is any  quotation records for this cid in $quotab or not
        debug_to_console("\$co_code = $co_code");
        debug_to_console("\$period =$period");
        $quonotest = (isset($quono)) ? $quono : "$co_code $period";
        $this->quonotest = $quonotest;
        debug_to_console("\$quonotest =$quonotest");

#echo "before echo \$sqlcheck ,the cid = $cid and bid = $bid <br>";
        $sqlcheck = "SELECT count(id) FROM $tempquono WHERE quono like '$quonotest%' "
                . "AND cid = '$cid' AND bid= '$bid'  order by id DESC   ";
#echo "\$sqlcheck = $sqlcheck <br>";
        debug_to_console("\$sqlcheck = $sqlcheck");
        $objCheck = new SQL($sqlcheck);

        $returnsets = $objCheck->getRowCount();
        debug_to_console("\$quono = $quono");

        if (isset($quono)) {
## check co_code
//get the correct quotation no, quono
            if ($returnsets == 0) {
#echo "\$returnsets = $returnsets <br>";
                debug_to_console("\$returnsets = $returnsets");
//echo "there are no record match this company in $quotab<br> ";
                debug_to_console("There are no record match this company in $quotab");
                $quono = $this->makeQuonoByCid();
//echo "\$quono = $quono <br>";
                debug_to_console("\$quono = $quono");
            } else {//!isset($quono)
#echo "There are some records match this company id in $quotab<br>";
                debug_to_console("There are some records match this company id in $quotab");
#check if the itemno have been reach no. 10 or not
                $sqlItemno = "SELECT item from $tempquono where cid = '$cid' "
                        . " AND quono like '$quonotest%' order by  id DESC";
#echo "\$sqlItemno =  $sqlItemno <br> ";
                $objSqlItemno = new SQL($sqlItemno);
                $resultItem = $objSqlItemno->getResultOneRowArray();
                $itemtest = $resultItem['item'];
#echo "\$itemtest = $itemtest <br>";
                $moduleResult = (int) $itemtest % 10;
                $remainderResult = $remainder = fmod($itemtest, 10);
#echo " \$moduleResult =   $moduleResult, \$remainderResult = $remainderResult <br>";
                if ($remainderResult == 0) {
                    $quono = $this->makeQuonoByCid();
//echo "\$quono = $quono <br>";
                    debug_to_console("\$quono = $quono");
                } else {
#echo "in else  loop $moduleResult != 0 <br>";
                    $sqlnow = "SELECT quono from $tempquono where cid = '$cid'"
                            . " AND quono like '$quonotest%' order by  item, quono desc";
#echo "\$sqlnow =  $sqlnow <br>";
                    $objNowquono = new SQL($sqlnow);
                    $resultquono = $objNowquono->getResultOneRowArray();
                    $quono = $resultquono['quono'];
                    $this->setQuono($quono);
                }
            }
        } else {
//doesn't detect any quono, create a new one based
//get the correct quotation no, quono
//echo "there are no record match this company in $quotab<br> ";
            debug_to_console("There are no record match this company in $quotab (3)");
            $quono = $this->makeQuonoByCid();
//echo "\$quono = $quono <br>";
            debug_to_console("\$quono = $quono");
        }
//        echo "\$qid = $qid <br>";

        $this->quono = $quono;
#echo "in constructor of QUONO, setup the quono value into scope variable $quono<br>";
        $this->setQuono($quono);
#set $this->quono
        $qid = $this->qid;
    }

    public function makeNewQuono() {

        $quotab = $this->quotab;
        $co_code = $this->co_code;

        $cid = $this->cid;
        $bid = $this->bid;
        $period = $this->period;
        $period = rtrim($period);
        $company = $this->company;


        $quonotest = "$co_code $period";
        $this->runningno = 1;
        $runningno = (string) $this->runningno;
        debug_to_console("\$runningno = $runningno");
        $runno = "00" . $runningno;
        $runno = ltrim($runno); // take out wll white spaces
        debug_to_console("\$runno = $runno");
        $co_code = $this->co_code;
//$quono = $co_code." ".$period." ".$runno;
//        $quono = "$co_code $period ".ltrim($runno);
        $quono = "$co_code $period ";
        debug_to_console("\$quono = $quono");
        $quono .= ltrim($runno);

        debug_to_console("\$quono = $quono");

        return $quono;
    }

    public function makeQuonoByCid() {

        $quotab = $this->quotab;
        $cid = $this->cid;
        $bid = $this->bid;
        $period = $this->period;
        $company = $this->company;
        $co_code = $this->co_code;

        $quonotest = "$co_code $period";
        $this->setQuonotest($quonotest);

        $sql = "SELECT quono FROM $quotab WHERE quono like '$quonotest%' "
                . "AND cid = $cid AND bid= $bid ORDER BY quono DESC  ";
#echo "sql on MakeQuonoByCid = $sql<br>";
        $objSql = new SQL($sql);

        $result = $objSql->getResultOneRowArray();
        if (!empty($result)) {
            $quono = $result['quono'];
        } else {
            $quono = $this->quonotest;
        }
#echo "quono at makeQuonoByCid() = $quono<br>";
        $testrunno = substr($quono, 9, 6);
#echo "\$testrunno = $testrunno <br>";
        $runno = intval($testrunno) + 1;

        if ($runno <= 9) {
            $this->runingno = "00" . strval($runno);
        } elseif ($runno > 9 or $runno <= 100) {
            $this->runingno = "0" . strval($runno);
        } elseif ($runno > 100) {

            $this->runingno = strval($runno);
        }
        $runingno = $this->runingno;
        $runingno = preg_replace('/\s+/', '', $runingno); // take out wll white spaces
        $period = preg_replace('/\s+/', '', $period);
        $co_code = preg_replace('/\s+/', '', $co_code);
#echo "The latest running no is $runingno <br>";

        $quono = $co_code . " " . $period . " " . $runingno;
//       $quono = "$co_code $period $runingno";
        return $quono;
    }

    public function getQuono() {

        return $this->quono;
    }

    public function setQuono($input) {

        $input = ltrim($input);
        $input = rtrim($input);
#echo "\$input = $input<br>";
        $this->quono = $input;
    }

    public function setQuonotest($input) {
        $input = ltrim($input);
        $input = rtrim($input);
        $this->quonotest = $input;
    }

}

Class QID extends Quotation {

    protected $period;
    protected $cid;

    public function __construct($period, $cid) {

        parent::__construct($period, $cid);
#echo "QID Class instantiated<br>";
    }

}

Class ITEMNO extends QUONO {

    protected $period;
    protected $cid;
    protected $itemno;
    protected $quono;
    protected $quotab;
    protected $currentitemno;

    public function __construct($quotab, $cid, $bid, $period, $company, $quono = null) {



        parent::__construct($quotab, $cid, $bid, $period, $company, $quono);


        $quono = $this->quono;
        $quono = ltrim($quono);
        $quono = rtrim($quono);
        $quotab = $this->quotab;
        $tempquono = 'quono_list';
        $tempquono = ltrim($tempquono);
        $tempquono = rtrim($tempquono);
        $cid = preg_replace('/\s+/', '', $cid);
        $quonotest = $this->quonotest;

#echo" \$quono =  $quono , in \$quotab = $quotab  <br>";
#echo "ITEMNO Class instantiated<br>";
        debug_to_console("ITEMNO Class instantiated");
        debug_to_console(" \$quono =  $quono , in \$quotab = $quotab  ");

        $sqlCheckcount = "SELECT COUNT(*) FROM $tempquono WHERE quono = '$quono'"
                . " AND cid = '$cid'";
#echo "\$sqlCheckcount = $sqlCheckcount <br>";
        debug_to_console("\$sqlCheckcount = $sqlCheckcount");
        $objRowcount = new SQL($sqlCheckcount);
        $numrows = $objRowcount->getRowCount();

#echo "Line 1104 , after \$sqlCheckcount , the numrows = $numrows <br>";
        debug_to_console("Line 1104 , after \$sqlCheckcount , the numrows = $numrows");

        if ($numrows == 0) {

            $itemno = 1;
        } else if ($numrows > 0) {

# check the count of itemno
            $sqlCheckItemno = "SELECT * FROM $tempquono WHERE quono like '$quonotest%' AND "
                    . " cid = $cid ORDER BY id DESC";
#echo "\$sqlCheckItemno = $sqlCheckItemno <br>";
            debug_to_console("\$sqlCheckItemno = $sqlCheckItemno");
            $objItemno = new SQL($sqlCheckItemno);
            $result = $objItemno->getResultOneRowArray();
            $latestItemno = $result['item'];
            $quono = $result['quono'];
            $this->setQuono($quono);
            $itemno = $latestItemno + 1;
#echo "\$latestItemno = $latestItemno <br>";
            debug_to_console("\$latestItemno = $latestItemno");
            $this->currentitemno = $itemno;
#echo "\$itemno = $itemno <br>";
            debug_to_console("\$itemno = $itemno");
        }

        $this->itemno = $itemno;

#echo "itemno = $itemno <br>";

        $this->setItemNo($itemno);
    }

    public function setItemNo($input) {

        $this->itemno = $input;
    }

    public function getItemno() {

        return $this->itemno;
    }

}
?>

