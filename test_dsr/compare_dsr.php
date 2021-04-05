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
                $objSQLPDSPST = new SQL($qrPDSPST);
                $legacy_OL_quono_Result = $objSQLPDSPST->getResultRowArray();
                if (empty($legacy_OL_quono_Result)) {
                    echo "There's no orderlist record for interval of $prevDateTime to $dateTime in $ordtabPST / $nextordtabPST / $prevordtabPST <br>";
                } else {
                    echo "Found " . count($legacy_OL_quono_Result) . " Transactions for interval of $prevDateTime to $dateTime<br>";
                    foreach ($legacy_OL_quono_Result as $legacy_OL_quono_datarow) {
                        $lold_quono = $legacy_OL_quono_datarow['quono'];
                        $lold_aid_cus = $legacy_OL_quono_datarow['aid_cus'];

                        $qrCurPST = "SELECT * "
                                . "FROM $ordtabPST "
                                . "WHERE datetimeissue_ol >= '$prevDateTime' "
                                . "AND datetimeissue_ol <= '$dateTime' "
                                . "AND `ivdate` <> '0000-00-00' "
                                . "AND `quono` = '$lold_quono' "
                                . "AND `aid_cus = $lold_aid_cus";
                        $qrNextPST = "SELECT * "
                                . "FROM $nextordtabPST "
                                . "WHERE datetimeissue_ol >= '$prevDateTime' "
                                . "AND datetimeissue_ol <= '$dateTime' "
                                . "AND `ivdate` <> '0000-00-00'"
                                . "AND `quono` = '$lold_quono' "
                                . "AND `aid_cus = $lold_aid_cus";
                        $qrPrevPST = "SELECT * "
                                . "FROM $prevordtabPST "
                                . "WHERE datetimeissue_ol >= '$prevDateTime' "
                                . "AND datetimeissue_ol <= '$dateTime' "
                                . "AND `ivdate` <> '0000-00-00'"
                                . "AND `quono` = '$lold_quono' "
                                . "AND `aid_cus = $lold_aid_cus";

                        if (checkTableExists($nextordtabPST) == 'YES') {
                            $qrPDSPST = "SELECT * FROM( " . $qrNextPST . " UNION ALL " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
                        } else {
                            $qrPDSPST = "SELECT * FROM( " . $qrCurPST . " UNION ALL " . $qrPrevPST . ") AS tem";
                        }
                        $objSQLPDSPST2 = new SQL($qrPDSPST);
                        $legacy_OL_Details = $objSQLPDSPST2->getResultRowArray();
                        
                        
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

