<?php
include_once "./class/dbh.inc.php";
include_once "./class/variables.inc.php";

function checkTableExist($tab) {
    $qr = "SHOW TABLES LIKE '$tab'";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return true;
    } else {
        return false;
    }
}

function get_QuonoListRecord($quono, $cid, $bid) {
    $tab = "quono_list";
    $qr = "SELECT * FROM $tab WHERE quono = '$quono' AND cid = '$cid' AND bid = '$bid'";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    return $result;
}

function get_QuotationRecord($quotab, $quono, $cid, $bid) {
    $tab = $quotab;
    $qr = "SELECT * FROM $tab WHERE quono = '$quono' AND cid = '$cid' AND bid = '$bid'";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultRowArray();
    return $result;
}

function compareOrderlistWithSchedulingData($period, $quono, $cid, $bid) {
    echo "<div class='border border-primary'>";
    echo "====Begin comparing data between Orderlist and Scheduling====<br>";
    $ordtab = "orderlistnew_pst_$period";
    $schtab = "production_scheduling_$period";
    $qrord = "SELECT "
            . "bid, qid, quono, company, cid, noposition, quantity, grade, mdt, mdw, mdl, fdt, fdw, fdl, process, cncmach, status, aid_cus, source, cuttingtype, runningno, jobno, operation  "
            . "FROM $ordtab WHERE quono = '$quono' AND cid = '$cid' AND bid = '$bid' ORDER BY noposition ASC";
    $objSQLord = new SQL($qrord);
    $qrsch = "SELECT "
            . "bid, qid, quono, company, cid, noposition, quantity, grade, mdt, mdw, mdl, fdt, fdw, fdl, process, cncmach, status, aid_cus, source, cuttingtype, runningno, jobno, operation  "
            . "FROM $schtab WHERE quono = '$quono' AND cid = '$cid' AND bid = '$bid' ORDER BY noposition ASC";
    $objSQLsch = new SQL($qrsch);
    echo "qrord =$qrord<br>";
    echo "qrsch =$qrsch<br>";
    echo "<br><br>";
    $orddataset = $objSQLord->getResultRowArray();
    $schdataset = $objSQLsch->getResultRowArray();
    if (empty($orddataset)) { //Orderlist not found
        throw new Exception("Cannot find data in $ordtab for quono = '$quono', bid = $bid, cid = $cid");
    }
    if (empty($schdataset)) {//Scheduling not found
        throw new Exception("Cannot find data in $schtab for quono = '$quono', bid = $bid, cid = $cid");
    }
    $ordnumrow = count($orddataset);
    $schnumrow = count($schdataset);
    if ($ordnumrow != $schnumrow) {//Check if the number of records the same or not
        throw new Exception("Number of Scheduling is not the same as Orderlist Records!");
    } else {
        $numrow = $ordnumrow;
        $topcount = $numrow - 1; //Maximum records, for loop requirement
        for ($i = 0; $i <= $topcount; $i++) {
            echo "Row No.$i<br>";
            $orddatarow = $orddataset[$i];
            $schdatarow = $schdataset[$i];
            $notmatch = 0;
            echo "<table class=' table-sm table-bordered'>";
            echo "<tr><th>Column Name</th><th>$ordtab</th><th>$schtab</th>";
            foreach ($orddatarow as $key => $val) {
                if ($orddatarow["$key"] != $schdatarow["$key"]) {
                    $bg = "class='bg-danger'";
                    $notmatch++;
                } else {
                    $bg = "";
                }
                echo "<tr $bg>";
                echo "<th>$key</th>";
                echo "<td>{$orddatarow["$key"]}</td>";
                echo "<td>{$orddatarow["$key"]}</td>";
                echo "</tr>";
            }
            echo "</table>";
            if ($notmatch == 0) {
                echo "All Record matches<br>";
            } else {
                throw new Exception("Record not matching in row no.$i");
            }
            echo "<br>";
        }
    }

    echo "====End comparing data between Orderlist and Scheduling====<br>";
    echo "</div>";
}

