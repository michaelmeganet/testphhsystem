<?php
include_once "dbh.inc.php";				//calls db connection function
include_once "variables.inc.php";		//calls query qctivity function

Class Customer{
	#begin initialize properties
	protected $getPostData;	//array that will contain $_POST datas.
	protected $tbl_name;
	public $cid;			//customer id
	#end initialization


	function __construct($postdata=array()){ //--> Put tasks that will be done first here
		$this->getPostData = $postdata;  //initialize empty array
		$this->tbl_name = 'customer_ptphh'; //set the table name
	}

	function create(){		//--> create a new customer data
		$postdata = $this->getPostData;	//insert $_POST data to local var
		$tbl_name = $this->tbl_name;    //insert table name to local var
		#print_r($postdata);
		#echo "<br>";
		$qr2 = "INSERT INTO ".$tbl_name." SET ";

		#------------------------------------------------
		# Try loop until all $postdatavalues are inserted
		//
		foreach ($postdata as $key => $value) {
			#
			if ($key != 'submit') {
				${$key} = trim($value);
				$columnHeader = $key;	// creates new variable based on $key values
				echo $columnHeader." = ".$$columnHeader."<br>";
				$qr2 .= $columnHeader."= '{$$columnHeader}'";
				
				if (isset($postdata['submit'])) {
				$qr2 .= " , ";
				}
			}else{
				unset($postdata['submit']);
			}

			# If submit value is there, then add comma
			
			

		}
		echo "<br><br><br>".$qr2."<br>";
		# End loop 

		# Try loop until query is created




		#begin trimming data from array into variables.
		$accno = trim($postdata['accno']); 		#customer id
		$co_name = trim($postdata['co_name']); 	#customer/company name
		$co_no = trim($postdata['co_no']);		#customer Tax number
		$co_code = trim($postdata['co_code']);	#customer Initials
		$address1 = trim($postdata['address1']); 		#address1st  line
		$address2 = trim($postdata['address2']);		#address 2nd Line
		$address3 = trim($postdata['address3']);		#address 3rd line
		$country = trim($postdata['country']);	#company country
		$telephone_sales = trim($postdata['telephone_sales']);	#sales phone number
		$fax_sales = trim($postdata['fax_sales']);	#sales fax number
		$handphone_sales = trim($postdata['handphone_sales']);	#sales handphone number
		$email_sales = trim($postdata['email_sales']);	#sales email
		$attn_sales = trim($postdata['attn_sales']);	#sales name
		$telephone_acc = trim($postdata['telephone_acc']);	#accounting phone number
		$fax_acc = trim($postdata['fax_acc']);		#accounting fax number
		$handphone_acc = trim($postdata['handphone_acc']);		#accounting handphone number
		$email_acc = trim($postdata['email_acc']);	#accounting email
		$attn_acc = trim($postdata['attn_acc']);		#accounting name
		$groups = trim($postdata['groups']);		#grouping code
		$aid_cus = trim($postdata['aid_cus']);	#marketing code
		$terms = trim($postdata['terms']);		#payment terms
		$currency = trim($postdata['currency']);	#payment currency
		$credit_limit = trim($postdata['credit_limit']);#payment credit limits
		$company = trim($postdata['company']);	#company branch
		$status = trim($postdata['status']);		#customer status (active, hold, disabled)
		$date_created = trim($postdata['date_created']);
		$remarks = trim($postdata['remarks']);	#remarksx
		#done trimming data

		#create query to insert data to db.
		$qr = "INSERT INTO customer_ptphh 
			   SET
			   accno ='{$accno}',
			   co_name ='{$co_name}', 
			    co_no='{$co_no}', 
			    co_code='{$co_code}', 
			    address1='{$address1}', 
			    address2='{$address2}', 
			    address3='{$address3}', 
			    country='{$country}', 
			    telephone_sales='{$telephone_sales}', 
			    fax_sales='{$fax_sales}', 
			    handphone_sales='{$handphone_sales}', 
			    email_sales='{$email_sales}', 
			    attn_sales='{$attn_sales}', 
			    telephone_acc='{$telephone_acc}', 
			    fax_acc='{$fax_acc}', 
			    handphone_acc='{$handphone_acc}', 
			    email_acc='{$email_acc}', 
			    attn_acc='{$attn_acc}', 
			    groups='{$groups}', 
			    aid_cus='{$aid_cus}', 
			    terms='{$terms}', 
			    currency='{$currency}', 
			    credit_limit='{$credit_limit}', 
			    company='{$company}', 
			    status='{$status}',
			    date_created='{$date_created}',
			    remarks='{$remarks}'";
		#echo $qr;
		# do the insert activity
		$dbInsert = new SQL($qr);
		if ($dbInsert->InsertData()=="insert ok!"){
			$insResult = "<div class='alert alert-success'>Insert Successful.<br>";
		}else{
			$insResult = "<div class='alert alert-danger'>Failed to Insert Data!<br>";
		}
		return $insResult;		//TRUE or FALSE
	}

	function update($cid){		//--> Updates customer data, must contains CID value
		$postdata = $this->getPostData;	//insert $_POST data to properties
		$this->cid = $cid;


		#begin trimming data from array into variables.
		$accno = trim($postdata['accno']); 		#customer id
		$co_name = trim($postdata['co_name']); 	#customer/company name
		$co_no = trim($postdata['co_no']);		#customer Tax number
		$co_code = trim($postdata['co_code']);	#customer Initials
		$address1 = trim($postdata['address1']); 		#address1st  line
		$address2 = trim($postdata['address2']);		#address 2nd Line
		$address3 = trim($postdata['address3']);		#address 3rd line
		$country = trim($postdata['country']);	#company country
		$telephone_sales = trim($postdata['telephone_sales']);	#sales phone number
		$fax_sales = trim($postdata['fax_sales']);	#sales fax number
		$handphone_sales = trim($postdata['handphone_sales']);	#sales handphone number
		$email_sales = trim($postdata['email_sales']);	#sales email
		$attn_sales = trim($postdata['attn_sales']);	#sales name
		$telephone_acc = trim($postdata['telephone_acc']);	#accounting phone number
		$fax_acc = trim($postdata['fax_acc']);		#accounting fax number
		$handphone_acc = trim($postdata['handphone_acc']);		#accounting handphone number
		$email_acc = trim($postdata['email_acc']);	#accounting email
		$attn_acc = trim($postdata['attn_acc']);		#accounting name
		$groups = trim($postdata['groups']);		#grouping code
		$aid_cus = trim($postdata['aid_cus']);	#marketing code
		$terms = trim($postdata['terms']);		#payment terms
		$currency = trim($postdata['currency']);	#payment currency
		$credit_limit = trim($postdata['credit_limit']);#payment credit limits
		$company = trim($postdata['company']);	#company branch
		$status = trim($postdata['status']);		#customer status (active, hold, disabled)
		$remarks = trim($postdata['remarks']);	#remarksx
		#done trimming data

		#create query to insert data to db.
		$qr = "UPDATE customer_ptphh 
			   SET
			   accno ='{$accno}',
			   co_name ='{$co_name}', 
			    co_no='{$co_no}', 
			    co_code='{$co_code}', 
			    address1='{$address1}', 
			    address2='{$address2}', 
			    address3='{$address3}', 
			    country='{$country}', 
			    telephone_sales='{$telephone_sales}', 
			    fax_sales='{$fax_sales}', 
			    handphone_sales='{$handphone_sales}', 
			    email_sales='{$email_sales}', 
			    attn_sales='{$attn_sales}', 
			    telephone_acc='{$telephone_acc}', 
			    fax_acc='{$fax_acc}', 
			    handphone_acc='{$handphone_acc}', 
			    email_acc='{$email_acc}', 
			    attn_acc='{$attn_acc}', 
			    groups='{$groups}', 
			    aid_cus={$aid_cus}, 
			    terms='{$terms}', 
			    currency={$currency}, 
			    credit_limit={$credit_limit}, 
			    company='{$company}', 
			    status='{$status}',
			    remarks='{$remarks}'
			    WHERE cid={$this->cid}";

		# do the insert activity
		$dbUpdate = new SQL($qr);
		if ($dbUpdate->getUpdate()=="updated"){
			$updResult = "<div class='alert alert-success'>Update Successful.<br>";
		}else{
			$updResult = "<div class='alert alert-danger'>Failed to Update!<br>";
		}
		return $updResult;		//TRUE or FALSE	
	}

	function summonAll(){	//--> Fetches all rows from table Customer
		#settle query
		$qr = "SELECT * FROM customer_ptphh ORDER BY accno ASC";

		#execute query
		$dbSummon = new SQL($qr);
		$result = $dbSummon->getResultRowArray();
		return $result;
	}

	function summonLimit($offset, $limit){ //--> Fetches rows bound by offset and limit, used for Pagination, 
		#settle query
		$qr = "SELECT * FROM customer_ptphh ORDER BY accno ASC LIMIT {$offset},{$limit}";
		#echo $qr;
		#execute query
		$dbSummonLimit = new SQL($qr);
		$result = $dbSummonLimit->getResultRowArray();
		return $result;
	}

	function summonOne($cid){	//--> Fetches single row from table Customer
		#settle query
		$qr = "SELECT * FROM customer_ptphh WHERE cid={$cid} LIMIT 0,1";

		#execute query
		$dbsummonOne = new SQL($qr);
		$result = $dbsummonOne->getResultOneRowArray();
		return $result;

	}

	function countAll(){	//--> Counts the number of rows exists in Customer Table
		#settle query
		$qr = "SELECT count(*) FROM customer_ptphh";

		#execute query
		$dbCountRow = new SQL($qr);
		$result = $dbCountRow->getRowCount();
		return $result;

	}

	function byebye($cid){		//--> Deletes a row from Customer Table, must contain CID
		#settle query
		$qr = "DELETE FROM customer_ptphh WHERE cid ={$cid}";

		#execute query
		$dbBye = new SQL ($qr);
		if ($dbBye->getDelete()=="delete ok!") {
			$byeResult = "<div class='alert alert-success'>Delete Successful.<br>";
		}else{
			$byeResult = "<div class='alert alert-danger'>Failed to Delete Data!<br>";
		}
		return $byeResult;
	}

	function createParam(){     //--> Creates a new customer data, using bindParam Method.
		#settle query
		$postdata = $this->getPostData;
		$qr = "INSERT INTO customer_ptphh
			   SET
			   accno=:accno,   
			   co_name=:co_name,
			   co_no=:co_no,
			   co_code=:co_code,
			   address1=:address1,
			   address2=:address2,
			   address3=:address3,
			   country=:country,
			   telephone_sales=:telephone_sales,
			   fax_sales=:fax_sales,
			   handphone_sales=:handphone_sales,
			   email_sales=:email_sales,
			   attn_sales=:attn_sales,
			   telephone_acc=:telephone_acc,
			   fax_acc=:fax_acc,
			   handphone_acc=:handphone_acc,
			   email_acc=:email_acc,
			   attn_acc=:attn_acc,
			   groups=:groups,
			   aid_cus=:aid_cus,
			   terms=:terms,
			   currency=:currency,
			   credit_limit=:credit_limit,
			   company=:company,
			   date_created=:tdate,
			   status=:status,
			   remarks=:remarks";
		$dbInsert2 = new SQLBindParam($qr,$postdata); //--> calls a different class to bound above Parameters

		if($dbInsert2->InsertData2()=="insert ok!"){
			$result = "<div class='alert alert-success'>Insert Successful<br>";
		}else{
			$result = "<div class='alert alert-danger'>Failed to Insert Data<br>";

		}
		return $result;
	}
}




?>