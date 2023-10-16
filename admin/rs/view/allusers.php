<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/users.php');
$db = new db();
$conn = $db->connect();
$currentUser = $_SESSION["userid"];

$sql = "SELECT role FROM users WHERE iduser = '$currentUser'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $currentUserRole = $row['role'];

  if ($currentUserRole == "superadmin" || $currentUserRole == "client") {
  } else {
    header("Location: main.php");
  }

}

if (isset($_REQUEST["response"]) && $_REQUEST["response"]=="err_limitexceed") {
  $msg="Maximum user limit reached. You can only add up to 20 users.";
}
$start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
    $length = isset($_REQUEST["length"]) ? intval($_REQUEST["length"]) : 10;
    
    // Get the paginated list of users and total count from the listUser() method
    $result = $user->listUser($start, $length);
    $list = $result["data"];
     
    $num = $result["num_total"];

    $fulldata = array();
    $data = array();

    $fulldata["draw"] = isset($_REQUEST["draw"]) ? intval($_REQUEST["draw"]) : 1;
    $fulldata["recordsTotal"] = $num;
    $fulldata["recordsFiltered"] = $num;


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once("header.php"); ?>
</head>
<style>
.background{
    height: auto;
			background: rgb(0, 46, 76);
			background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%);
			display: flex;
      margin-bottom: 1rem;
			justify-content: flex-end;
			align-items: center;
			padding: 2rem 2rem;
			border-bottom-left-radius: 2rem;
			border-top-right-radius: 2rem;
			
  }
  .heading {
			display: flex;
   
			align-items: center;
		}
    .clearfix .searchbar{
      margin-left: 2rem;
    }
    .clearfix .heading{
      margin-left: 2rem;
    }
    #btn_add{
      border: none;
      outline: none;
    }
    /* Style the modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
}

/* Style the modal content */
.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
}

/* Style the close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

</style>
<body>
  <!-- header -->
  <div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span
            class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;">
          <?php echo APP_TITLE; ?>
        </a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"
              style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span
                class="caret"></span></a>
            <ul id="g-account-menu" class="dropdown-menu" role="menu">
              <?php include_once("menu_top.php"); ?>
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
        <?php include_once("menu.php"); ?>
        
        <!-- /col-3 -->
      </div>
      <div class="col-lg-9 col-md-12">
        <h1 class="page-header">All New Users</h1>
        
        <div class="col-lg-12 col-md-12">
        <div class="alert <?php if (isset($msg)) echo 'alert-danger'; else echo 'alert-info'; ?>" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?> id="notify">

                <?php if (isset($msg)) echo $msg;?>
              </div>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="clearfix mb20 background">
              
                <div class="heading">
             
                
             <span>
             <button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ New User</button>
             </span>
                 
                
                </div>
               
              </div>
          

              <table class="table table-striped table-bordered table-hover display" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>User Name</th>
            <th>User Email</th>
            <th>Company</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($list !== false) {
            foreach ($list as $item) {
                echo '<tr>';
                echo '<td>' . $item["iduser"] . '</td>';
                echo '<td>' . $item["u_name"] . '</td>';
                echo '<td>' . $item["u_mail"] . '</td>';
                echo '<td>' . $item["c_company"] . '</td>';
                echo '<td>' . $item["u_phone"] . '</td>';
                echo '<td>' . $item["role"] . '</td>';
                echo '<td>' . '<div style="text-align: center;">
                    <a title="Edit" class="btn btn-xs btn-primary" href="alluser.php?action=edit&id=' . $item["iduser"] . '"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                    <a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["iduser"] . '\');"><i class="glyphicon glyphicon-trash"></i></a>
                </div>' . '</td>';
                echo '</tr>';
            }
        }
        ?>
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

  <?php include_once("footer.php"); ?>


 
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable({
        "order": [[ 0, "desc" ]]
      });
      
        $("#btn_add").click(function () {
            $(location).attr("href", "../view/alluser.php?action=add");
        });
        if ($("#notify").is(":visible")) {
            // Set a timeout to hide the alert after 5 seconds
            setTimeout(function () {
                $("#notify").fadeOut(); // Fade out the alert
            }, 5000); // 5000 milliseconds = 5 seconds
        }
  
    });
    function del(id) {
        BootstrapDialog.show({
            message: 'Are you sure you want to delete this entry?',
            buttons: [{
                label: 'No, go back',
                action: function (dialogItself) {
                    dialogItself.close();
                },
            }, {
                label: 'Yes, delete',
                action: function (dialogItself) {
                    res = $.ajax({ type: "GET", url: "../controller/users.php?action=delete&id=" + id, async: false });
                    dialogItself.close();
                    location.reload();
                    

                    //$('#dataTable').DataTable().ajax.reload();
                    // dialogItself.close();
                }
            }]
        }); // end dialog	
    }
      
    
</script>


</body>

</html>