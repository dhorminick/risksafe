  <?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/assessment.php');
include_once('../model/incidents.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="Error saving incident, please try again.";
	$msgClass = "alert-danger";
}

$incident=new incidents();
if ($_REQUEST["action"]=="edit") {
	$edit=true;
	$info=$incident->getIncident($_REQUEST["id"]);
} else {
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
    <h1 class="page-header">
    <?php
	if ($edit) {
		echo 'Edit Incident';
	} else {
		echo 'New Incident';	
	}
	?>
    </h1>
    
  <div class="">
  	
  	<div class="alert <?php if (isset($msgClass)) echo $msgClass;?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
    	<?php if (isset($msg)) echo $msg;?>
  	</div>
  	
    <div class="panel panel-default">
      <div class="panel-body">
        <form role="form" id="form" action="../controller/incident.php" method="post">
      		
           <div class="form-group">
            <label>Incident number</label>
            <!--- Auto Generated no == not id -->
        
          </div>
            
         <div class="form-group">
            <label>Case Title</label>
            <input value="<?php if ($edit) echo $info["in_title"];?>" name="title" type="text" maxlength="255" class="form-control" placeholder="Enter case title" required>        
          </div>
           <div class="form-group">
            <label>Date Occured</label>
            <input value="<?php if ($edit) { echo date("m/d/Y", strtotime($info["in_date"])); } else { echo date("m/d/Y");}?>" name="date" type="text" maxlength="255" class="form-control datepicker" placeholder="Enter incident date occured..." required>
        
          </div>
                   <div class="form-group">
            <label>Reported By</label>
            <input value="<?php if ($edit) echo $info["in_reported"];?>" name="reported" type="text" maxlength="255" class="form-control" placeholder="Enter person who reported the incident..." required>
        
          </div>
             <div class="form-group">
            <label>Team or Department</label>
            <input value="<?php if ($edit) echo $info["in_team"];?>" name="team" type="text" maxlength="255" class="form-control" placeholder="Enter team or department..." required>
          </div>
          <div class="form-group">
            <label>Financial Loss</label>
            <input value="<?php if ($edit) echo $info["in_financial"];?>" name="financial" type="text" maxlength="255" class="form-control" placeholder="" required>
          </div>
          <div class="form-group">
            <label>Injuries</label>
            <input value="<?php if ($edit) echo $info["in_injuries"];?>" name="injuries" type="text" maxlength="255" class="form-control" placeholder="" required>
          </div>
          <div class="form-group">
            <label>Complaints</label>
            <input value="<?php if ($edit) echo $info["in_complaints"];?>" name="complaints" type="text" maxlength="255" class="form-control" placeholder="" required>
          </div>
          <div class="form-group">
            <label>Compliance breach</label>
            <input value="<?php if ($edit) echo $info["in_compliance"];?>" name="compliance" type="text" maxlength="255" class="form-control" placeholder="" required>
          </div>
            <div class="form-group">
            <label>Description</label>
            <textarea rows="3" class="form-control" placeholder="Enter description..." class="form-control" name="descript"><?php if ($edit) echo $info["in_descript"];?></textarea>            
        
          </div>
            <div class="form-group">
            <label>Impact</label>
            <textarea rows="3" class="form-control" placeholder="Enter impact..." class="form-control" name="impact"><?php if ($edit) echo $info["in_impact"];?></textarea>            
        
          </div>
   
                   <div class="form-group">
            <label>Priority</label>
            <select class="form-control" name="priority" >
            	<option value="High" <?php if($edit && $info["in_status"] == "High") echo "selected='selected'"; ?> >High</option>
            	<option value="Medium" <?php if($edit && $info["in_status"] == "Medium") echo "selected='selected'"; ?> >Medium</option>
            	<option value="Low" <?php if($edit && $info["in_status"] == "Low") echo "selected='selected'"; ?> >Low</option>
            </select>
           
          </div>
          <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="status" >
            	<option value="Open" <?php if($edit && $info["in_status"] == "Open") echo "selected='selected'"; ?> >Open</option>
            	<option value="Closed" <?php if($edit && $info["in_status"] == "Closed") echo "selected='selected'"; ?> >Closed</option>
            	<option value="In Progress" <?php if($edit && $info["in_status"] == "In Progress") echo "selected='selected'"; ?> >In Progress</option>
            </select>
          </div>
                   
          <div class="form-group">
          	  <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Incident</button>
	          <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>	         
	          <input name="action" type="hidden" value="<?php echo $_REQUEST["action"];?>"  />
	          <input name="id" type="hidden" value="<?php if ($edit) echo $info["idincident"];?>"  />
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
 	$( ".datepicker" ).datepicker();
 	
 	$("#btn_cancel").click(function(){
 		$(location).attr("href","../view/incidents.php");
 	});
}); 
  </script>
</script>
</body>
</html>