<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/paymentdata.php');


$assess = new paymentdata;
$list = $assess->listOfPayment(0, 10);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("header.php"); ?>
    <style>
     

        .heading {
            height: auto;
            background: rgb(0, 46, 76);
            background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%);
            display: flex;
            margin-bottom: 1rem;
            color: white;
            justify-content: flex-start;
            align-items: center;
            padding: 0rem 1rem;
            border-bottom-left-radius: 2rem;
            border-top-right-radius: 2rem;
           
        }
        .panel {
    /* Set a max width for the panel to contain the table */
    max-width: 100%;
    overflow-x: auto; /* Enable horizontal scrolling if needed */
}

    </style>
</head>


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
             
                    <div class="heading">
                        <h1 class="page-header">My Payments History</h1>
                    </div>
   
                    <div class="">
                        <div class="panel panel-default">
                            <div class="panel-body">

                               
                                <button style="margin-bottom: 1rem;" type="button" class="btn btn-md btn-info" id="btn_clearhistory"> Clear History</button>
                               

                                <table class="table table-striped table-bordered table-responsive table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer Name</th>
                                            <th>Customer ID</th>
                                            <th>Transaction ID</th>
                                            <th>Currency</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <!-- <th>&nbsp;</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; ?>
                                        <?php foreach ($list as $item): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $count++; ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['customer_name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['customer']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['transaction_id']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['currency']; ?>
                                                </td>
                                                <td>
                                                    <?php echo rtrim('$'.$item['amount'], '0'); ?>
                                                </td>
                                                <td><?php echo $item['created_at']; ?></td>
                                                <td>
                                                    <?php echo $item['status']; ?>
                                                </td>
                                              

                                            </tr>
                                        <?php endforeach; ?>
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
      //  $('#table').DataTable();

        var empDataTable = $('#table').DataTable({
        dom: 'Blfrtip',
        buttons: [
        
        {
        extend: 'pdf',
        // exportOptions: {
        // columns: [0,1] // Column index which needs to export
        // }
        },
        {
        extend: 'csv',
        },
        {
        extend: 'excel',
        } 
        ] 

        });
 

    });


    $("#btn_clearhistory").click(function (e) {
   
   // $(location).attr("href", "../controller/paymentdata.php?action=clearhistory");


   BootstrapDialog.show({
           title: "<i class='glyphicon glyphicon-trash'></i> Warning",
           type: BootstrapDialog.TYPE_DANGER,
           message: 'Are you sure you want to Clear  this history?',
           buttons: [{
               label: 'Cancel',
               action: function (dialogItself) {
                   dialogItself.close();
               },

           }, {
               label: 'Clear History',
               cssClass: 'btn-danger',
               action: function (dialogItself) {
                   res = $.ajax({ type: "POST", url: "../controller/paymentdata.php?action=clearhistory", async: false })
                   location.reload();
                   //$('#table').DataTable().ajax.reload();
                   //dialogItself.close();
               }
           }]
       });



 });
</script>
</body>

</html>