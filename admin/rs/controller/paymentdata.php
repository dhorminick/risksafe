<!-- controller -->
<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/paymentdata.php");
	
	$assess=new paymentdata;

    //LIST OF Payment Data s
	if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "list") {
		$db = new db;
		$conn = $db->connect();
		$num = $db->rowCount($conn, "payments", "user_id", $_SESSION["userid"]);
        $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
        $length = isset($_REQUEST["length"]) ? $_REQUEST["length"] : 10;
	
		$list = $assess->listOfPayment($start ,$length);
	
		$fulldata = array();
		$data = array();
	
		
		$fulldata["recordsTotal"] = $num;
		$fulldata["recordsFiltered"] = $num;
	
		foreach ($list as $item) {
			$response = array();
	
			$response["nr"] = $item["id"];
			$response["name"] = $item["customer_name"];
			$response["customer"] = $item["customer"];
			$response['currency'] = $item['currency'];
				$response["amount"] = $item["amount"];
			$data[] = array_values($response);
           
		}
	
		$fulldata["data"] = $data;
   
	
		echo json_encode($fulldata);
   
	}

	if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "clearhistory") {
	
		$db = new db;
		$conn = $db->connect();
		$currentUser = $_SESSION["userid"];


		$sql = "SELECT role FROM users WHERE iduser = '$currentUser'";

		$resultcheck = mysqli_query($conn, $sql);

		if (mysqli_num_rows($resultcheck) > 0) {
		$row = mysqli_fetch_assoc($resultcheck);
		$currentUserRole = $row['role'];

		}

		if ($currentUserRole == "superadmin") {
		 	$query="UPDATE payments SET 
			admin_clearhistory='1'
			";
		}else{

		 $query="UPDATE payments SET 
				clear_history='1'
				 WHERE user_id=".$_SESSION["userid"]."";
		}
				
		if ($conn->query($query)) {
			$response=true;	
			header("Location: ../view/payments.php");

		}else{
			$response=false;	
		}

	}

    ?>


