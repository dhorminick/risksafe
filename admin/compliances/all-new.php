<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/assessments/all');
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    #include '../ajax/assessment.php';
    
    if (isset($_POST['delete-data'])){
        $type = sanitizePlus($_POST['data-type']);
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '' || !$type || $type == null || $type == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            $query="DELETE  FROM as_assessment WHERE as_id = '$id' AND c_id = '$company_id'";
            $dataDeleted = $con->query($query);
                    
            if ($dataDeleted) {
                $query2 ="DELETE  FROM as_details WHERE as_assessment = '$id' AND c_id = '$company_id'";
                $dataDeleted2 = $con->query($query2);
                        
                if ($dataDeleted2) {
                    array_push($message, 'Assessment Deleted Successfully!!');
                }else{
                    array_push($message, 'Error 502: Error Deleting Assessment!!');
                }
            }else{
                array_push($message, 'Error 502: Error Deleting Assessment!!');
            }
        }
        
    }
    
    $_query = $con->query("SELECT * FROM as_compliancestandard WHERE c_id = '$company_id' ORDER BY idcompliance DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Risk Assessments | <?php echo $siteEndTitle ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/datatables/datatables.min.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">My Risk Assessments</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-assessment"><i class="fas fa-plus"></i> New Assesment</a>
                    </div>
                    <div class="card-body">
                        <!-- Data list container -->
                        <div id="dataContainer">
                        <table class="table table-striped table-hover" id="table-2"> 
                        <thead> 
                            <tr> 
                                <th style='width: 5%;'>S/N</th>
                                <th style='width: 30%;'>Compliance Task or Obligation</th>
                                <th style='width: 22%;'>Requirements</th>
                                <th style='width: 21%;'>Action</th>
                                <th style='width: 10%;'>Frequency</th>
                                <th style='width: 12%;'/th>  
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($_query->num_rows > 0){ $i = 0;
                                while($item = $_query->fetch_assoc()){ $i++;
                                    
                                    $viewLink = 'compliance-details?id='.$item["compli_id"].'" data-toggle="tooltip" title="View Compliance" data-placement="right"';
                                    $editLink = 'edit-compliance?id='.$item["compli_id"].'" data-toggle="tooltip" title="Edit Compliance" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="compliance" data-id="'.$item["compli_id"];
                                    $downloadLink = 'download?download=compliance&file=xls&id='.$item["compli_id"].'" data-toggle="tooltip" title="Download Compliance" data-placement="left"';
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo mb_strimwidth(ucwords($item["com_compliancestandard"]), 0, 70, "..."); ?></td>
                                    <td><?php echo mb_strimwidth(ucwords($item["com_training"]), 0, 70, "..."); ?></td>
                                    <th><?php echo mb_strimwidth(ucwords($item["action"]), 0, 70, "..."); ?></th>
                                    <td><?php echo ucwords($item["frequency"]); ?></td>
                                    <td>
                                        <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                        <a href="<?php echo $downloadLink; ?>" class="action-icons btn btn-success btn-action "><i class="fas fa-download"></i></a>
                                    </td>
                                </tr> 
                            <?php 
                                } 
                            }else{ 
                                echo '<tr><td colspan="6">No Records found...</td></tr>'; 
                            } 
                            ?> 
                        </tbody>  
                        </table> 
                        </div>
                    </div>
                </div>
            </div>
            </section>
        </div>
        <!-- basic modal -->
        <?php require '../../layout/delete_data.php' ?>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <!-- Page Specific JS File -->
    <script src="<?php echo $file_dir; ?>assets/bundles/datatables/datatables.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/page/datatables.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .main-footer {
          margin-top: 0px !important;
        }
    </style>
    <script>
		 $(".delete").click(function(e) { 
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            if (id == '' || !id || id == null || type == '' || !type || type == null) {
                alert('Error 402!!');
                //refresh
                //window.location.assign("audits");
            } else {
                $("#data-id").val();
                $("#data-id").val(id);
                $("#data-type").val();
                $("#data-type").val(type);
                $("#view-id").html();
                $("#view-id").html(id);
                $(".view-type").html();
                $(".view-type").html(type);
            }
        });
	</script>
</body>
</html>