function compareOrderlistWithQuotation($period, $quoperiod, $quono, $cid, $bid, $docount) {
    echo "<div class='border border-warning'>";
    echo "====Begin comparing data between Orderlist and Quotation====<br>";
    $ordtab = "orderlistnew_pst_$period";
    $quotab = "quotationnew_pst_$quoperiod";
    //fetch orderlist record first
    $comparationcolumn = "qid,bid,quono,Shape_Code,Category,tabletype,specialShapeOrder,company,cusstatus,cid,item,quantity,grade,
                            mdt,mdw,mdl,dim_desp,fdt,fdw,fdl,finishing_dim_desp,process,mat,pmach,cncmach,other,ftz,amountmat,discountmat,
                            gstmat,totalamountmat,amountpmach,discountpmach,gstpmach,totalamountpmach,amountcncmach,discountcncmach,gstcncmach,
                            totalamountcncmach,amountother,discountother,gstother,totalamountother,totalamount ";
    try {
        $qrord = "SELECT $comparationcolumn
                     FROM $ordtab WHERE quono = '$quono' AND cid = $cid AND bid = $bid AND docount = $docount";
        $objSQLord = new SQL($qrord);
        $orddataset = $objSQLord->getResultRowArray();
        echo "\$qrord = $qrord<br>";
        if (empty($orddataset)) {
            throw new Exception("There's no record in $ordtab for $quono [cid = $cid;bid = $bid;docount = $docount]");
        }
        echo "==Begin loop on each records<br>";
        $errcnt = 0;
        foreach ($orddataset as $orddatarow) {
            $qid = $orddatarow['qid'];
            echo "===Get Equivalent record in $quotab (qid = $qid)<br>";
            $notmatchArr = array();
            $qrquo = "SELECT $comparationcolumn FROM $quotab WHERE quono = '$quono' AND cid = $cid AND bid = $bid AND qid = $qid ";
            $objSQLquo = new SQL($qrquo);
            $quodatarow = $objSQLquo->getResultOneRowArray();
            if (empty($quodatarow)) {
                echo "<font class='bg-danger'>Cannot find record of qid = $qid in $quotab</font><br>";
                $errcnt ++;
            } else {
                echo "<table class='table table-sm table-responsive'>";
                echo "<tr>"
                . "<th>Table Name</th>";
                foreach ($orddatarow as $index => $val) {
                    echo "<th>$index</th>";
                }
                echo "</tr>";
                echo "<tr>";
                echo "<td>$ordtab</td>";
                foreach ($orddatarow as $index => $val) {
                    if ($quodatarow[$index] != $orddatarow[$index]) {
                        $bg = 'class="bg-danger"';
                    } else {
                        $bg = 'class="bg-info"';
                    }
                    echo "<td $bg>$val</td>";
                }
                echo "</tr>";
                echo "<tr>";
                echo "<td>$quotab</td>";
                foreach ($quodatarow as $index => $val) {
                    if ($quodatarow[$index] != $orddatarow[$index]) {
                        $bg = 'class="bg-danger"';
                        $notmatchArr[] = $index;
                    } else {
                        $bg = 'class="bg-info"';
                    }
                    echo "<td $bg>$val</td>";
                }
                echo "</tr>";
                echo "</table>";
            }
            echo "<b>";
            if ($errcnt > 0) {
                echo "ITEM CANNOT BE FIND IN THE QUOTATION.<br>";
            } elseif (!empty($notmatchArr)) {
                foreach ($notmatchArr as $val) {
                    echo "$val is not the same, [ $quotab = $quodatarow[$val] | $ordtab = $orddatarow[$val] ]<br>";
                }
            } else {
                echo "ALL RECORD MATCHES<br>";
            }
            echo "</b>";
            echo "===End Get Equivalent record in $quotab (qid = $qid)<br>";
        }
        $result = 'ok';
    } catch (Exception $e) {
        echo $e->getMessage() . "<br>";
        $result = 'fail';
    }

    echo "</div>";
    echo "====End comparing data between Orderlist and Quotation====<br>";
    return $result;
}

