<?php

include_once("dbh.inc.php");
include_once("variables.inc.php");

//function redirect($url) {
//    ob_start();
//    header('Location: '.$url);
//    ob_end_flush();
//    die();
//}
class Customers {
    ## properties
//    $accno
//    $co_name
//    $co_no
//    $co_code
//    $address1
//    $address2
//    $address3
//    $country
//    $telephone_sales
//    $fax_sales
//    $handphone_sales
//    $email_sales
//    $attn_sales
//    $telehpone_acc
//    $fax_acc
//    $handphone_acc
//    $email_acc
//    $attn_acc
//    $groups
//    $aid_cus
//    $terms
//    $credit_limit
//    $currency
//    $company
//    $status
//    $date_created
//    $remark
//    $credit_use
//    $one_do_one_inv
//    $can_not_migrate
//    $regular
//    $nobusy

    protected $getPostData;
    public $cid;

    public function __construct() {

        $this->getPostData = [];
    }

    public function customer_list() {

        $sql = "SELECT * FROM customer_list ORDER BY cid asc ";
        $objSQL = new SQL($sql);
        $result = $objSQL->getResultRowArray();

        return $result;
    }

    public function customer_list_numrowas() {

        $sql = "SELECT count(*) FROM customer_list ORDER BY cid ";
        $objSQL = new SQL($sql);
        // $result=  $this->conn->query($sql);
        $result = $objSQL->getRowCount();
        return $result;
    }

    public function create_customer_info($post_data = array()) {

        $this->getPostData = $post_data;
        $cid = $post_data['cid'];
        $this->cid = $cid;
        print_r($post_data);
        //    $accno
//    $co_name
//    $co_no
//    $co_code
//    $address1
//    $address2
//    $address3
//    $country
//    $telephone_sales
//    $fax_sales
//    $handphone_sales
//    $email_sales
//    $attn_sales
//    $telehpone_acc
//    $fax_acc
//    $handphone_acc
//    $email_acc
//    $attn_acc
//    $groups
//    $aid_cus
//    $terms
//    $credit_limit
//    $currency
//    $company
//    $status
//    $date_created
//    $remark
//    $credit_use
//    $one_do_one_inv
//    $can_not_migrate
//    $regular
//    $nobusy       
        if (isset($post_data['create_customer'])) {
            $accno = trim($post_data['accno']);
            $co_name = trim($post_data['co_name']);
            $co_no = trim($post_data['co_no']);
            $co_code = trim($post_data['co_code']);
            $address1 = trim($post_data['address1']);
            $address2 = trim($post_data['address2']);
            $address3 = trim($post_data['address3']);
            $country = trim($post_data['country']);
            $telephone_sales = trim($post_data['telephone_sales']);
            $fax_sales = trim($post_data['fax_sales']);
            $handphone_sales = trim($post_data['handphone_sales']);
            $email_sales = trim($post_data['email_sales']);
            $attn_sales = trim($post_data['attn_sales']);
            $telehpone_acc = trim($post_data['telehpone_acc']);
            $fax_acc = trim($post_data['fax_acc']);
            $handphone_acc = trim($post_data['handphone_acc']);
            $email_acc = trim($post_data['email_acc']);
            $attn_acc = trim($post_data['attn_acc']);
            $groups = trim($post_data['groups']);
            $aid_cus = trim($post_data['aid_cus']);
            $terms = trim($post_data['terms']);
            $credit_limit = trim($post_data['credit_limit']);
            $currency = trim($post_data['currency']);
            $company = trim($post_data['company']);
            $status = trim($post_data['status']);
            $date_created = trim($post_data['date_created']);
            $remarks = trim($post_data['remarks']);
            $credit_used = trim($post_data['credit_used']);
            $one_do_one_inv = trim($post_data['one_do_one_inv']);
            $cannot_migrate = trim($post_data['cannot_migrate']);
            $regular = trim($post_data['regular']);
            $nobusy = trim($post_data['nobusy']);
            $sql = "INSERT INTO customer_list ("
                    . "accno, co_name, co_no, co_code, address1,address2,address3,"
                    . "country, telephone_sales, fax_sales, handphone_sales, email_sales, attn_sales,"
                    . "telephone_acc, fax_acc, handphone_acc, email_acc, attn_acc, groups,"
                    . "aid_cus, terms, credit_limit, currency, company, status, "
                    . "date_created, remarks, credit_used, one_do_one_inv, cannot_migrate, regular, nobusy"
                    . ")"
                    . "VALUES ('$accno','$co_name', '$co_no', '$co_code', '$address1','$address2','$address3',"
                    . "'$country', '$telephone_sales', '$fax_sales', '$handphone_sales', '$email_sales', '$attn_sales',"
                    . "'$telehpone_acc', '$fax_acc', '$handphone_acc', '$email_acc', '$attn_acc', '$groups',"
                    . "'$aid_cus', '$terms', '$credit_limit', '$currency', '$company', '$status',"
                    . "'$date_created', '$remarks', '$credit_used', '$one_do_one_inv', '$cannot_migrate', '$regular', '$nobusy'"
                    . ") WHERE cid = $cid ";

//        $result=  $this->conn->query($sql);
            $objSQL = new SQL($sql);

            $result = $objSQL->InsertData();

            if ($result == 'insert ok!') {

                $_SESSION['message'] = "Successfully Created Student Info";
                echo "Successfully Created Customer Info<br>";

                header('Location: index.php');
            } else {
                $error = "Fail to Created Customer Info <br>";
                $_SESSION['message'] = "Please check this \$sql -> $sql";
                $url = "customercreatefail.php?err=$error";
                //    redirect($url);
                //header('Location: customercreatefail.php?err=$error');
            }

            unset($post_data['create_customer']);
        }
    }

