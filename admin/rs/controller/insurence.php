<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/insurence.php");
	
	$insurence = new insurence();
	
	//LIST OF AUDITS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="list") {
	
		$db=new db;
		$conn=$db->connect();
		$num=$db->rowCount($conn, "as_insurance", "is_user", $_SESSION["userid"]);		
		$list=$insurence->listInsurances($_REQUEST["start"],$_REQUEST["length"]);
		
		$fulldata=array();
		$data=array();
			
		$fulldata["draw"]=$_REQUEST["draw"];
		$fulldata["recordsTotal"]=$num;
		$fulldata["recordsFiltered"]=$num;
	
		foreach ($list as $item) {
			
			$response=array();
			$response["nr"]=$item["idinsurance"];
			$response["type"]=$item["is_type"];
			$response["coverage"]=$item["is_coverage"];
			$response["company"]=$item["is_company"];
			$response["date"]=date("m/d/Y", strtotime($item["is_date"]));			
			$response["link"] = '<div style="text-align: center;">			
			<a class="btn btn-xs btn-success" href="insurence.php?action=edit&id=' . $item["idinsurance"] . '"><i class=" glyphicon glyphicon-pencil"></i></a>&nbsp;
			<a class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["idinsurance"] . '\');"><i class=" glyphicon glyphicon-remove"></i></a></div>';
			$data[] = array_values($response);
		}
		
		$fulldata["data"]=$data;		
		echo json_encode($fulldata);	
	}
	
	//DELETE 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="delete") {	
		echo $insurence->deleteInsurance($_REQUEST["id"]);		
	}	
	
	//ADD 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="add") {		
	  if ($au=$insurence->addInsurance($_REQUEST["type"],$_REQUEST["coverage"],$_REQUEST["exclusions"],$_REQUEST["company"],$_REQUEST["date"],$_REQUEST["details"],$_REQUEST["actions"])) {
		  header("Location: ../view/insurences.php?response=success");
	  } else {
		  header("Location: ../view/insurence.php?response=err&action=add");
	  }		
	}
	
	//EDIT AUDIT
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="edit") {
		
	  if ($insurence->editInsurance($_REQUEST["id"],$_REQUEST["type"],$_REQUEST["coverage"],$_REQUEST["exclusions"],$_REQUEST["company"],$_REQUEST["date"],$_REQUEST["details"],$_REQUEST["actions"])) {
		  header("Location: ../view/insurences.php?response=success");
	  } else {
		  header("Location: ../view/insurence.php?response=err&action=edit&id=".$_REQUEST["id"]);
	  }		
	}	
?>