function compareOrderlistWithCustomerPayment($period, $quono, $cid, $bid, $docount, $invcotype, $invno) {
    echo "<div class='border border-light'>";
    echo "====Begin comparing data between Orderlist and Customer Payment====<br>";
    $ordtab = "orderlistnew_pst_$period";
    $cusptab = "customer_payment_pst_$period";
    try {
        $qrord = "SELECT * FROM $ordtab WHERE quono = '$quono' AND cid = $cid AND bid = $bid AND docount = $docount";
        $objSQLord = new SQL($qrord);
        $orddataset = $objSQLord->getResultRowArray();
        echo "\$qrord = $qrord<br>";
        if (empty($orddataset)) {
            throw new Exception("There's no record in $ordtab for $quono [cid = $cid;bid = $bid;docount = $docount]");
        }
        echo "===Calculating Total Amount in Orderlist<br>";
        $ordamount = 0;
        $orddiscount = 0;
        $ordgst = 0;
        $ordtotamount = 0;
        foreach ($orddataset as $orddatarow) {
            $amount = (float) $orddatarow['amountmat'] + (float) $orddatarow['amountpmach'] + (float) $orddatarow['amountcncmach'] + (float) $orddatarow['amountother'];
            $discount = (float) $orddatarow['discountmat'] + (float) $orddatarow['discountpmach'] + (float) $orddatarow['discountcncmach'] + (float) $orddatarow['discountother'];
            $gst = (float) $orddatarow['gstmat'] + (float) $orddatarow['gstpmach'] + (float) $orddatarow['gstcncmach'] + (float) $orddatarow['gstother'];
            $totamount = $amount - $discount + $gst;
            $ordamount += $amount;
            $orddiscount += $discount;
            $ordgst += $gst;
            $ordtotamount += $totamount;
            unset($amount);
            unset($discount);
            unset($gst);
            unset($totamount);
        }
        echo "<div class='container bg-primary'>";
        echo "Total : " . number_format($ordamount, 2) . "<br>";
        echo "Discount : " . number_format($orddiscount, 2) . " <br>";
        echo "GST : " . number_format($ordgst, 2) . " <br>";
        echo "Grand Total: " . number_format(($ordtotamount), 2) . "<br>";
        echo "</div>";
        echo" ==get the InvAmount from $cusptab table<br>";
        $qrcusp = "SELECT * FROM $cusptab WHERE invcotype = '$invcotype' AND invno = '$invno' AND cid = $cid AND quono = '$quono' AND docount = '$docount'";
        $objSQLcusp = new SQL($qrcusp);
        $cuspdatarow = $objSQLcusp->getResultOneRowArray();
        if (empty($cuspdatarow)) {
            throw new Exception("There's no payment data in $cusptab for $quono [cid = $cid;invoicerunno = " . $invcotype . $invno . ";docount = $docount");
        }
        $invamount = (float) $cuspdatarow['invamount'];
        $invgst = (float) $cuspdatarow['gst'];
        $invtotamount = $invamount + $invgst;
        echo "<div class='container bg-secondary'>";
        echo "Total : " . number_format($invamount, 2) . "<br>";
        echo "GST : " . number_format($invgst, 2) . " <br>";
        echo "Grand Total: " . number_format(($invtotamount), 2) . "<br>";
        echo "</div>";
        if ($invtotamount != $ordtotamount) {
            echo "<p class='bg-danger'>TOTAL AMOUNT IN $ordtab v $cusptab DOESN'T MATCH</p><br>";
        } else {
            echo "<p class='bg-success'>TOTAL AMOUNT IN $ordtab v $cusptab MATCHES</p><br>";
        }
        $result = 'ok';
    } catch (Exception $e) {
        echo $e->getMessage() . "<br>";
        $result = 'fail';
    }
    echo "====End comparing data between Orderlist and Customer Payment ====<br>";
    echo "</div>";
    return $result;
}