    public function view_customer_by_cid($cid) {

        if (isset($cid)) {

//       $student_id= mysqli_real_escape_string($this->conn,trim($id));
            $cid = trim($cid);

            $sql = "Select * from customer_list where cid='$cid'";
            //echo "line 181, \$sql = $sql <br>"; 

            $objSQL = new SQL($sql);

            $result = $objSQL->getResultOneRowArray();
            return $result;
        }
    }

    public function update_customer_info($post_data = array()) {
        echo "print_r(\$post_data)";
        print_r($post_data);
        echo "<br>";
        $cid = $post_data['cid'];

        if (isset($post_data['update_customer']) && isset($post_data['cid'])) {

            //       $student_name= mysqli_real_escape_string($this->conn,trim($post_data['student_name']));
            //       $email_address= mysqli_real_escape_string($this->conn,trim($post_data['email_address']));
            //       $gender= mysqli_real_escape_string($this->conn,trim($post_data['gender']));
            //       $contact= mysqli_real_escape_string($this->conn,trim($post_data['contact']));
            //       $country= mysqli_real_escape_string($this->conn,trim($post_data['country']));
            //       $student_id= mysqli_real_escape_string($this->conn,trim($post_data['id']));
            $accno = trim($post_data['accno']);
            $co_name = trim($post_data['co_name']);
            $co_no = trim($post_data['co_no']);
            $co_code = trim($post_data['co_code']);
            $address1 = trim($post_data['address1']);
            $address2 = trim($post_data['address2']);
            $address3 = trim($post_data['address3']);
            $country = trim($post_data['country']);
            $telephone_sales = trim($post_data['telephone_sales']);
            $fax_sales = trim($post_data['fax_sales']);
            $handphone_sales = trim($post_data['handphone_sales']);
            $email_sales = trim($post_data['email_sales']);
            $attn_sales = trim($post_data['attn_sales']);
            $telephone_acc = trim($post_data['telephone_acc']);
            $fax_acc = trim($post_data['fax_acc']);
            $handphone_acc = trim($post_data['handphone_acc']);
            $email_acc = trim($post_data['email_acc']);
            $attn_acc = trim($post_data['attn_acc']);
            $groups = trim($post_data['groups']);
            $aid_cus = trim($post_data['aid_cus']);
            $terms = trim($post_data['terms']);
            $credit_limit = trim($post_data['credit_limit']);
            $currency = trim($post_data['currency']);
            $company = trim($post_data['company']);
            $status = trim($post_data['status']);
            $date_created = trim($post_data['date_created']);
            $remarks = trim($post_data['remarks']);
            $credit_used = trim($post_data['credit_used']);
            $one_do_one_inv = trim($post_data['one_do_one_inv']);
            $cannot_migrate = trim($post_data['cannot_migrate']);
            $regular = trim($post_data['regular']);
            $nobusy = trim($post_data['nobusy']);
            // co_name, co_no, co_code, address1,address2,address3,"
            // . "country, telephone_sales, fax_sales, handphone_sales, email_sales, attn_sales,"
            // . "telephone_acc, fax_acc, handphone_acc, email_acc, attn_acc, groups,"
            // . "aid_cus, terms, credit_limit, currency, company, status, "
            // . "date_created, remarks, credit_used, one_do_one_inv, cannot_migrate, regular, nobusy"

            $sql = "UPDATE customer_list SET "
                    . " accno='$accno', co_name='$co_name',co_no='$co_no', co_code='$co_code', address1='$address1', "
                    . " address2='$address2', address3='$address3', country='$country', telephone_sales='$telephone_sales', "
                    . " fax_sales='$fax_sales', handphone_sales='$handphone_sales', email_sales='$email_sales', attn_sales='$attn_sales', "
                    . " telephone_acc='$telephone_acc', fax_acc='$fax_acc', handphone_acc='$handphone_acc', email_acc='$email_acc',  "
                    . " attn_acc='$attn_acc', groups='$groups', aid_cus='$aid_cus', terms='$terms', credit_limit='$credit_limit',  "
                    . " currency='$currency', company='$company', status='$status', date_created='$date_created', remarks='$remarks', "
                    . " credit_used='$credit_used', one_do_one_inv='$one_do_one_inv', cannot_migrate='$cannot_migrate', "
                    . " regular='$regular', nobusy='$nobusy' "
                    . " WHERE cid = $cid ";

            $objSQL = new SQL($sql);

            $result = $objSQL->getUpdate();

            //    if($result == 'updated'){
            //        $_SESSION['message']="Successfully Updated Student Info";
            //    }

            if ($result == 'updated') {

                $_SESSION['message'] = "Successfully  Update Customer Info";
                echo "Successfully Created Customer Info<br>";
                $url = "index.php";
                // redirect($url);
                //    header('Location: index.php');
            } else {
                $error = "Fail to Update Customer Info <br>";
                $_SESSION['message'] = "Please check this \$sql -> $sql";
                $url = "index.php";
                redirect($url);
                //header('Location: customercreatefail.php?err=$error');
            }
            unset($post_data['update_customer']);
        }
    }

