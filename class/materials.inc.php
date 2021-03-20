<?php
include_once("dbh.inc.php");
include_once("variables.inc.php");
function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}
class Materials

{
    ## properties

    protected $getPostData;
    public $mid;

    public function __construct() {

            $this->getPostData = [];

    }


    public function material_list(){
        
       $sql = "SELECT * FROM material ORDER BY material asc ";
       $objSQL = new SQL($sql);
       //echo "\$sql = $sql <br>";
       $result = $objSQL->getResultRowArray();
       
       return $result;  
    }

    public function material_list_numrows(){
        
       $sql = "SELECT count(*) FROM material ORDER BY mid ";
       $objSQL = new SQL($sql);
       //echo "\$sql = $sql <br>";
       // $result=  $this->conn->query($sql);
       $result = $objSQL->getRowCount();
       //echo "material_list_numrows have $result record(s).<br>";
       return $result;  
       
    }    
    public function create_material_info($post_data=array()){
        
        $this->getPostData = $post_data;
//        $mid = $post_data['mid'];
//        $this->mid = $mid;
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
       if(isset($post_data['create_material'])){
       $material = trim($post_data['material']);
       $neg = trim($post_data['neg']);
       $material_acc= trim($post_data['material_acc']);
       $materialcode= trim($post_data['materialcode']);
       $shaft= trim($post_data['shaft']);
       $shaftindicator= trim($post_data['shaftindicator']);
       $company= trim($post_data['company']);           
       $ismaterial= trim($post_data['ismaterial']);
       $imagesource= trim($post_data['imagesource']);       
       
       $machiningcode= trim($post_data['machiningcode']);           
       $materialtype= trim($post_data['materialtype']);
       $phhstandard= trim($post_data['phhstandard']); 
       $listing= trim($post_data['listing']);
       $stocklisting= trim($post_data['stocklisting']);           
       $subcategory= trim($post_data['subcategory']); 
       $combine= trim($post_data['combine']);
       
       $sql="INSERT INTO material ("
               . "mid, material, neg, material_acc, materialcode, shaft, shaftindicator, company, "
               . "ismaterial, haveimage, imagesource, machiningcode, materialtype, "
               . "phhstandard, listing, stocklisting, subcategory, combine )"
               . "VALUES ('','$material','$neg', '$material_acc', '$materialcode', '$shaft', '$shaftindicator', "
               . "'$company', '$ismaterial', 'no', '$imagesource', '$machiningcode', "
               . "'$materialtype', '$phhstandard', '$listing', '$stocklisting', "
               . "'$subcategory', '$combine' "
               . ") " ;
        
//        $result=  $this->conn->query($sql);
        $objSQL = new SQL($sql);

        $result = $objSQL->InsertData();        
        
           if($result == 'insert ok!' ){
           
               $_SESSION['message']="Successfully Created Student Info";
               echo "Successfully Created material Info<br>";
               
              header('Location: index.php');
           }else{
               $error = "Fail to Created material Info <br>";
               $_SESSION['message']="Please check this \$sql -> $sql";
               $url = "materialcreatefail.php?err=$error";
            //    redirect($url);
               //header('Location: materialcreatefail.php?err=$error');
           }
          
       unset($post_data['create_material']);
       }
       
        
    }
    
