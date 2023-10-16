<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/readnotification.php");

  
	
	$assess=new readnotification;

    // $db = new db;
    // $conn = $db->connect();
  
  
	

   // header("Location: ../view/readnotification.php?response=err");


    // 	//LIST OF ASSESSMENT 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "readnotification"){
     
		 $db = new db;
		 $conn = $db->connect();
		 $num = $db->rowCount($conn, "as_notification", "notification_by", $_SESSION["userid"]);		
		$start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
		$length = isset($_REQUEST["length"]) ? intval($_REQUEST["length"]) : 10;
		$list = $assess->listreadnotification( $start,  $length);
	
		$fulldata = array();
		$data = array();

        // $query = "UPDATE as_notification
        // SET read_status = '1'
        // WHERE notification_by = ".$_SESSION["userid"]."";
        // $result11 = $conn->query($query);

		// $query = "UPDATE as_notification
        // SET read_status = '1'
		// WHERE notification_to = '1'";
        // $result11 = $conn->query($query);
	
		//$fulldata["draw"] = $_REQUEST["draw"];
		$fulldata["recordsTotal"] = $num;
		$fulldata["recordsFiltered"] = $num;
	
		foreach ($list as $item) {
          
			$response = array();
	         
			//$response["date"] = $item["date"];
			//$response["task"] = $item["as_task"];
            $response['id'] = $item['id'];
			$userid = $item['notification_by'];
			// $checkstatus = $item['read_status'];
			//$query = "SELECT u_name FROM users WHERE iduser=" . $userid;
			//$result = $conn->query($query);
			//if ($row = $result->fetch_assoc()) {
				//$response["name"] = $row["u_name"];
			//}
			if ($item["read_status"] == 0) {
				
				$response["message"] = '<span style="font-weight: 600;">' . $item["messageinfo"] . '</span>';
			} else {
				$response["message"] = '<span style="font-weight: normal;">' . $item["messageinfo"] . '</span>';
			}
	
			$response["date"] = date("m/d/Y", strtotime($item["created_at"]));
		 $response["link"] = '<div style="text-align: center;">
			 	<a class="btn btn-xs btn-danger" title="Delete" href="javascript:del(\'' . $item["id"] . '\')"><i class="glyphicon glyphicon-remove"></i></a>
				
			 </div>';
			$data[] = array_values($response);
		}
	
		$fulldata["data"] = $data;
	
		echo json_encode($fulldata);
	}

     if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "statusupdate"){
		 $id = $_REQUEST['id'];
        $db = new db;
        $conn = $db->connect();
        $query = "UPDATE as_notification
        SET read_status = '1'
        WHERE id = ".$id."";
        $result = $conn->query($query);
             echo 1;



    }
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="deleteallnotification") {
		
        $assess->deleteallnotification();
         
     }
	

    //DELETE readnotification
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="deletenotification") {
		
        $assess->deletenotification($_REQUEST["id"]);
         
     }


    


		  
	  
		

	

	
	
	
	
	

	
	
	  
	  
	
	
	
	

	

	
	
	



	

?>