    public function delete_customer_info_by_id($cid) {

        // $cid = $this->cid;
        echo "\$cid = $cid <br>";
        if (isset($cid)) {
//       $student_id= mysqli_real_escape_string($this->conn,trim($cid));
            $cid = trim($cid);

            $sql = "DELETE FROM  customer_list  WHERE cid =$cid";
//        $result=  $this->conn->query($sql);
            echo "\$sql= $sql<br>";
            $objSQL = new SQL($sql);

            $result = $objSQL->getUpdate();
            if ($result) {
                $_SESSION['message'] = "Successfully Deleted Student Info";
            }
        }
        header('Location: index.php');
    }

    function __destruct() {
//    mysqli_close($this->conn);  
    }

}

class Customers2 {
    ## properties
//    $accno
//    $co_name
//    $co_no
//    $co_code
//    $address1
//    $address2
//    $address3
//    $country
//    $telephone_sales
//    $fax_sales
//    $handphone_sales
//    $email_sales
//    $attn_sales
//    $telehpone_acc
//    $fax_acc
//    $handphone_acc
//    $email_acc
//    $attn_acc
//    $groups
//    $aid_cus
//    $terms
//    $credit_limit
//    $currency
//    $company
//    $status
//    $date_created
//    $remark
//    $credit_use
//    $one_do_one_inv
//    $can_not_migrate
//    $regular
//    $nobusy

