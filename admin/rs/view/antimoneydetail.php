<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/antimoney.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	if ($_REQUEST["action"]=="editdetail") $msg="Aml updated sucessfully.";
	if ($_REQUEST["action"]=="adddetail") $msg="Aml added sucessfully. You may continue with your Aml.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="An error occured, please try again.";
}

$assess=new antimoney();
$assess->deleteTmpFields();

$assessment = $assess->getAssessment($_REQUEST["assessmentId"]);
if ($_REQUEST["action"]=="editdetail") {
	$edit=true;
	$id=$_REQUEST["id"];	
	$data=$assess->getAssessmentDet($_REQUEST["id"]);
    //print_r($data);
} else {
	$id=-1;
	$edit=false;	
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once("header.php");?>
</head>
<body>
<!-- header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE;?></a> </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
          <ul id="g-account-menu" class="dropdown-menu" role="menu">
			<?php include_once("menu_top.php");?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /container --> 
</div>
<!-- /Header --> 

<!-- Main -->
<div class="container-fluid">
<div class="row">
  <div class="col-lg-3 col-sm-12"> 
    <!-- Left column --> 
	<?php include_once("menu.php");?>
  <!-- /col-3 -->
  </div>
  <div class="col-lg-9 col-md-12">
    <h1 class="page-header">AML Details</h1>
  <div class="col-lg-9 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <form role="form" id="form" action="../controller/antimoney.php">
      <div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
            <?php if (isset($msg)) echo $msg;?>
          </div>
                    <div class="form-group">
          <button type="button" class="btn btn-md btn-info pull-right" id="btn_viewdet">Switch to AML Details</button>
          </div>
          <h3 class="subtitle">Risk Identification</h3>
          <div class="form-group">
            <label>Risk</label>
           <div id="risk_div"></div>
          
          </div>
         
         <div class="form-group">
            <label>Risk Sub Category</label>
           <div id="hazard_div"></div>
          
          </div>
         <div class="form-group">
            <label>Risk Description</label>
            <textarea name="descript" rows="4" class="form-control" placeholder="Enter risk description..." required><?php if ($edit) echo $data["aml_descript"];?></textarea>

          </div>
          <h3 class="subtitle">Risk Evaluation<a href="#" data-tooltip="Select likelyhood of the risk and risk consequence. Risk rating will be calculated automatically."><img src="../img/help_ico.gif"/ class="help-ico"></a></h3>
                  <div class="form-group">
                   <label>Likelihood</label>
			<?php 
				if ($edit) {
					echo $assess->listLikelihood($data["as_like"]);
				} else {
					echo $assess->listLikelihood(-1);
				}
			
			?>
          </div>
          <div class="form-group">
            <label>Consequence</label>
			<?php 
				if ($edit) {
					echo $assess->listConsequence($data["as_consequence"]);
				} else {
					echo $assess->listConsequence(-1);
				}
			?>
          </div>
          
          <div class="form-group">
            <label>Risk Rating</label>
			<div id="rating"></div>
          </div>
          
          <h3 class="subtitle">Control actions</h3>
    <div class="form-group">
            <label>Controls in place<a href="#" data-tooltip="You add controls to your risk by selecting existing control from your controls library or you can create your own custom control by typing it into the text field below and clicking on '+Add' button."><img src="../img/help_ico.gif"/ class="help-ico"></a></label>
            <select name="existing_ct" id="existing_ct" class="form-control" required>
            <option value="-1" selected>Select and add an existing control</option>
            <?php 
			  echo $assess->listControl($_SESSION["userid"]);
			?>
            </select>
          
          </div>
<div class="form-group">
                    <input style="width:84%;float:left;" name="control" id="control" type="text" maxlength="255" class="form-control" placeholder="Enter custom control description...">
           <button style="width:15%;float:right;margin-top:0px;" type="button" class="btn btn-sm btn-info" id="btn_addcontrol">+ Add</button>
          </div>
          <div class="clearfix" id="controls">
          &nbsp;
          </div>
            <div class="form-group">
                   <label>Control Effectiveness</label>
            <textarea name="effectiveness" rows="4" class="form-control" placeholder="Enter control effectiveness..." required><?php if ($edit) echo $data["as_effect"];?></textarea>

          </div>
            <div class="form-group">
            <label>Action Type</label>
			<?php 
				if ($edit) {
					echo $assess->listActions($data["as_action"]);
				} else {
					echo $assess->listActions(-1);
				}
			
			?>
            
          </div>
          <h3 class="subtitle">Treatment Plans</h3>
              <div class="form-group">
            		<label>Treatments<a href="#" data-tooltip="You add treatments to your risk by selecting existing treatment from your treatments library or you can create your own custom treatment by typing it into the text field below and clicking on '+Add' button."><img src="../img/help_ico.gif"/ class="help-ico"></a></label>
            		<select name="existing_tr" id="existing_tr" class="form-control" required>
            		<option value="-1" selected>Select and add an existing treatment</option>
            <?php 
			  echo $assess->listTreatmentsLib($_SESSION["userid"]);
			?>
            </select>
          
          </div>
          <div class="form-group">
            
            <input style="width:84%;float:left;" name="treatment" id="treatment" type="text" maxlength="255" class="form-control" placeholder="Enter custom treatment description...">
           <button style="width:15%;float:right;margin-top:0px;" type="button" class="btn btn-sm btn-info" id="btn_addtreatment">+ Add</button>
          </div>
          <div class="clearfix" id="treatments">
          &nbsp;
          </div>
    <div class="form-group">
            <label>Due Date</label>
            <input name="date" id="date" type="text" maxlength="100" class="form-control readonly" placeholder="Select date..." required readonly style="cursor:pointer;" value="<?php if ($edit) { echo date("m/d/Y", strtotime($data["as_duedate"])); } else { echo date("m/d/Y"); }?>">
          </div>
<div class="form-group">
            <label>Action Owner</label>
            <input name="owner" id="owner" type="text" maxlength="100" class="form-control" placeholder="Enter action owner..." required value="<?php if ($edit) echo $data["as_owner"];?>">
           
          </div>
          <div class="form-group">
          		<button type="submit" class="btn btn-md btn-info" id="btn_save">Save Risk</button>
          		<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
          
          		<input name="action" type="hidden" value="<?php echo $_REQUEST["action"];?>" />
          		<input name="id" type="hidden" value="<?php if ($edit) echo $id;?>" />
          		<input name="assessmentId" type="hidden" value="<?php echo $assessment['id'];?>" />
            

        </form>
      </div>
    </div>
  </div>
  </div>
  <!--/col-span-9--> 
</div>  
</div>

<!-- /Main -->

<?php include_once("footer.php");?>
<script>
$(document).ready(function(e) {
 
 <?php
 	//picks up data from the db and asigns values to JS variables
 	if ($edit) {
		echo 'detid=' . $_REQUEST["id"] . ';';
		echo 'selrisk=' . $data["aml_cat"] . ';';
		echo 'selhazard=' . $data["aml_subcat"] . ';';
		echo 'cathazard=' . $data["aml_cat"] . ';';
		echo 'riskType=' . $assessment["as_type"] . ';';
		
	} else {
		echo 'detid=-1;';
		echo 'selrisk=-1;';
		echo 'selhazard=-1;';
		echo 'cathazard=-1;';
		echo 'riskType=' . $assessment["as_type"] . ';';	
	}
	
 ?>
 
 $(function() {
    $( "#date" ).datepicker();
  });
  
  $("#btn_cancel").click(function(e) {
 	$(location).attr("href","antimoney.php?id=<?php echo $assessment['id'];?>");   
  });
  
  
  $("#risk_div").load('../controller/antimoney.php?action=listrisks&type='+riskType+'&selected='+selrisk);
  $("#hazard_div").load('../controller/antimoney.php?action=listhazards&cat='+cathazard+'&selected='+selhazard);
  $("#treatments").load('../controller/antimoney.php?action=listtreat&id='+detid);
  $("#controls").load('../controller/antimoney.php?action=listcontrols&id='+detid);
  $("#rating").load('../controller/antimoney.php?action=rating&likelihood='+$("#likelihood").val()+'&consequence='+$("#consequence").val());
  
  $("#risk_div").change(function(e) {
  
  	  $("#hazard_div").load('../controller/antimoney.php?action=listhazards&cat='+ $("#risk").val()+'&selected='+selhazard);

  });
  
  $("#consequence, #likelihood").change(function(e) {
  
  	  	$("#rating").load('../controller/antimoney.php?action=rating&likelihood='+$("#likelihood").val()+'&consequence='+$("#consequence").val());

  });
  
  $("#btn_addtreatment").click(function(e) {
  	if ($("#treatment").val()!=='') {
		$.ajax({type: "GET", url: "../controller/antimoney.php?action=addtreat&descript="+$("#treatment").val()+'&id='+detid, async: false})
		$("#treatments").load('../controller/antimoney.php?action=listtreat&id='+detid);
		$("#treatment").val("");	
	}
  });
  
  $("#btn_addcontrol").click(function(e) {
  	if ($("#control").val()!=='') {
		$.ajax({type: "GET", url: "../controller/antimoney.php?action=addcontrol&descript="+$("#control").val()+'&id='+detid, async: false})
		$("#controls").load('../controller/antimoney.php?action=listcontrols&id='+detid);
		$("#control").val("");	
	}
  });
  
   $("#existing_ct").change(function(e) {
  	if ($("#exisitng_ct").val()!=='-1') {
		$.ajax({type: "GET", url: "../controller/antimoney.php?action=addlibcontrol&id="+$("#existing_ct").val()+'&det='+detid, async: false})
		$("#controls").load('../controller/antimoney.php?action=listcontrols&id='+detid);
		$("#control").val("");
		$("#existing_ct").val("-1");	
	}
  });
  
   $("#existing_tr").change(function(e) {
  	if ($("#exisitng_tr").val()!=='-1') {
		$.ajax({type: "GET", url: "../controller/antimoney.php?action=addlibtreat&id="+$("#existing_tr").val()+'&det='+detid, async: false})
		$("#treatments").load('../controller/antimoney.php?action=listtreat&id='+detid);
		$("#treatment").val("");
		$("#existing_tr").val("-1");	
	}
  });
  
  $("#btn_viewdet").click(function(e) {
	  $(location).attr("href","antimoney.php?id=<?php echo $assessment['id'];?>");
  });

    
}); 

function del(what, id) {
	
	if (what=="treatment") {
		$.ajax({type: "GET", url: "../controller/antimoney.php?action=deletetreat&id="+id, async: false})
		$("#treatments").load('../controller/antimoney.php?action=listtreat&id='+detid);
	}
	
	if (what=="control") {
		$.ajax({type: "GET", url: "../controller/antimoney.php?action=deletecontrol&id="+id, async: false})
		$("#controls").load('../controller/antimoney.php?action=listcontrols&id='+detid);
	}
	
}


</script>

</body>
</html>