<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/incidents.php');

//$msg = null;
if(isset($_REQUEST['response']) && $_REQUEST['response'] == "success"){
	$msg = "Incident saved successfully.";	
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
    	<h1 class="page-header">Incidents</h1>
    
    	<?php if (isset($msg)) { ?>
    		<div class="alert alert-success" id="notify" >
        		<i class=" glyphicon glyphicon-ok-sign"></i> <?php if (isset($msg)) echo $msg;?>
      		</div>
      	<?php } ?>
    
		  <div class="">
		    <div class="panel panel-default">
		      <div class="panel-body">      		
		       		<div class="clearfix mb20">
		          		<button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ New incident</button>
		          	</div>
		          	
		         <table class="table table-striped table-bordered table-hover" id="table" style="width:100%;">
		          <thead>
		            <tr>
		              <th>#</th>
		              <th>Title</th>
		              <th>Team or Department</th>
		              <th>Status</th>
		              <th>Priority</th>
		              <th>Date</th>
		              <th>&nbsp;</th>
		            </tr>
		          </thead>
		          <tbody>
		          	<tr><td colspan="7">Loading...</td></tr>
		          </tbody>
		         </table> 
		
		      </div>
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
 
   var table = $('#table').dataTable( {
        "processing": true,
        "serverSide": true,
		"stateSave": true,
		"bFilter": false,
		"ordering": false,
		"columns": [
		{ "width": "25" },
		{  },
   		{ "width": "20%" },
		{ "width": "55" },
		{ "width": "50" },
		{ "width": "50" },
		{ "width": "50" },

		],
        "ajax": "../controller/incident.php?action=list"
    } );

  $("#btn_add").click(function(e) {
	  $(location).attr("href","../view/incident.php?action=add");
  });
}); 

function del(id) {
	BootstrapDialog.show({		
		message: 'Are you sure you want to delete this incident?',
		title : "<i class='glyphicon glyphicon-trash'></i>  Warning",
		type: BootstrapDialog.TYPE_DANGER,
		buttons: [{
			label: 'Cancel',
			action: function(dialogItself){
				dialogItself.close();
			},
			
		},{
			label: 'Delete',
			cssClass: 'btn-danger',
			action: function(dialogItself){
				res = $.ajax({type: "GET", url: "../controller/incident.php?action=delete&id="+id, async: false})	
			    $('#table').DataTable().ajax.reload();
				dialogItself.close();
			}
		}]
	});//end dialog	
}
</script>
</body>
</html>