<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/audit.php");
	
	$audit=new audit();
	
	//LIST OF AUDITS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="list") {
	
		$db=new db;
		$conn=$db->connect();
		$num=$db->rowCount($conn, "as_auditcontrols", "con_user", $_SESSION["userid"]);
		$start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
		$length = isset($_REQUEST["length"]) ? intval($_REQUEST["length"]) : 10;
	
		$list=$audit->listAudits($start,$length);
		
		$fulldata=array();
		$data=array();
			
		$fulldata["draw"]=$_REQUEST["draw"];
		$fulldata["recordsTotal"]=$num;
		$fulldata["recordsFiltered"]=$num;
		
			foreach ($list as $item) {
				
				$next=$audit->getNext($item["con_date"],$item["con_frequency"]);
				$effect ='';
				//effect
				switch ($item["con_effect"]) {
					case 0:
						$effect="Not selected";
						break;
					case 1:
						$effect="Not effective";
						break;
					case 2:
						$effect="Effective";
						break;
				}
				
				$response=array();
				$response["nr"]=$item["idcontrol"];
				$response["control"]=$item["con_control"];
				$response["date"]=date("m/d/Y", strtotime($item["con_date"]));
				$response["next"]=$next;
				$response["effect"]=$effect;
				$response["link"] = '<div style="text-align: center;">
				<a class="btn btn-info btn-xs" href="auditcriteria.php?id=' . $item["idcontrol"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
				<a class="btn btn-success btn-xs" href="audit.php?action=edit&return=audits&id=' . $item["idcontrol"] . '"><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-danger btn-xs" href="javascript:del(\'' . $item["idcontrol"] . '\');"><i class=" glyphicon glyphicon-remove"></i></a>
				<a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="audits.php?action=downloadxls&id=' . $item["idcontrol"] . '"><i class=" glyphicon glyphicon-download"></i></a></div>';
				$data[] = array_values($response);
			}
			
		$fulldata["data"]=$data;
		
		echo json_encode($fulldata);
		exit();
	
	}
	// List of sub control

	if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "getSubInfo") {
		if (isset($_POST["controlId"])) {
			$controlId = $_POST["controlId"];
	
			$result = $audit->getSubNames($controlId);
			echo $result;
		}
	}
	
	
  if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "listsubcontrol") {
	$controlId = $_GET['controlId'];
  
	// Prepare the query
	$query = "SELECT idsubcontrol, sub_name FROM as_subcontrol WHERE audit_id = ?";
	$stmt = mysqli_prepare($connection, $query);
  
	if ($stmt) {
	  // Bind the parameter
	  mysqli_stmt_bind_param($stmt, "i", $controlId);
  
	  // Execute the statement
	  if (mysqli_stmt_execute($stmt)) {
		$subControls = array();
  
		// Bind the result
		mysqli_stmt_bind_result($stmt, $subControlId, $subControlName);
  
		// Fetch sub-controls from the result set
		while (mysqli_stmt_fetch($stmt)) {
		  $subControls[] = array('idsubcontrol' => $subControlId, 'sub_name' => $subControlName);
		}
  
		// Return sub-controls as JSON
		echo json_encode($subControls);
	  } else {
		echo "Failed to execute query: " . mysqli_stmt_error($stmt);
	  }
  
	  // Close the statement
	  mysqli_stmt_close($stmt);
	} else {
	  echo "Failed to prepare query: " . mysqli_error($connection);
	}
  }
	//LIST OF CRITERIA
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="listcriteria") {
	
		$db=new db;
		$conn=$db->connect();
		$num=$db->rowCount($conn, "as_auditcriteria", "cri_control", $_REQUEST["id"]);
		
		$list=$audit->listCriteria($_REQUEST["id"],$_REQUEST["start"],$_REQUEST["length"]);
		
		$fulldata=array();
		$data=array();
			
		$fulldata["draw"]=$_REQUEST["draw"];
		$fulldata["recordsTotal"]=$num;
		$fulldata["recordsFiltered"]=$num;
	
		foreach ($list as $item) {
			$response=array();
			$response["question"]=$item["cri_question"];
			$response["expected"]=$item["cri_expected"];
			switch ($item["cri_outcome"]) {
				case 0:
					$response["ouctome"]="N/A";
					break;
				case 1:
					$response["ouctome"]="Pass";
					break;
				case 2:
					$response["ouctome"]="Fail";
					break;
			}
			
			
			$response["link"] = '<div style="text-align: center;"><a href="auditcriteriafrm.php?action=editcriteria&id=' . $item["idcriteria"] ."&control=" . $_REQUEST["id"] . '">Edit</a> | <a href="javascript:del(\'' . $item["idcriteria"] . '\');">Delete</a></div>';
			$data[] = array_values($response);
		}
		
		$fulldata["data"]=$data;
		
		echo json_encode($fulldata);
	
	}
	
	
	//DELETE AUDIT
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="delete") {
		
		echo $audit->deleteAudit($_REQUEST["id"]);
		
	}
	
	
	//DELETE CRITERIA
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="deletecriteria") {
		
		echo $audit->deleteCriteria($_REQUEST["id"]);
		
	}
	
	//ADD AUDIT
	
	if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add") {
		$next=$audit->getNext($_REQUEST["date"],$_REQUEST["freq"]);
		if ($au = $audit->addAudit(
			
			$_REQUEST["company"],
			$_REQUEST["industry"],
			$_REQUEST["team"],
			$_REQUEST["task"],
			$_REQUEST["assessor"],
			
			$_REQUEST["site"],
			$_REQUEST["date"],
			$_REQUEST["time"],
			$_REQUEST["street"],
			$_REQUEST["building"],
			$_REQUEST["zipcode"],
			$_REQUEST["state"],
			$_REQUEST["country"],
			isset($_REQUEST["control"]) ? $_REQUEST["control"] : "",
			$_REQUEST["existing"],
			$_REQUEST["audi_treatment"],
			$_REQUEST["Effectivness"],
			$_REQUEST["freq"],
			isset($_REQUEST["subControl"]) ? $_REQUEST["subControl"] : "",
			$next,
			
		)) {
			$message="Audit has been created successfully";
			
			if ($audit->savenotification($_SESSION["userid"],$message,0,0,$au)) {
			header("Location: ../view/auditcriteria.php?id=" . $au);
		 }} else {
			header("Location: ../view/audit.php?response=err&action=add");
		}
	
	}
	
	//EDIT AUDIT
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="edit") {
 
              $control = '';
                
			 
		      if( $_REQUEST["existing"]){
				$control = $_REQUEST['existing'];
			  }else{
				$control = $_REQUEST['control'];
			  }
			   
	  if ($audit->editAudit($_REQUEST["id"],$_REQUEST["company"],$_REQUEST["industry"],$_REQUEST["team"],$_REQUEST["task"],$_REQUEST["assessor"],$_REQUEST["site"],$_REQUEST["date"],$_REQUEST["time"],$_REQUEST["street"],$_REQUEST["building"],$_REQUEST["zipcode"],$_REQUEST["state"],$_REQUEST["country"],$control,$_REQUEST["audi_treatment"])) {
		  if ($_REQUEST["return"]=="auditcriteria") {
			
			  header("Location: ../view/auditcriteria.php?id=".$_REQUEST["id"]);
		  } else {
			  header("Location: ../view/audits.php");
		  }
	  } else {
		  header("Location: ../view/audit.php?response=err&action=edit");
	  }
		
	}
	
	//UPDATE CONTROL EFFECTIVNESS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="updateeffect") {
		
	  if ($audit->updateEffect($_REQUEST["id"],$_REQUEST["effect"],$_REQUEST["observation"],$_REQUEST["rootcause"],$_REQUEST["treatment"],$_REQUEST["frequency"])) {
		  header("Location: ../view/auditcriteria.php?response=true&id=".$_REQUEST["id"]);
	  } else {
		  header("Location: ../view/auditcriteria.php?response=err&id=".$_REQUEST["id"]);
	  }
		
	}
	
	
	//ADD CRITERIA
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="addcriteria") {
		
		if ($audit->addCriteria($_REQUEST["control"],$_REQUEST["question"],$_REQUEST["procedure"],$_REQUEST["expected"],$_REQUEST["outcome"],$_REQUEST["notes"])) {
			header("Location: ../view/auditcriteria.php?id=".$_REQUEST["control"]); //."&audit=" . $_REQUEST["audit"]
		} else {
			header("Location: ../view/auditcriteriafrm.php?response=err&action=addcriteria");
		}
		
	}
	
	//EDIT CRITERIA
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="editcriteria") {
		
		if ($audit->editCriteria($_REQUEST["id"],$_REQUEST["control"],$_REQUEST["question"],$_REQUEST["procedure"],$_REQUEST["expected"],$_REQUEST["outcome"],$_REQUEST["notes"])) {
			header("Location: ../view/auditcriteria.php?id=".$_REQUEST["control"]);
		} else {
			header("Location: ../view/auditcriteriafrm.php?response=err&action=editcriteria&control=".$_REQUEST["control"]."&id=".$_REQUEST["id"]);
		}
		
	}
	
	//GET INFO ON CONTROL
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="getauditinfo") {
		
		echo $audit->cronjobfunctionaudit();
	}