    protected $getPostData;
    public $cid;
    public $com;
    public $customertable;

    public function __construct($com) {
        #echo "Initialize Customers2 Class - Line 355<br>";
        $this->com = $com;
        $this->customertable = "customer_" . trim(strtolower($com));
        $this->getPostData = [];
    }

    public function customer_list() {
        $tblname = $this->customertable;
        $sql = "SELECT * FROM $tblname ORDER BY cid asc ";
        $objSQL = new SQL($sql);
        $result = $objSQL->getResultRowArray();

        return $result;
    }

    public function customer_list_numrowas() {
        $tblname = $this->customertable;

        $sql = "SELECT count(*) FROM $tblname ORDER BY cid ";
        $objSQL = new SQL($sql);
        // $result=  $this->conn->query($sql);
        $result = $objSQL->getRowCount();
        return $result;
    }

    public function create_customer_info($post_data = array()) {
        $tblname = $this->customertable;
        $this->getPostData = $post_data;
        $cid = $post_data['cid'];
        $this->cid = $cid;
        print_r($post_data);
        //    $accno
//    $co_name
//    $co_no
//    $co_code
//    $address1
//    $address2
//    $address3
//    $country
//    $telephone_sales
//    $fax_sales
//    $handphone_sales
//    $email_sales
//    $attn_sales
//    $telehpone_acc
//    $fax_acc
//    $handphone_acc
//    $email_acc
//    $attn_acc
//    $groups
//    $aid_cus
//    $terms
//    $credit_limit
//    $currency
//    $company
//    $status
//    $date_created
//    $remark
//    $credit_use
//    $one_do_one_inv
//    $can_not_migrate
//    $regular
//    $nobusy       
        if (isset($post_data['create_customer'])) {
            $accno = trim($post_data['accno']);
            $co_name = trim($post_data['co_name']);
            $co_no = trim($post_data['co_no']);
            $co_code = trim($post_data['co_code']);
            $address1 = trim($post_data['address1']);
            $address2 = trim($post_data['address2']);
            $address3 = trim($post_data['address3']);
            $country = trim($post_data['country']);
            $telephone_sales = trim($post_data['telephone_sales']);
            $fax_sales = trim($post_data['fax_sales']);
            $handphone_sales = trim($post_data['handphone_sales']);
            $email_sales = trim($post_data['email_sales']);
            $attn_sales = trim($post_data['attn_sales']);
            $telehpone_acc = trim($post_data['telehpone_acc']);
            $fax_acc = trim($post_data['fax_acc']);
            $handphone_acc = trim($post_data['handphone_acc']);
            $email_acc = trim($post_data['email_acc']);
            $attn_acc = trim($post_data['attn_acc']);
            $groups = trim($post_data['groups']);
            $aid_cus = trim($post_data['aid_cus']);
            $terms = trim($post_data['terms']);
            $credit_limit = trim($post_data['credit_limit']);
            $currency = trim($post_data['currency']);
            $company = trim($post_data['company']);
            $status = trim($post_data['status']);
            $date_created = trim($post_data['date_created']);
            $remarks = trim($post_data['remarks']);
            $credit_used = trim($post_data['credit_used']);
            $one_do_one_inv = trim($post_data['one_do_one_inv']);
            $cannot_migrate = trim($post_data['cannot_migrate']);
            $regular = trim($post_data['regular']);
            $nobusy = trim($post_data['nobusy']);
            $sql = "INSERT INTO $tblname ("
                    . "accno, co_name, co_no, co_code, address1,address2,address3,"
                    . "country, telephone_sales, fax_sales, handphone_sales, email_sales, attn_sales,"
                    . "telephone_acc, fax_acc, handphone_acc, email_acc, attn_acc, groups,"
                    . "aid_cus, terms, credit_limit, currency, company, status, "
                    . "date_created, remarks, credit_used, one_do_one_inv, cannot_migrate, regular, nobusy"
                    . ")"
                    . "VALUES ('$accno','$co_name', '$co_no', '$co_code', '$address1','$address2','$address3',"
                    . "'$country', '$telephone_sales', '$fax_sales', '$handphone_sales', '$email_sales', '$attn_sales',"
                    . "'$telehpone_acc', '$fax_acc', '$handphone_acc', '$email_acc', '$attn_acc', '$groups',"
                    . "'$aid_cus', '$terms', '$credit_limit', '$currency', '$company', '$status',"
                    . "'$date_created', '$remarks', '$credit_used', '$one_do_one_inv', '$cannot_migrate', '$regular', '$nobusy'"
                    . ") WHERE cid = $cid ";

//        $result=  $this->conn->query($sql);
            $objSQL = new SQL($sql);

            $result = $objSQL->InsertData();

            if ($result == 'insert ok!') {

                $_SESSION['message'] = "Successfully Created Customer Info";
                echo "Successfully Created Customer Info<br>";

                header('Location: index.php');
            } else {
                $error = "Fail to Created Customer Info <br>";
                $_SESSION['message'] = "Please check this \$sql -> $sql";
                $url = "customercreatefail.php?err=$error";
                //    redirect($url);
                //header('Location: customercreatefail.php?err=$error');
            }

            unset($post_data['create_customer']);
        }
    }

