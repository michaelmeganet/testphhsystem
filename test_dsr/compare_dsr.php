<?php
include_once './test_dsr/salesreport.axios.php';
include_once './class/dbh.inc.php';
include_once './class/variables.inc.php';
include_once './class/phhdate.inc.php';
include_once './test_dsr/salesreport.func.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$periodList = getPeriod();
?>
<div id='testArea'>
    <form action='' target="_parent" method="POST">
        <label>Select Period</label>
        <br>
        <select name='period' id='period' v-model='period' class='custom-select-sm' >
            <?php
            foreach ($periodList as $period) {
                echo "<option value='$period'>$period</option>";
            }
            ?>
        </select>
        <select name='day' id='day' v-model='day' class='custom-select-sm' >
            <option v-for='day in dayList' v-bind:value='day'>{{day}}</option>
            ?>
        </select>
        <input type='submit' value='Submit' />
    </form>
</div>

<div class='container-fluid'>
    <?php
    if (isset($_POST['period'])) {
        $period = $_POST['period'];
        $day = $_POST['day'];
        echo "Selected period = $period<br>";
        echo "Selected day = $day<br>";
        try {
            echo "<div class='border border-success'>";
            $tab = "testdailyreport_$period";
            echo "====Check if $tab exists or not.<br> ";
            if (checkTableExists($tab) == 'NO') {
                echo "$tab is not yet generated.... <br>";
                throw new Exception();
            } else {
                echo "CHECK DATA MANUALLY INTO ORDERLIST<BR>";
                $year = intval('20' . substr($period, 0, 2));
                $month = intval(substr($period, -2));
                $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
                echo "year = $year; month = $month; day = $day;<br>";
                $dateTime = $date . " 16:30:59";
                echo "cutofftime = $dateTime<br>";

                $prevDate = date('Y-m-d', mktime(0, 0, 0, $month, $day - 1, $year));
                $prevDateTime = $prevDate . " 16:31:00";
                echo "starttime = $prevDateTime<br>";
                $prevDateMonth = date('Y-m-d', mktime(0, 0, 0, $month - 1, 1, $year));
                $prevPeriod = substr($prevDateMonth, 2, 2) . substr($prevDateMonth, 5, 2);

                $nextDate = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
                $nextDateMonth = date('Y-m-d', mktime(0, 0, 0, $month + 1, 28, $year));
                $nextPeriod = substr($nextDate, 2, 2) . substr($nextDateMonth, 5, 2);
                //GENERATE TABLES
                echo "===Begin fetch distinct Quono / AID records===<br>";
                $ordtabPST = "orderlistnew_pst_$period";
                $nextordtabPST = "orderlistnew_pst_$nextPeriod";
                $prevordtabPST = "orderlistnew_pst_$prevPeriod";

                $ordtabPSVPMB = "orderlistnew_psvpmb_$period";
                $nextordtabPSVPMB = "orderlistnew_psvpmb_$nextPeriod";
                $prevordtabPSVPMB = "orderlistnew_psvpmb_$prevPeriod";

                //BEGIN TOTAL TRANSACTION COUNT

                $qrCurPST = "SELECT DISTINCT quono,aid_cus "
                        . "FROM $ordtabPST "
                        . "WHERE datetimeissue_ol >= '$prevDateTime' "
                        . "AND datetimeissue_ol <= '$dateTime' "
                        . "AND `ivdate` <> '0000-00-00'";
                $qrNextPST = "SELECT DISTINCT quono,aid_cus "
                        . "FROM $nextordtabPST "
                        . "WHERE datetimeissue_ol >= '$prevDateTime' "
                        . "AND datetimeissue_ol <= '$dateTime' "
                        . "AND `ivdate` <> '0000-00-00'";
                $qrPrevPST = "SELECT DISTINCT quono,aid_cus "
                        . "FROM $prevordtabPST "
                        . "WHERE datetimeissue_ol >= '$prevDateTime' "
                        . "AND datetimeissue_ol <= '$dateTime' "
                        . "AND `ivdate` <> '0000-00-00'";

                if (checkTableExists($nextordtabPST) == 'YES') {
                    $qrPDSPST = "SELECT * FROM( " . $qrNextPST . " UNION ALL " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
                } else {
                    $qrPDSPST = "SELECT * FROM( " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
                }
                echo "<pre style='color:black'>$qrPDSPST</pre>";
                $objSQLPDSPST = new SQL($qrPDSPST);
                $legacy_OL_quono_Result = $objSQLPDSPST->getResultRowArray();
                if (empty($legacy_OL_quono_Result)) {
                    echo "There's no orderlist record for interval of $prevDateTime to $dateTime in $ordtabPST / $nextordtabPST / $prevordtabPST <br>";

                    echo "===End fetch distinct Quono / AID records===<br>";
                } else {
                    echo "Found " . count($legacy_OL_quono_Result) . " Transactions for interval of $prevDateTime to $dateTime<br>";

                    echo "===End fetch distinct Quono / AID records===<br>";
                    foreach ($legacy_OL_quono_Result as $legacy_OL_quono_datarow) {
                        $lold_quono = $legacy_OL_quono_datarow['quono'];
                        $lold_aid_cus = $legacy_OL_quono_datarow['aid_cus'];
                        echo "====Begin fetching details for $lold_quono ====<br>";

                        $qrCurPST2 = "SELECT * "
                                . "FROM $ordtabPST "
                                . "WHERE datetimeissue_ol >= '$prevDateTime' "
                                . "AND datetimeissue_ol <= '$dateTime' "
                                . "AND `ivdate` <> '0000-00-00' "
                                . "AND `quono` = '$lold_quono' "
                                . "AND `aid_cus` = $lold_aid_cus";
                        $qrNextPST2 = "SELECT * "
                                . "FROM $nextordtabPST "
                                . "WHERE datetimeissue_ol >= '$prevDateTime' "
                                . "AND datetimeissue_ol <= '$dateTime' "
                                . "AND `ivdate` <> '0000-00-00' "
                                . "AND `quono` = '$lold_quono' "
                                . "AND `aid_cus` = $lold_aid_cus";
                        $qrPrevPST2 = "SELECT * "
                                . "FROM $prevordtabPST "
                                . "WHERE datetimeissue_ol >= '$prevDateTime' "
                                . "AND datetimeissue_ol <= '$dateTime' "
                                . "AND `ivdate` <> '0000-00-00' "
                                . "AND `quono` = '$lold_quono' "
                                . "AND `aid_cus` = $lold_aid_cus";

                        if (checkTableExists($nextordtabPST) == 'YES') {
                            $qrPDSPST2 = $qrNextPST2 . " UNION ALL " . $qrCurPST2 . " UNION ALL " . $qrPrevPST2;
                        } else {
                            $qrPDSPST2 = $qrCurPST2 . " UNION ALL " . $qrPrevPST2;
                        }
                        echo "<pre style='color:black'>$qrPDSPST2</pre>";
                        $objSQLPDSPST2 = new SQL($qrPDSPST2);
                        $legacy_OL_Details = $objSQLPDSPST2->getResultRowArray();
                        $lgc_amountmat = 0;
                        $lgc_discountmat = 0;
                        $lgc_gstmat = 0;
                        $lgc_amountsubtotalmat = 0;
                        $lgc_amountpmach = 0;
                        $lgc_discountpmach = 0;
                        $lgc_gstpmach = 0;
                        $lgc_amountsubtotalpmach = 0;
                        $lgc_amountcncmach = 0;
                        $lgc_discountcncmach = 0;
                        $lgc_gstcncmach = 0;
                        $lgc_amountsubtotalcncmach = 0;
                        $lgc_amountother = 0;
                        $lgc_discountother = 0;
                        $lgc_gstother = 0;
                        $lgc_amountsubtotalother = 0;
                        $lgc_totalamount = 0;
                        echo "====End fetching details for $lold_quono ====<br><br>";
                        echo "==+++ Begin fetching price values +++==<br>";
                        foreach ($legacy_OL_Details as $datarow) {
                            $lgc_amountmat += (float) $datarow['amountmat'];
                            $lgc_discountmat += (float) $datarow['discountmat'];
                            $lgc_gstmat += (float) $datarow['gstmat'];
                            $lgc_subtotalmat = (float) $datarow['amountmat'] - (float) $datarow['discountmat'] + (float) $datarow['gstmat'];
                            $lgc_amountsubtotalmat += $lgc_subtotalmat;

                            $lgc_amountpmach += (float) $datarow['amountpmach'];
                            $lgc_discountpmach += (float) $datarow['discountpmach'];
                            $lgc_gstpmach += (float) $datarow['gstpmach'];
                            $lgc_subtotalpmach = (float) $datarow['amountpmach'] - (float) $datarow['discountpmach'] + (float) $datarow['gstpmach'];
                            $lgc_amountsubtotalpmach += $lgc_subtotalpmach;

                            $lgc_amountcncmach += (float) $datarow['amountcncmach'];
                            $lgc_discountcncmach += (float) $datarow['discountcncmach'];
                            $lgc_gstcncmach += (float) $datarow['gstcncmach'];
                            $lgc_subtotalcncmach = (float) $datarow['amountcncmach'] - (float) $datarow['discountcncmach'] + (float) $datarow['gstcncmach'];
                            $lgc_amountsubtotalcncmach += $lgc_subtotalcncmach;

                            $lgc_amountother += (float) $datarow['amountother'];
                            $lgc_discountother += (float) $datarow['discountother'];
                            $lgc_gstother += (float) $datarow['gstother'];
                            $lgc_subtotalother = (float) $datarow['amountother'] - (float) $datarow['discountother'] + (float) $datarow['gstother'];
                            $lgc_amountsubtotalother += $lgc_subtotalother;

                            $lgc_totalamount += ($lgc_subtotalmat + $lgc_subtotalpmach + $lgc_subtotalcncmach + $lgc_subtotalother);
                        }
                        $lgc_set_array = array(
                            'amountmat' => $lgc_amountmat,
                            'discountmat' => $lgc_discountmat,
                            'gstmat' => $lgc_gstmat,
                            'amountsubtotalmat' => $lgc_amountsubtotalmat,
                            'amountpmach' => $lgc_amountpmach,
                            'discountpmach' => $lgc_discountpmach,
                            'gstpmach' => $lgc_gstpmach,
                            'amountsubtotalpmach' => $lgc_amountsubtotalpmach,
                            'amountcncmach' => $lgc_amountcncmach,
                            'discountcncmach' => $lgc_discountcncmach,
                            'gstcncmach' => $lgc_gstcncmach,
                            'amountsubtotalcncmach' => $lgc_amountsubtotalcncmach,
                            'amountother' => $lgc_amountother,
                            'discountother' => $lgc_discountother,
                            'gstother' => $lgc_gstother,
                            'amountsubtotalother' => $lgc_amountsubtotalother,
                            'totalamount' => $lgc_totalamount
                        );
//                        echo "legacy price set = <br>";
//                        echo "\$lgc_amountmat = " . $lgc_amountmat . "<br>";
//                        echo "\$lgc_discountmat = " . $lgc_discountmat . "<br>";
//                        echo "\$lgc_gstmat = " . $lgc_gstmat . "<br>";
//                        echo "\$lgc_amountsubtotalmat = " . $lgc_amountsubtotalmat . "<br>";
//                        echo "\$lgc_amountpmach = " . $lgc_amountpmach . "<br>";
//                        echo "\$lgc_discountpmach = " . $lgc_discountpmach . "<br>";
//                        echo "\$lgc_gstpmach = " . $lgc_gstpmach . "<br>";
//                        echo "\$lgc_amountsubtotalpmach = " . $lgc_amountsubtotalpmach . "<br>";
//                        echo "\$lgc_amountcncmach = " . $lgc_amountcncmach . "<br>";
//                        echo "\$lgc_discountcncmach = " . $lgc_discountcncmach . "<br>";
//                        echo "\$lgc_gstcncmach = " . $lgc_gstcncmach . "<br>";
//                        echo "\$lgc_amountsubtotalcncmach = " . $lgc_amountsubtotalcncmach . "<br>";
//                        echo "\$lgc_amountother = " . $lgc_amountother . "<br>";
//                        echo "\$lgc_discountother = " . $lgc_discountother . "<br>";
//                        echo "\$lgc_gstother = " . $lgc_gstother . "<br>";
//                        echo "\$lgc_amountsubtotalother = " . $lgc_amountsubtotalother . "<br>";
//                        echo "\$lgc_totalamount = " . $lgc_totalamount . "<br>";
                        echo "==+++ End fetching price values +++==<br>";
                        echo "==Compare to new function==<br>";
                        $qrNewFunction = "SELECT * FROM $tab WHERE quono = '$lold_quono' AND aid_cus = $lold_aid_cus AND date = '$date'";
                        $objSQLNewFunction = new SQL($qrNewFunction);
                        $NF_dataset = $objSQLNewFunction->getResultOneRowArray();
                        if (empty($NF_dataset)) {
                            echo "<b> CANNOT COMPARE, THERE'S NO DATA IN NEW FUNCTION DATASET WITH QUONO = $lold_quono AND aid_cus = $lold_aid_cus in date = $date<br>";
                        } else {
                            echo "Comparing : <br>";
                            echo "==DATE = $date<br>";
                            echo "==QUONO = $lold_quono<br>";
                            echo "==SALESPERSON = {$NF_dataset['salesperson']}<br>";
                            unset($NF_dataset['dsrid']);
                            unset($NF_dataset['date']);
                            unset($NF_dataset['quono']);
                            unset($NF_dataset['aid_cus']);
                            unset($NF_dataset['salesperson']);
                            unset($NF_dataset['currency']);
                            echo "<table class='table table-bordered table-responsive'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th> Table Name </th>";
                            foreach ($lgc_set_array as $key => $priceval) {
                                echo "<th>$key</th>";
                            }
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            echo "<tr>";
                            echo "<td> LEGACY </td>";
                            foreach ($lgc_set_array as $key => $priceval) {
                                if (($lgc_set_array[$key]) == ($NF_dataset[$key])) {
                                    echo "<td class='bg-primary'>".($priceval)."</td>";                                    
                                } else {
                                    echo "<td class='bg-danger'>".($priceval)."</td>";
                                }
                            }
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td> NEW_FUNCTION </td>";
                            foreach ($NF_dataset as $key => $priceval) {
                                if (($lgc_set_array[$key]) == ($NF_dataset[$key])) {
                                    echo "<td class='bg-primary'>".($priceval)."</td>";                                    
                                } else {
                                    echo "<td class='bg-danger'>".($priceval)."</td>";
                                }
                            }
                            echo "</tr>";
                            echo "</tbody>";
                            echo "</table>";
                        }
                        echo "==End Compare to new function==<br>";
                    }
                }
            }
        } catch (Exception $e) {
            echo "Exception occurs, Cannot continue process<br>";
        }
        echo "</div>";
    }
    ?>
</div>

<script>
    var testVue = new Vue({
        el: '#testArea',
        data: {
            period: '',
            month: '',
            year: '',
            dayList: '',
            day: '',
        },
        watch: {
            period: function (val) {
                this.month = parseInt(val.substr(2, 2));
                this.year = parseInt('20' + val.substr(0, 2));
                this.getDayList();
            }
        },
        methods: {
            getDayList: function () {
                let year = this.year;
                let month = this.month;
                let dat = new Date(year, month, 0).getDate();
                this.dayList = dat;

            }
        }
    });


</script>

