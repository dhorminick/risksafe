<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/business/incidents');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    #include '../ajax/incidents.php';
    
    #confirm data
    $querys = "SELECT * FROM as_incidents WHERE c_id = '$company_id'";
	$result = $con->query($querys);
	if ($result->num_rows > 0) {
		$hasData = true;
	} else {
		$hasData = false;
	}
    
    if (isset($_POST['delete-data'])){
        $type = sanitizePlus($_POST['data-type']);
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '' || !$type || $type == null || $type == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            $query="DELETE  FROM as_incidents WHERE in_id = '$id' AND c_id = '$company_id'";
            $dataDeleted = $con->query($query);
                    
            if ($dataDeleted) {
                array_push($message, 'Incident Deleted Successfully!!');
            }else{
                array_push($message, 'Error 502: Error Deleting Incident!!');
            }
        }
        
    }
    

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $pagetitle = 'Incident Data';
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM as_incidents WHERE in_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
            $info = $IncidentExist->fetch_assoc();
		}else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
        $pagetitle = 'Registered Incidents';
    }
    
    // Include pagination library file 
    include_once $file_dir.'layout/pagination.class.php'; 
    
    // Include database configuration file 
    require_once $file_dir.'layout/dbConfig.php'; 
    
    // Set some useful configuration 
    $limit = 10; 
    
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_incidents WHERE c_id = '$company_id'"); 
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
    $query = $db->query("SELECT * FROM as_incidents WHERE c_id = '$company_id' ORDER BY idincident DESC LIMIT $limit");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php echo $pagetitle.' | '.$siteEndTitle ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/sort.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
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
                <?php if($toDisplay == true){ ?>
                <?php if ($in_exist == true) { ?>
                <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Incident Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="incidents"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12 col-lg-12">
                                        <label>Case Title :</label>
                                        <div class="description-text"><?php echo $info['in_title']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-8">
                                        <label>Reported By :</label>
                                        <div class="description-text"><?php echo $info['in_reported']; ?></div>
                                    </div>
                                    <div class="user-description col-4">
                                        <label>Date :</label>
                                        <div class="description-text"><?php echo $info['in_date']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Team or Department :</label>
                                        <div class="description-text"><?php echo $info['in_team']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Description :</label>
                                        <div class="description-text"><?php echo $info['in_descript']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Financial Loss :</label>
                                        <div class="description-text"><?php echo $info['in_financial']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Injuries :</label>
                                        <div class="description-text"><?php echo $info['in_injuries']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Complaints :</label>
                                        <div class="description-text"><?php echo $info['in_complaints']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Compliance Breach :</label>
                                        <div class="description-text"><?php echo $info['in_compliance']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Impact :</label>
                                        <div class="description-text"><?php echo $info['in_impact']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Priority :</label>
                                        <div class="description-text"><?php echo $info['in_priority']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Status :</label>
                                        <div class="description-text"><?php echo $info['in_status']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-body">
                            <div class="form-group">
								<a href="edit-incident?id=<?php echo $info['in_id']; ?>" class="btn btn-md btn-primary">Edit Incident</a>
								<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
							</div>
							</div>
                        </div>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px;">Incident Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">Registered Incidents</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-incident"><i class="fas fa-plus"></i> New Incident</a>
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
                                <th scope="col" class="sorting" coltype="idincident" colorder="">S/N</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Title</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Team or Department</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Status</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Priority</th>
                                <th scope="col" class="sorting" coltype="idincident" colorder="">Date</th>
                                <th scope="col" class="sorting" coltype="idincident">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($query->num_rows > 0){ $i = 0;
                                while($item = $query->fetch_assoc()){ $i++;
                                    $viewLink = '?id='.$item["in_id"].'" data-toggle="tooltip" title="View Incident" data-placement="right" class="action-icons btn btn-primary btn-action mr-1"';
                                    $editLink = 'edit-incident?id='.$item["in_id"].'" data-toggle="tooltip" title="Edit Incident" data-placement="right" class="action-icons btn btn-info btn-action mr-1"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="insident" data-id="'.$item["in_id"];
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['in_title']); ?></td>
                                    <td><?php echo ucwords($item['in_team']); ?></td>
                                    <th><?php echo ucwords($item["in_status"]); ?></th>
                                    <td><?php echo ucwords($item["in_priority"]); ?></td>
                                    <td><?php echo date("m/d/Y", strtotime($item["in_date"])); ?></td>
                                    <td>
                                        <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
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
                    <?php }else{ #empty data ?>
                    <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                              <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Compliance Data Registered Yet,
                                <p><a href="new-compliance" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Compliance</a></p>
                            </div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/delete_data.php' ?>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/admin/d_incident.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
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