<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/report.php');


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
    <h1 class="page-header">My risk assessment reports</h1>
  <div class="col-lg-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
         <table class="table table-striped table-bordered table-hover" id="table" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>Team or Organisation</th>
              <th>Task or Process</th>
              <th>Date</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
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
		{ "width": "35%" },
		{ "width": "55" },
		{ "width": "60" }
		],
        "ajax": "../controller/report.php?action=list"
    } );

	
}); 

</script>
</body>
</html>