    public function view_customer_by_cid($cid) {
        #echo "Function View_Customer_by_cid - line 489<br>";
        $tblname = $this->customertable;
        if (isset($cid)) {

//       $student_id= mysqli_real_escape_string($this->conn,trim($id));
            $cid = trim($cid);

            $sql = "Select * from $tblname where cid='$cid'";
            #echo "line 497, \$sql = $sql <br>"; 

            $objSQL = new SQL($sql);

            $result = $objSQL->getResultOneRowArray();
            return $result;
        }
    }

    public function update_customer_info($post_data = array()) {
        $tblname = $this->customertable;
        #echo "print_r(\$post_data)";
        #print_r($post_data);
        #echo "<br>";
        $cid = $post_data['cid'];

        if (isset($post_data['update_customer']) && isset($post_data['cid'])) {

            //       $student_name= mysqli_real_escape_string($this->conn,trim($post_data['student_name']));
            //       $email_address= mysqli_real_escape_string($this->conn,trim($post_data['email_address']));
            //       $gender= mysqli_real_escape_string($this->conn,trim($post_data['gender']));
            //       $contact= mysqli_real_escape_string($this->conn,trim($post_data['contact']));
            //       $country= mysqli_real_escape_string($this->conn,trim($post_data['country']));
            //       $student_id= mysqli_real_escape_string($this->conn,trim($post_data['id']));
            $accno = trim($post_data['accno']);
            $co_name = trim($post_data['co_name']);
            $co_no = trim($post_data['co_no']);
            $co_code = trim($post_data['co_code']);
            $address1 = trim($post_data['address1']);
            $address2 = trim($post_data['address2']);
            $address3 = trim($post_data['address3']);
            $country = trim($post_data['country']);
            $telephone_sales = trim($post_data['telephone_sales']);
            $fax_sales = trim($post_data['fax_sales']);
            $handphone_sales = trim($post_data['handphone_sales']);
            $email_sales = trim($post_data['email_sales']);
            $attn_sales = trim($post_data['attn_sales']);
            $telephone_acc = trim($post_data['telephone_acc']);
            $fax_acc = trim($post_data['fax_acc']);
            $handphone_acc = trim($post_data['handphone_acc']);
            $email_acc = trim($post_data['email_acc']);
            $attn_acc = trim($post_data['attn_acc']);
            $groups = trim($post_data['groups']);
            $aid_cus = trim($post_data['aid_cus']);
            $terms = trim($post_data['terms']);
            $credit_limit = trim($post_data['credit_limit']);
            $currency = trim($post_data['currency']);
            $company = trim($post_data['company']);
            $status = trim($post_data['status']);
            $date_created = trim($post_data['date_created']);
            $remarks = trim($post_data['remarks']);
            $credit_used = trim($post_data['credit_used']);
            $one_do_one_inv = trim($post_data['one_do_one_inv']);
            $cannot_migrate = trim($post_data['cannot_migrate']);
            $regular = trim($post_data['regular']);
            $nobusy = trim($post_data['nobusy']);
            // co_name, co_no, co_code, address1,address2,address3,"
            // . "country, telephone_sales, fax_sales, handphone_sales, email_sales, attn_sales,"
            // . "telephone_acc, fax_acc, handphone_acc, email_acc, attn_acc, groups,"
            // . "aid_cus, terms, credit_limit, currency, company, status, "
            // . "date_created, remarks, credit_used, one_do_one_inv, cannot_migrate, regular, nobusy"

            $sql = "UPDATE $tblname SET "
                    . " accno='$accno', co_name='$co_name',co_no='$co_no', co_code='$co_code', address1='$address1', "
                    . " address2='$address2', address3='$address3', country='$country', telephone_sales='$telephone_sales', "
                    . " fax_sales='$fax_sales', handphone_sales='$handphone_sales', email_sales='$email_sales', attn_sales='$attn_sales', "
                    . " telephone_acc='$telephone_acc', fax_acc='$fax_acc', handphone_acc='$handphone_acc', email_acc='$email_acc',  "
                    . " attn_acc='$attn_acc', groups='$groups', aid_cus='$aid_cus', terms='$terms', credit_limit='$credit_limit',  "
                    . " currency='$currency', company='$company', status='$status', date_created='$date_created', remarks='$remarks', "
                    . " credit_used='$credit_used', one_do_one_inv='$one_do_one_inv', cannot_migrate='$cannot_migrate', "
                    . " regular='$regular', nobusy='$nobusy' "
                    . " WHERE cid = $cid ";

            $objSQL = new SQL($sql);

            $result = $objSQL->getUpdate();

            //    if($result == 'updated'){
            //        $_SESSION['message']="Successfully Updated Student Info";
            //    }

            if ($result == 'updated') {

                $_SESSION['message'] = "Successfully  Update Customer Info";
                #echo "Successfully Created Customer Info<br>";
                $url = "index.php";
                // redirect($url);
                //    header('Location: index.php');
            } else {
                $error = "Fail to Update Customer Info <br>";
                $_SESSION['message'] = "Please check this \$sql -> $sql";
                $url = "index.php";
                redirect($url);
                //header('Location: customercreatefail.php?err=$error');
            }
            unset($post_data['update_customer']);
        }
    }