    public function view_material_by_mid($mid){

       if(isset($mid)){

//       $student_id= mysqli_real_escape_string($this->conn,trim($id));
       $mid= trim($mid);
      
       $sql="Select * from material where mid='$mid'";
       //echo "line 181, \$sql = $sql <br>"; 

       $objSQL = new SQL($sql);

       $result = $objSQL->getResultOneRowArray();
       return $result;          
    
       }  
    }
    
    
    public function update_material_info($post_data=array()){
        echo "print_r(\$post_data)";
        print_r($post_data);
        echo "<br>";
        $mid = $post_data['mid'];
        
       if(isset($post_data['update_material'])&& isset($post_data['mid'])){
           
        //       $student_name= mysqli_real_escape_string($this->conn,trim($post_data['student_name']));
        //       $email_address= mysqli_real_escape_string($this->conn,trim($post_data['email_address']));
        //       $gender= mysqli_real_escape_string($this->conn,trim($post_data['gender']));
        //       $contact= mysqli_real_escape_string($this->conn,trim($post_data['contact']));
        //       $country= mysqli_real_escape_string($this->conn,trim($post_data['country']));
        //       $student_id= mysqli_real_escape_string($this->conn,trim($post_data['id']));
       $material = trim($post_data['material']);
       $neg = trim($post_data['neg']);
       $material_acc= trim($post_data['material_acc']);
       $materialcode= trim($post_data['materialcode']);
       $shaft= trim($post_data['shaft']);
       $shaftindicator= trim($post_data['shaftindicator']);
       $company= trim($post_data['company']);           
       $ismaterial= trim($post_data['ismaterial']);
       $imagesource= trim($post_data['imagesource']);       
       
       $machiningcode= trim($post_data['machiningcode']);           
       $materialtype= trim($post_data['materialtype']);
       $phhstandard= trim($post_data['phhstandard']); 
       $listing= trim($post_data['listing']);
       $stocklisting= trim($post_data['stocklisting']);           
       $subcategory= trim($post_data['subcategory']); 
       $combine= trim($post_data['combine']);         
        // co_name, co_no, co_code, address1,address2,address3,"
        // . "country, telephone_sales, fax_sales, handphone_sales, email_sales, attn_sales,"
        // . "telephone_acc, fax_acc, handphone_acc, email_acc, attn_acc, groups,"
        // . "aid_cus, terms, credit_limit, currency, company, status, "
        // . "date_created, remarks, credit_used, one_do_one_inv, cannot_migrate, regular, nobusy"
       
        $sql="UPDATE material SET "
            . " material='$material',neg='$neg',material_acc='$material_acc', materialcode='$materialcode', shaft='$shaft', "
            . " shaftindicator='$shaftindicator', company='$company', ismaterial='$ismaterial', imagesource='$imagesource', "
            . " machiningcode='$machiningcode', materialtype='$materialtype', phhstandard='$phhstandard',  "
            . " listing='$listing',  "
            . " stocklisting='$stocklisting', subcategory='$subcategory', combine='$combine' "
            . " WHERE mid = $mid ";
        
        
        
        $objSQL = new SQL($sql);

        $result = $objSQL->getUpdate();
        
        //    if($result == 'updated'){
        //        $_SESSION['message']="Successfully Updated Student Info";
        //    }

           if($result == 'updated' ){
           
            $_SESSION['message']="Successfully  Update material Info";
            echo "Successfully Created material Info<br>";
            $url = "index.php";
           // redirect($url);
        //    header('Location: index.php');
        }else{
            $error = "Fail to Update material Info <br>";
            $_SESSION['message']="Please check this \$sql -> $sql";
            $url = "index.php";
            //redirect($url);
            //header('Location: materialcreatefail.php?err=$error');
        }           
       unset($post_data['update_material']);

       }   
    }
    
    public function delete_material_info_by_id($mid){

        // $mid = $this->mid;
        echo "\$mid = $mid <br>";
       if(isset($mid)){
//       $student_id= mysqli_real_escape_string($this->conn,trim($mid));
         $mid= trim($mid);

       $sql="DELETE FROM  material  WHERE mid =$mid";
//        $result=  $this->conn->query($sql);
        echo "\$sql= $sql<br>";
        $objSQL = new SQL($sql);

        $result = $objSQL->getDelete() ;      
           if($result == 'deleted'){
               $_SESSION['message']="Successfully Deleted Material Info";
//               echo "Successfully Deleted Material Info";
               echo "$result <br> ";
               ('Location: index.php');
           }else
               {
               echo "$result <br> ";
               ('Location: index.php');
           }
       }
         ('Location: index.php'); 
    }

    function __destruct() {
//    mysqli_close($this->conn);  
    }
    
}

?>

