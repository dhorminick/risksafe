<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/assessments/all');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    
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
    
    // Include pagination library file 
    include_once '../../layout/pagination.class.php'; 
    
    // Include database configuration file 
    require_once '../../layout/dbConfig.php'; 
    
    // Set some useful configuration 
    $limit = 10; 
    
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_assessment WHERE c_id = '$company_id'"); 
    $result  = $query->fetch_assoc(); 
    $rowCount= $result['rowNum']; 
    
    // Initialize pagination class 
    $pagConfig = array( 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'contentDiv' => 'dataContainer', 
        'link_func' => 'columnSorting' 
    ); 
    $pagination =  new Pagination($pagConfig); 
    
    // Fetch records based on the limit 
    #$query = $db->query("SELECT * FROM as_cat ORDER BY id DESC LIMIT $limit");
    $_query = $db->query("SELECT * FROM as_assessment WHERE c_id = '$company_id' ORDER BY idassessment DESC LIMIT $limit");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Risk Assessments | <?php echo $siteEndTitle ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/sort.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
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
                    <div class="datalist-wrapper">
                    <!-- Loading overlay -->
                    <div class="loading-overlay"><div class="overlay-content"><?php require '../../layout/loading_data.php' ?></div></div>
                    <div class="card-body">
                        <!-- Data list container -->
                        <div id="dataContainer">
                        <table class="table table-striped sortable"> 
                        <thead> 
                            <tr> 
                                <th scope="col" class="sorting" style='width: 5%;' coltype="idassessment" colorder="">S/N</th>
                                <th scope="col" class="sorting" style='width: 23%;' coltype="idassessment" colorder="">Team or Organisation</th>
                                <th scope="col" class="sorting" style='width: 23%;' coltype="idassessment" colorder="">Task or Process</th>
                                <th scope="col" class="sorting" style='width: 17%;' coltype="idassessment" colorder="">Assessment Type</th>
                                <th scope="col" class="sorting" style='width: 10%;' coltype="idassessment" colorder="">Date</th>
                                <th scope="col" class="sorting" style='width: 21%;' coltype="idassessment" colorder="">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($_query->num_rows > 0){ $i = 0;
                                while($item = $_query->fetch_assoc()){ $i++;
                                    $id = $item['as_type'];
                                    $query = "SELECT ty_name FROM as_types WHERE idtype='$id'";
                                    $result = $con->query($query);
                                    if ($row = $result->fetch_assoc()) {
                                        $response["type"] = $row["ty_name"];
                                    }
                                    $as_HasValue = $item["has_values"];
                                    if($as_HasValue == 'true'){
                                        $_editLink = "edit-assessment?id=".$item["as_id"];
                                    }else{
                                        $_editLink = "add-assessment-details?id=".$item["as_id"];
                                    }
                    
                                    $viewLink = 'assessment-details?id='.$item["as_id"].'" data-toggle="tooltip" title="View Assessment" data-placement="right"';
                                    $editLink = $_editLink.'" data-toggle="tooltip" title="Edit Assessment" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="assessment" data-id="'.$item["as_id"];
                                    // $downloadLink = 'download?download=assessment&file=xls&id='.$item["as_id"].'" data-toggle="tooltip" title="Download Assessment" data-placement="left"';
                                    $downloadLink = 'javascript:void(0);" data-toggle="modal" data-target="#exportModal" export-data="assessment" export-id="'.$item["as_id"];
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['as_team']); ?></td>
                                    <td><?php echo ucwords($item['as_task']); ?></td>
                                    <th><?php echo ucwords($row["ty_name"]); ?></th>
                                    <td><?php echo date("m/d/Y", strtotime($item["as_date"])); ?></td>
                                    <td>
                                        <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                        <a href="<?php echo $downloadLink; ?>" class="export__data action-icons btn btn-success btn-action "><i class="fas fa-download"></i></a>
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
                         
                        <!-- Display pagination links --> 
                        <?php echo $pagination->createLinks(); ?>
                        </div>
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
    <script src="<?php echo $file_dir; ?>assets/js/admin/d_risk.js"></script>
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