    public function delete_customer_info_by_id($cid) {
        $tblname = $this->customertable;
        // $cid = $this->cid;
        echo "\$cid = $cid <br>";
        if (isset($cid)) {
//       $student_id= mysqli_real_escape_string($this->conn,trim($cid));
            $cid = trim($cid);

            $sql = "DELETE FROM  $tblname  WHERE cid =$cid";
//        $result=  $this->conn->query($sql);
            echo "\$sql= $sql<br>";
            $objSQL = new SQL($sql);

            $result = $objSQL->getUpdate();
            if ($result) {
                $_SESSION['message'] = "Successfully Deleted Student Info";
            }
        }
        header('Location: index.php');
    }

    function __destruct() {
//    mysqli_close($this->conn);  
    }

}

Class CustomerName extends Dbh {

    protected $cid;
    protected $com;

    public function __construct($cid, $com) {

        $this->cid = $cid;
        $this->com = $com;
    }

    public function getCustomerName() {

        $cid = $this->cid;
        $com = $this->com;

        $customertab = "customer_" . strtolower($com);




        $sql = "SELECT co_name FROM $customertab WHERE cid = $cid ";
//                    echo "\$sql = $sql    in getCustomerName <br>"; 
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $row3 = $stmt->fetch(PDO::FETCH_ASSOC);
        }
//            echo "print_r($row3)<br>";
//             print_r($row3);
//            extract($row3);
        $co_name = $row3['co_name'];

        return $co_name;
    }

}

Class QuotationPeriod {

    public $QuonoPeriod;

    public function __construct($period) {

        $this->QuonoPeriod = $period;
    }

    public function getQuonoPeriod() {

        return $QuonoPeriod;
    }

}
?>


