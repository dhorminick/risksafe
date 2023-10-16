<?php

include_once("../config.php");
include_once("db.php");

class paymentdata {

	
	public function __construct(){	
	}

    public function listOfPayment($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
        $currentUser = $_SESSION["userid"];

        $sql = "SELECT role FROM users WHERE iduser = '$currentUser'";

        $resultcheck = mysqli_query($conn, $sql);

        if (mysqli_num_rows($resultcheck) > 0) {
            $row = mysqli_fetch_assoc($resultcheck);
            $currentUserRole = $row['role'];
		
            if ($currentUserRole == "superadmin") {
                $query = "SELECT * FROM payments WHERE admin_clearhistory='0' ORDER BY id DESC";
				
            }else{
                $query = "SELECT * FROM payments WHERE user_id=" . $_SESSION["userid"] . " AND clear_history='0' ORDER BY id DESC LIMIT " . intval($start) . ", " . intval(1);
            }
    
		if ($result=$conn->query($query)) {	
			$data=array();
			while ($row=$result->fetch_assoc()) {
				$data[]=$row;
                // print_r($data);
			}
			
			$response=$data;
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}

}
}