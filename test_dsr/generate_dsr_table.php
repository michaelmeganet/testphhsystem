<?php
include_once 'salesreport.axios.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_POST['period'])) {
    $period = $_POST['period'];
    $aid = 'all';
    echo "Selected period = $period<br>";
    echo "Begin generating record for Period = $period<br>";
    $year = (int) '20' . substr($period, 0, 2);
    $month = (int) substr($period, -2);
    $totalday = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    echo "Year = $year; month = $month <br>";
    echo "===Total days in here : $totalday days.<br>";
    $dsr_record = array();
    for ($day = 1; $day <= $totalday; $day++) {
        echo "==========Check Records in " . $day . "-" . $month . "-" . $year . "==========<br>";
        $quono_dataset = get_DailySalesQuonoBySalesperson($period, $day, $aid);
        if (!empty($quono_dataset)) {
            echo "Found " . count($quono_dataset) . " Records.<br>";
            $ck_date = date('Y-m-d', mktime($day, 0, 0, $month, $day, $year));
            $salesList = get_distinct_salesperson($quono_dataset, 'aid_cus');
            foreach ($salesList as $salesrow) {
                $ck_aid = $salesrow['aid'];
                $ck_count = $salesrow['count'];
                $dtl_aid = get_adminDetail($ck_aid);
//                print_r($dtl_aid);
                echo "+=+=  " . $dtl_aid['name'] . " has " . $ck_count . " Transactions.<br>";
            }
            echo "BEGIN CALCULATING RECORDS<br>";
            $i = 0;
            foreach ($quono_dataset as $quono_datarow) {
                $i++;
                $rw_quono = $quono_datarow['quono'];
                $rw_aid = $quono_datarow['aid_cus'];
                $rw_dtl_aid = get_adminDetail($rw_aid);
                $rw_aid_name = $rw_dtl_aid['name'];
                $rw_currency = $quono_datarow['currency'];
                $currencyDetails = get_Currency($rw_currency);
                if ($currencyDetails != 'empty') {
                    $cr_symbol = $currencyDetails['currencysymbol'];
                    $cr_decimal = $currencyDetails['decimalsymbol'];
                    $cr_comma = $currencyDetails['commasymbol'];
                    $cr_centsincluded = $currencyDetails['centsincluded'];
                } else {
                    $cr_symbol = 'RM';
                    $cr_decimal = '.';
                    $cr_comma = ',';
                    $cr_centsincluded = 'yes';
                }

                $rw_bid = $quono_datarow['bid'];
                $rw_cid = $quono_datarow['cid'];
                echo "===Get Detail Records of $rw_quono in $day $month $year===<br>";
                $DSRDetails_dataset = get_DSRRecordsByQuonoCid($period, $day, $rw_quono, $rw_cid, $aid);
                echo "===++Calculate Price++===<br>";
                $priceDetails = get_PriceByDateQuonoAid($DSRDetails_dataset);
                echo "===++End Price Calculation++===<br>";
                $dsr_record[$i]['date'] = $ck_date;
                $dsr_record[$i]['quono'] = $rw_quono;
                $dsr_record[$i]['aid_cus'] = $rw_aid;
                $dsr_record[$i]['salesperson'] = $rw_aid_name;
                $dsr_record[$i]['currency'] = $rw_currency;
                foreach ($priceDetails as $keyprice => $valprice) {
                    $dsr_record[$i][$keyprice] = $valprice;
                }
                echo "===End Get Detail===<br>";
            }
            echo "END CALCULATING RECORDS<br>";
        } else {
            echo "no records found in $day - $month - $year <br>";
        }
        echo "==========End Check Records in " . $day . "-" . $month . "-" . $year . "==========<br><br>";
    }
    ?>
    Table Result = <br>;
    <table border='1'>
        <thead>
            <tr>
                <?php
                foreach ($dsr_record as $dsr_row) {
                    foreach ($dsr_row as $key => $dsr_details) {
                        echo "<th>$key</th>";
                    }
                    break;
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($dsr_record as $dsr_row) {
                echo "<tr>";
                foreach ($dsr_row as $key => $dsr_details) {
                    echo "<td>$dsr_details</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <?php
}



$periodList = getPeriod();
?>

<h3>Generate Table for Daily Sales Report by Month</h3>
<br>
<form action='' target="_parent" method="POST">
    <label>Select Period</label>
    <br>
    <select name='period' id='period' >
        <?php
        foreach ($periodList as $period) {
            echo "<option value='$period'>$period</option>";
        }
        ?>
    </select>
    <input type='submit' value='Submit' />
</form>

