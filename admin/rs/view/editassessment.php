<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/assessment.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	$msg="Assessment updated successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="Error updating risk assessment, please try again.";
}

$assess=new assessment();
$datainfo=$assess->getAssessment($_REQUEST["id"]);


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
    <h1 class="page-header">Edit Risk Assessment</h1>
  <div class="">
  	
  	<div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
            <?php if (isset($msg)) echo $msg;?>
          </div>
  	
    <div class="panel panel-default">
      <div class="panel-body">
        <form role="form" id="form" action="../controller/assessment.php" method="post">
        <h4 class="subtitle">Type of Assessment - <?php echo $assess->getBusinessType($datainfo["as_type"]);?> </h4>
            <h3 class="subtitle">Basic Information</h3>
         <div class="form-group">
            <label>Team or Company</label>
            <input value="<?php echo $datainfo["as_team"];?>" name="team" type="text" maxlength="100" class="form-control" placeholder="Enter company or a team name..." required>
        
          </div>
                  <div class="form-group">
            <label>Task or Process Being Reviewed</label>
            <input value="<?php echo $datainfo["as_task"];?>" name="task" type="text" maxlength="255" class="form-control" placeholder="Enter task being reviewed..." required>
            
          </div>
             <div class="form-group">
            <label>Description of Task or Process</label>
            <textarea name="description" rows="4" class="form-control" placeholder="Enter task description..." required><?php echo $datainfo["as_descript"];?></textarea>
           
          </div>
          <div class="form-group">
            <label>Business/Process Owner</label>
            <input value="<?php echo $datainfo["as_owner"];?>" name="owner" type="text" maxlength="100" class="form-control" placeholder="Enter assessment owner..." required>
       
          </div>
                             <div class="form-group">
            <label>Assessor Name</label>
            <input value="<?php echo $datainfo["as_assessor"];?>" name="assessor" type="text" maxlength="100" class="form-control" placeholder="Enter assessor name..." required>
       
          </div>
                   <div class="form-group">
            <label>Next Assessment</label>
            <input name="date" id="date" type="text" maxlength="100" class="form-control readonly" placeholder="Select date..." required readonly style="cursor:pointer;" value="<?php echo date("m/d/Y", strtotime($datainfo["as_date"]));?>">
           
          </div>
                             <div class="form-group">
            <label>Approval</label>
            <select name="approval" class="form-control" required>
            	<option value="1" <?php if ($datainfo["as_approval"]==1) echo ' selected';?>>In progress</option>
                <option value="2" <?php if ($datainfo["as_approval"]==2) echo ' selected';?>>Approved</option>
                <option value="3" <?php if ($datainfo["as_approval"]==3) echo ' selected';?>>Closed</option>
            </select>
       
          </div>
          <div class="form-group">
          		<button type="submit" class="btn btn-md btn-info" id="btn_save">Save</button>
          		<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>          		
	          <input name="action" type="hidden" value="edit" />
	          <input name="id" type="hidden" value="<?php echo $datainfo["idassessment"];?>" />
            <input name="return" type="hidden" value="<?php echo $_REQUEST["id"];?>" />
          </div>
          
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
 
 	$(function() {
    	$( "#date" ).datepicker();
  	});
  
	$("#btn_cancel").click(function(e) {
 		<?php if (isset($_REQUEST["return"]) and $_REQUEST["return"]=="details") {?>
			$(location).attr("href","assessment.php?id=<?php echo $_REQUEST["id"];?>");
		<?php } else {?>
			$(location).attr("href","assessments.php");
		<?php } ?>   
	});
    
}); 
  </script>
</script>
</body>
</html>