<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/audits');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    function getNext($date, $freq){

		if ($freq == 0) {
			$next = "Not set";
		} else {
			$next = strtotime($date) + ($freq * 24 * 60 * 60);
			$next = date("m/d/Y", $next);
		}
		return $next;
	}
	
    if (isset($_POST['delete-data'])){
        $type = sanitizePlus($_POST['data-type']);
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            # code...
            $query="DELETE  FROM as_auditcontrols WHERE aud_id = '$id' AND c_id = '$company_id'";
                    $dataDeleted = $con->query($query);
                            
                    if ($dataDeleted) {
                        array_push($message, 'Audit Deleted Successfully!!');
                    }else{
                        array_push($message, 'Error 502: Error Deleting Data!!');
                    }
        }
        
    }
    
    #confirm data
    $querys = "SELECT * FROM as_auditcontrols WHERE c_id = '$company_id'";
	$result = $con->query($querys);
	if ($result->num_rows > 0) {
		$hasData = true;
	} else {
		$hasData = false;
	}

    
    // Include pagination library file 
    include_once $file_dir.'layout/pagination.class.php'; 
    
    // Include database configuration file 
    require_once $file_dir.'layout/dbConfig.php'; 
    
    // Set some useful configuration 
    $limit = 10; 
    
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_auditcontrols WHERE c_id = '$company_id'"); 
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
    $query = $db->query("SELECT * FROM as_auditcontrols WHERE c_id = '$company_id' ORDER BY idcontrol DESC LIMIT $limit");
		
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Audit Of Controls | <?php echo $siteEndTitle ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/sort.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">My Audits</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-audit"><i class="fas fa-plus"></i> New Audit</a>
                    </div>
                    
                    <?php if($hasData == true) { ?>
                    <div class="datalist-wrapper">
                    <!-- Loading overlay -->
                    <div class="loading-overlay"><div class="overlay-content"><?php require $file_dir.'layout/loading_data.php' ?></div></div>
                    <div class="card-body">
                        <!-- Data list container -->
                        <div id="dataContainer">
                        <table class="table table-striped sortable"> 
                        <thead> 
                            <tr> 
                                <th scope="col" class="sorting" coltype="idcontrol" colorder="">S/N</th>
                                <th scope="col" class="sorting" coltype="idcontrol" colorder="" style='width: 30%;'>Control</th>
                                <th scope="col" class="sorting" coltype="idcontrol" colorder="">Issued On</th>
                                <th scope="col" class="sorting" coltype="idcontrol" colorder="">Effectiveness</th>
                                <th scope="col" class="sorting" coltype="idcontrol" colorder="">Frequency</th>
                                <th scope="col" class="sorting" coltype="idcontrol" colorder="">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($query->num_rows > 0){ $i = 0;
                                while($item = $query->fetch_assoc()){ $i++;
                                    switch ($item["con_effect"]) {
                                        case 0:
                                            $effect="Not selected";
                                            break;
                                        case 1:
                                            $effect="Not effective";
                                            break;
                                        case 2:
                                            $effect="Effective";
                                            break;
                                    }
                                    function get_Frequencyis($freq){

                                        if ($freq == 7) {
                                            return "As Required";
                                        } else if ($freq == 1) {
                                            return "Daily Controls";
                                        } else if ($freq == 2) {
                                            return "Weekly Controls";
                                        } else if ($freq == 3) {
                                            return "Fort-Nightly Controls";
                                        } else if ($freq == 4) {
                                            return "Monthly Controls";
                                        } else if ($freq == 5) {
                                            return "Semi-Annually Controls";
                                        } else if ($freq == 6) {
                                            return "Annually Controls";
                                        } else {
                                            return "Un-Assessed";
                                        }
                                    }
                                    $viewLink = 'audit-details?id='.$item["aud_id"].'" data-toggle="tooltip" title="View Audit" data-placement="right"';
                                    $editLink = 'edit-audit?id='.$item["aud_id"].'" data-toggle="tooltip" title="Edit Audit" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="audit" data-id="'.$item["aud_id"];
                                    $downloadLink = 'javascript:void(0);" data-toggle="modal" data-target="#exportModal" export-data="audit" export-id="'.$item["aud_id"];
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item["con_control"]); ?></td>
                                    <td><?php echo date("m-d-Y", strtotime($item["con_date"])); ?></td>
                                    <td><?php echo ucwords($effect); ?></td>
                                    <td><?php echo get_Frequencyis($item["con_frequency"]); ?></td>
                                    <td>
                                        <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                        <a href="<?php echo $downloadLink; ?>" class="export__data action-icons btn btn-success btn-action "><i class="fas fa-download"></i></a>
                                    </td>
                                </tr> 
                            <?php }}  ?> 
                        </tbody> 
                        </table> 
                        <!-- Display pagination links -->
                        <?php echo $pagination->createLinks(); ?>
                        </div>
                    </div>
                    </div>
                    <?php }else{ #empty data ?>
                    <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                              <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Audit Of Control Created Yet,
                                <p><a href="new-audit" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Audit</a></p>
                            </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/delete_data.php' ?>
        <?php require $file_dir.'layout/footer.php' ?>
        </div>
        
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/js/admin/d_audit.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .main-footer {
            margin-top: 0px;
        }
	    .action-icons{
	        margin-top:5px !important;
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