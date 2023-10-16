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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once("header.php"); ?>
</head>
<style>
  .background {
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

  #btn_add {
    border: none;
    outline: none;
  }
</style>

<body>
  <!-- header -->
  <div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;">
          <?php echo APP_TITLE; ?>
        </a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
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
        <h1 class="page-header">Contact</h1>
        <div class="col-lg-12 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="clearfix mb20 background">
                <div class="heading">
                  <h4 style="color:white;">Contact Info</h4>
                </div>

              </div>
              <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0" >
                <thead>
                  <tr>
                    <th>#</th>
                    <th> Name</th>
                    <th> Email</th>
                    <th>Subject</th>
                    <th>Question</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="7">Loading...</td>
                  </tr>
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

  <!-- Include necessary JavaScript files for DataTables -->
  <script>
  var table;
    $(document).ready(function(e) {
      table = $('#dataTable').dataTable({
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "bFilter": false,
        "ordering": false,
        "columns": [
          {
            "width": "25px"
          },
          //null,
          {
            "width": "25px"
          },
          {
            "width": "25px"
          },
          {
            "width": "25px"
          },
          {
            "width": "25px"
          },
          {
            "width": "25px"
          },
          {
            "width": "25px"
          }
        ],
        "ajax": "../controller/contact.php?action=list"
        //  "ajax": "../controller/readnotification.php?action=readnotification"
      });


      $("#btn_add").click(function(e) {
        $(location).attr("href", "../view/alluser.php?action=add");
      });


    });

    function updateReadStatus(notificationId, messageCell) {
      messageCell.addClass('highlight'); // Temporarily add the 'highlight' class to the message cell
      // console.log(notificationId)
      $.ajax({
        type: 'POST',
        url: '../controller/contact.php?action=statusupdate',
        data: {
          id: notificationId
        },
        success: function() {
          // console.log('Notification status updated successfully.');
          messageCell.removeClass('highlight'); // Remove the 'highlight' class after updating status
          $('#dataTable').DataTable().ajax.reload();
        },
        error: function() {
          // console.error('Failed to mark notification as read.');
          // messageCell.removeClass('highlight'); // Remove the 'highlight' class on error as well
        }
      });
      // 5000 milliseconds = 5 seconds
    }
    $('#dataTable tbody').on('click', 'tr td:not(:last-child)', function(e) {
      e.stopPropagation();
      var row = $(this).closest('tr');
      var notificationId = $(this).closest('tr').find('td:first-child').text();
      // var notificationId = dataArray[0];
      console.log("under"+notificationId)
      var messageCell = row.find('tr td:not(:last-child)'); // Assuming message cell is the 2nd column, change it if needed

      // Call the function to update read_status after a delay
      updateReadStatus(notificationId, messageCell);

      // Update the UI to show the notification as read immediately (without waiting for 5 seconds)
      messageCell.css('font-weight', 'normal');
    });
    
    function del(id) {
      BootstrapDialog.show({
        message: 'Are you sure you want to delete this contact ?',
        buttons: [{
          label: 'No, go back',
          action: function(dialogItself) {
            dialogItself.close();
          },

        }, {
          label: 'Yes, delete',
          action: function(dialogItself) {
            //kod za booking
            res = $.ajax({
              type: "GET",
              url: "../controller/contact.php?action=deletecontact&id=" + id,
              async: false
            })
            $('#dataTable').DataTable().ajax.reload();
            dialogItself.close();
          }
        }]
      }); //end dialog	
    }
  </script>
</body>

</html>