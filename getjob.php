<?php

include "./class/dbh.inc.php";
include "./class/variables.inc.php";

// function updatejobcodesid($jobcode, $period, $sid){
//     $sql = "UPDATE jobcodesid SET sid = $sid , period = $period "
//             . "WHERE jobcode = '$jobcode' ";
//     echo "\$sql = $sql <br>";
//     $objSQL = new SQL($sql);
//     $result = $objSQL->getUpdate();
//     return $result;
// }
// function insBySqlOutput2102($sql){
    
//      $objSQL = new SQL($sql);
//      $insResult = $objSQL->InsertData();
//      return $insResult;
// //     if ($insResult == 'insert ok!') { //if insert succesful
// //         
// //         return $insResult;
// //     }else{
// ////         throw new Exception("<font style='color:red'>can't insert.</font>", 102);
// //         return $insResult;
// //     }
    
// }
// function IsExistOutput2102($sid) {

//     $output2102 = "production_output_2102";
//     $sqloutput2102 = "select * from $output2102 where sid = '$sid'";
//     $objoutput = new SQL($sqloutput2102);
//     $resultoutput = $objoutput->getResultRowArray();
//     return $resultoutput;


// }

// function IsExistOutput2103($sid) {

//     $output2103 = "production_output_2103";
//     $sqloutput2103 = "select * from $output2103 where sid = '$sid'";
//     echo "in function IsExistOutput2103 the sid is $sid <br>";
//     echo "\$sqloutput2103 = $sqloutput2103 <br>";
//     $objoutput = new SQL($sqloutput2103);
//     $resultoutput = $objoutput->getResultRowArray();
//     echo "<br> in IsExistOutput2103, var_dump resultoutput<br> ";
//     var_dump($resultoutput);
//     echo "<br>";
//     return $resultoutput;


// }

// function insertToOutput2102($Insert_Array){
    
//             echo "<br> c<br>";
//             var_dump($Insert_Array);
//             echo "<br>";

//             $qrins = "INSERT INTO $prodtab SET ";
//             $qrins_debug = "INSERT INTO $prodtab SET ";
//             $arrCnt = count($Insert_Array);
//             $cnt = 0;
//             foreach ($Insert_Array as $key => $val) {
//             $cnt++;
//             $qrins .= " $key =:$key ";
//             $qrins_debug .= " $key = '$val' ";
//             if ($cnt != $arrCnt) {
//             $qrins .= " , ";
//             $qrins_debug .= " , ";
//             }
//             }

//             echo "<br><br>\$qrins = $qrins <br><br>";
//             echo "<br><br>\$qrins_debug= $$qrins_debug <br><br>";
//             echo "<br>$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$<br>";
            
//             $objSQLlog = new SQLBINDPARAM($qrins, $Insert_Array);
//             $insResult = $objSQLlog->InsertData2();
//             echo "===DEBUG LOG QR = $qrins_debug <br>";
//             echo "+++===LOG RESULT = $insResult<br>";
//             return $insResult;
// }

// function deloutput2103($sid2){
//     $output2103 = "production_output_2103";
//     $sql = "DELETE FROM $output2103 WHERE sid = $sid2 ";
//     echo "\$sql = $sql <br>";
//     $objSql = new SQL($sql);
//     $result = $objSql->getDelete();
//     return $result;
// }

// function delSche2103($sid2){
//     $pro2103 = "production_scheduling_2103";
//     $sql = "DELETE FROM $pro2103 WHERE sid = $sid2 ";
//     echo "\$sql = $sql <br>";
//     $objSql = new SQL($sql);
//     $result = $objSql->getDelete();
//     return $result;
    
// }
// $pro2102 = "production_scheduling_2102";
// $pro2103 = "production_scheduling_2103";
// $output2102 = "production_output_2102";
// $output2103 = "production_output_2103";
// $sql2102 = "select * from $pro2102 where operation = 1 or  operation = 3 order by quono ";
// $sql2103 = "select * from $pro2103 where operation = 1 or  operation = 3  order by quon";

// invoice start from what period
$period = '2103';
$sqlinv = "select * from customer_payment_pst_".$period;
$objsql = new SQL($sqlinv);
echo "\$sqlinv = $sqlinv <br>";
$result1 = $objsql->getResultRowArray();
//
//print_r($result1);
$totalcount = 0;
$delcount = 0;
$jcudpatecount = 0;
foreach ($result1 as $array){
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

    echo "<br>^^^^^^^^^^^^^^^^start  of record $totalcount , quono = $quono^^^^^^^^^<br>";
    echo "$totalcount , cid = $cid , bid = $bid, quono = $quono, invoice no = $invcotype.$invno, amount = $invamount <br>";
    echo "start the checking of the table value <br>";
    
    echo "<br>^^^^^^^^^^^^^^^^end of record $totalcount  , quono =  $quono^^^^^^^^^^<br>";

}

