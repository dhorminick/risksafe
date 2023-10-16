<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/bia.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="Error saving Bussiness Impact Analysis, please try again.";
}

$bia=new bia();
if ($_REQUEST["action"]=="edit") {
	$edit=true;
	$info=$bia->getBIA($_REQUEST["id"]);
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
		echo 'Edit Bussiness Impact Analysis ';
	} else {
		echo 'New Bussiness Impact Analysis';	
	}
	?>
    </h1>
  <div class="">
  	
  			<div class="alert alert-danger" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
            	<?php if (isset($msg)) echo $msg;?>
          	</div>
          	
    <div class="panel panel-default">
      <div class="panel-body">
        <form role="form" id="form" action="../controller/bia.php" method="post">
        	
      		
            
         <div class="form-group">
            <label>Critical Business Activity</label>
            <input value="<?php if ($edit) echo $info["bia_activity"];?>" name="activity" type="text" maxlength="255" class="form-control" placeholder="Enter critical business activity..." required>
        
          </div>
                   <div class="form-group">
            <label>Description</label>
            	<textarea rows="3" class="form-control" placeholder="Enter description..." class="form-control" name="descript"><?php if ($edit) echo $info["bia_descript"];?></textarea>                    
          </div>
                   <div class="form-group">
            <label>Priority</label>
            <select name="priority" class="form-control">
            	<option value="High" <?php if($edit && $info["bia_priority"] == "High") echo "selected='selected'"; ?> >High</option>
            	<option value="Medium" <?php if($edit && $info["bia_priority"] == "Medium") echo "selected='selected'"; ?> >Medium</option>
            	<option value="Low" <?php if($edit && $info["bia_priority"] == "Low") echo "selected='selected'"; ?> >Low</option>
            </select>
        
          </div>
                   <div class="form-group">
            <label>Impact of Loss </label>
            <select name="impact" class="form-control">
            	<option value="Financial" <?php if($edit && $info["bia_impact"] == "Financial") echo "selected='selected'"; ?> >Financial</option>
            	<option value="Reputational" <?php if($edit && $info["bia_impact"] == "Reputational") echo "selected='selected'"; ?> >Reputational</option>
            	<option value="Compliance" <?php if($edit && $info["bia_impact"] == "Compliance") echo "selected='selected'"; ?> >Compliance</option>
            </select>
        
          </div>
                   <div class="form-group">
            <label>Recovery Time Objective</label>
            <input value="<?php if ($edit) echo $info["bia_time"];?>"  name="time" type="text" maxlength="255" class="form-control" placeholder="e.g. 12 hours" required>
        
          </div>
            <div class="form-group">
            <label>Preventative/Recovery Actions</label>
            <textarea rows="3" class="form-control" placeholder="Enter preventative or recovery actions..." class="form-control" name="action"><?php if ($edit) echo $info["bia_action"];?></textarea>	                    
          </div>
 
                   <div class="form-group">
            <label>Resource Requirements</label>
            <textarea rows="3" class="form-control" placeholder="Enter resource requirements..." class="form-control" name="resource"><?php if ($edit) echo $info["bia_resource"];?></textarea>                
          </div>
                     
            <div class="form-group">
            	<button type="submit" class="btn btn-md btn-info" id="btn_save">Save</button>
	            <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>		         
	          	<input name="action" type="hidden" value="<?php echo $_REQUEST["action"];?>"  />
	        	<input name="id" type="hidden" value="<?php if ($edit) echo $info["idbia"];?>"  />
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
 	$("#btn_cancel").click(function(){
 		$(location).attr("href","../view/bias.php");
 	});
}); 
</script>
</script>
</body>
</html>