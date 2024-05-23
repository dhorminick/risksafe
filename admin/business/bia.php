<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/business/bia');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    
    #confirm data
    $querys = "SELECT * FROM as_bia WHERE c_id = '$company_id'";
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
            $query="DELETE  FROM as_bia WHERE bia_id = '$id' AND c_id = '$company_id'";
            $dataDeleted = $con->query($query);
                    
            if ($dataDeleted) {
                array_push($message, 'BIA Deleted Successfully!!');
            }else{
                array_push($message, 'Error 502: Error Deleting BIA!!');
            }
        }
        
    }
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $pagetitle = 'BIA Data';
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM as_bia WHERE bia_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
            $info = $IncidentExist->fetch_assoc();
		}else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
        $pagetitle = "Registered BIA's";
    }
    
    // Include pagination library file 
    include_once $file_dir.'layout/pagination.class.php'; 
    
    // Include database configuration file 
    require_once $file_dir.'layout/dbConfig.php'; 
    
    // Set some useful configuration 
    $limit = 10; 
    
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_bia WHERE c_id = '$company_id'"); 
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
    $query = $db->query("SELECT * FROM as_bia WHERE c_id = '$company_id' ORDER BY idbia DESC LIMIT $limit");
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
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">BIA Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="bia"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12">
                                        <label>Critical Business Activity :</label>
                                        <div class="description-text"><?php echo $info['bia_activity']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Description :</label>
                                        <div class="description-text"><?php echo $info['bia_descript']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Priority :</label>
                                        <div class="description-text"><?php echo $info['bia_priority']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Impact of Loss :</label>
                                        <div class="description-text"><?php echo $info['bia_impact']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Recovery Time Objective :</label>
                                        <div class="description-text"><?php echo $info['bia_time']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Preventative / Recovery Actions :</label>
                                        <div class="description-text"><?php echo $info['bia_action']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Resource Requirements :</label>
                                        <div class="description-text"><?php echo $info['bia_resource']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-body">
                            <div class="form-group">
								<a href="edit-bia?id=<?php echo $info["bia_id"]; ?>" class="btn btn-md btn-primary btn-icon"> Edit BIA</a>
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
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Business Impact Analysis Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">Registered BIA's</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-bia"><i class="fas fa-plus"></i> New BIA</a>
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
                                <th scope="col" class="sorting" coltype="idbia" colorder="">S/N</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Activity</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Priority</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Impact</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">Time</th>
                                <th scope="col" class="sorting" coltype="idbia" colorder="">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($query->num_rows > 0){ $i = 0;
                                while($item = $query->fetch_assoc()){ $i++;
                                    $viewLink = '?id='.$item["bia_id"].'" data-toggle="tooltip" title="View BIA" data-placement="right"';
                                    $editLink = 'edit-bia?id='.$item["bia_id"].'" data-toggle="tooltip" title="Edit BIA" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="BIA" data-id="'.$item["bia_id"];
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['bia_activity']); ?></td>
                                    <td><?php echo ucwords($item['bia_priority']); ?></td>
                                    <th><?php echo ucwords($item["bia_impact"]); ?></th>
                                    <td><?php echo ucwords($item["bia_time"]); ?></td>
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
                                No Business Impact Analysis Created Yet,
                                <p><a href="new-bia" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New BIA</a></p>
                            </div>
                    </div>
                    <?php } ?>
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
    <script src="<?php echo $file_dir; ?>assets/js/admin/d_bia.js"></script>
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