// invoice start from what period
$period = '2103';
$cuspaytab = "customer_payment_pst_$period";
$ordtab = "orderlistnew_pst_$period";
$sqlinv = "select * from $cuspaytab ORDER BY quono, docount";
$objsql = new SQL($sqlinv);
echo "\$sqlinv = $sqlinv <br>";
$result1 = $objsql->getResultRowArray();
//
//print_r($result1);
$totalcount = 0;
$delcount = 0;
$jcudpatecount = 0;
foreach ($result1 as $array) {
    $totalcount++;
//    print_r($array);
    $cid = $array['cid'];
    $bid = $array['bid'];
    $quono = $array['quono'];
    $docount = $array['docount'];
    $invcotype = $array['invcotype'];
    $invno = $array['invno'];
    $invdate = $array['invdate'];
    $invamount = $array['invamount'];
    echo "<div class='container border border-success'>";
    echo "<p class='bg-primary'>start  of record $totalcount , quono = $quono</p>";
    echo "$totalcount , cid = $cid , bid = $bid, quono = $quono, invoice no = " . $invcotype . $invno . ", amount = $invamount <br>";
    echo "start the checking of the table value <br>";
    ## check the quotation record is correctly insert into quotation tables
    ## sample SELECT * FROM quotationnew_pst_2103 WHERE quono = 'A&N 2103 002 '
    ## check field odissue = 'yes' ?
//    Check record in quono list first 
    try {
        $ql_dataset = get_QuonoListRecord($quono, $cid, $bid);
        if (empty($ql_dataset)) {
            Throw new Exception("Cannot find any record in quono_list");
        }
        $quotab = $ql_dataset['quotab'];
        //check table exist or not
        if (!checkTableExist($quotab)) {
            throw new Exception("$quotab has not yet been generated");
        }
        $quoperiod = $ql_dataset['period'];
        $quodataset = get_QuotationRecord($quotab, $quono, $cid, $bid);
        if (empty($quodataset)) {
            Throw new Exception("Cannot find any record in $quotab");
        }
        $quodtnumrow = count($quodataset);
        echo "Detected $quodtnumrow items in Quono [$quono]<br>";
        $odissuecnt = 0;
        ?>
        <div>
            <table class="table table-sm table-responsive">
                <thead>
                    <tr>
                        <?php
                        foreach ($quodataset[0] as $index => $val) {
                            echo "<th>$index</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($quodataset as $datarow) {
                        if ($datarow['odissue'] != 'yes') {
                            $bg_q = 'class="bg-danger"';
                        } else {
                            $bg_q = 'class="bg-primary"';
                        }
                        echo "<tr $bg_q>";
                        foreach ($datarow as $index => $val) {
                            echo "<th>$val</th>";
                            if ($index == 'odissue') {
                                if ($val == 'yes') {
                                    $odissuecnt++;
                                }
                            }
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        if ($odissuecnt == 0) {
            unset($odissuecnt);
            throw new Exception('Quotation has not yet been Issued!');
        }
        echo "$odissuecnt of $quodtnumrow items has been issued into orderlist<br>";
        unset($odissuecnt);

        //Comparing Orderlist record with QUotation Record
        $chckOrdvQuo = compareOrderlistWithQuotation($period, $quoperiod, $quono, $cid, $bid, $docount);
        if ($chckOrdvQuo == 'fail') {
            throw new Exception('Cannot continue Comparing Orderlist and Quotation, Skipping this data.');
        }

        //Comparing Orderlist VS Customer_Payment
        $chckOrdvCusPay = compareOrderlistWithCustomerPayment($period, $quono, $cid, $bid, $docount, $invcotype, $invno);
        if ($chckOrdvCusPay == 'fail') {
            throw new Exception('Cannot continue Comparing Orderlist and Customer Payment, Skipping this data.');
        }
        # $checkquotabResult = isExistQuotable($cid, $bid, $quono,$docount);// is the quotation table exist?
        # if $checkquotabResult is true response the result is ok,then go to next step
        # else if $checkquotabResult is false response the result, an dgo to next step
        ## next step , if $checkquotabResult is true
        ## $ResultQuotab =  $sqlQueryQuotab($cid, $bid, $quono,$docount).
        ## check obissue = issued 
        ## also verify quono_list for the same records
        ## check orderlist record,
        ## SELECT * FROM orderlistnew_pst_2103 WHERE quono = 'A&N 2103 002' AND docount = 1
        ## $checkOrdTabResult = isExistOrdtab($cid, $bid, $quono,$docount);
        ## if $checkOrdTabResult is True
        //check orderlist record
        #$resultCheckOrdSch = compareOrderlistWithSchedulingData($period, $quono, $cid, $bid);
        ## $ResultOrdTab = $sqlQueryOrdTab($cid, $bid, $quono,$docount);
        ## noOfRecords = checkNoOfRecOrdTab($cid, $bid, $quono,$docount);
        ## sumOfAmount = checkSumOrdTab($cid, $bid, $quono,$docount), 
        ## the sum of amount on  field totalamount in orderlist for
        ## compare this value with sum of amount the field invamount in the customer_payment_pst_
        ## same calculation of discount for  sum of  
        ## all discount in orderlist vs customer_payment_pst_period
        ## check how orderlist record for the filter cid,bid, quono, docount 
        ## whether the same records (base on qid in orderlist) can be found thier counter 
        ## part in quotation tables  or not.
        ## quono_list and quotationnew_pst_period
        ## the same things to cross check if compare the orderlist record vs produciton_scheduling_period
        ## base on the filter cid, bid, quono, docount, iterate by nopositional
        ## also check the item, noposisiton, (orderlist and production_scheduling_period)
        ##  and jobno (production_scheduling_period)
    } catch (Exception $e) {
        echo "<p class='bg-danger'>" . $e->getMessage() . "<br></p>";
    }
    echo "<h6 class='bg-primary'>end  of record $totalcount , quono = $quono</h6>";
    echo "</div><br><br>";
}

