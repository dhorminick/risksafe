<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/bia.php");
	
	$bia=new bia();
	
	//LIST 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="list") {
	
		$db=new db;
		$conn=$db->connect();
		$num=$db->rowCount($conn, "as_bia", "bia_user", $_SESSION["userid"]);
		
		$list=$bia->listBIA($_REQUEST["start"],$_REQUEST["length"]);
		
		$fulldata=array();
		$data=array();
			
		$fulldata["draw"]=$_REQUEST["draw"];
		$fulldata["recordsTotal"]=$num;
		$fulldata["recordsFiltered"]=$num;
	
		foreach ($list as $item) {
			
			$response=array();
			$response["nr"]=$item["idbia"];
			$response["activity"]=$item["bia_activity"];
			$response["priority"]=$item["bia_priority"];
			$response["impact"]=$item["bia_impact"];
			$response["time"]=$item["bia_time"];						
			$response["link"] = '<div style="text-align: center;">
						<a class="btn btn-xs btn-success" href="bia.php?action=edit&id=' . $item["idbia"] . '"><i class=" glyphicon glyphicon-pencil"></i></a>&nbsp;
			<a class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["idbia"] . '\');"><i class=" glyphicon glyphicon-remove"></i></a></div>';
			$data[] = array_values($response);
		}
		
		$fulldata["data"]=$data;		
		echo json_encode($fulldata);	
	}
			
	//DELETE 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="delete") {	
		echo $bia->deleteBIA($_REQUEST["id"]);		
	}

	//ADD 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="add") {
		
	  if ($au=$bia->addBIA($_REQUEST["activity"],$_REQUEST["descript"],$_REQUEST["priority"],$_REQUEST["impact"],$_REQUEST["time"],$_REQUEST["action"],$_REQUEST["resource"])) {
		  header("Location: ../view/bias.php?response=success");
	  } else {
		  header("Location: ../view/bia.php?response=err&action=add");
	  }		
	}
	
	//EDIT 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="edit") {
		
	  if ($bia->editBIA($_REQUEST["id"],$_REQUEST["activity"],$_REQUEST["descript"],$_REQUEST["priority"],$_REQUEST["impact"],$_REQUEST["time"],$_REQUEST["action"],$_REQUEST["resource"])) {
		  header("Location: ../view/bias.php?response=success");
	  } else {
		  header("Location: ../view/bia.php?response=err&action=edit&id=".$_REQUEST['id']);
	  }	
	}
	
?>