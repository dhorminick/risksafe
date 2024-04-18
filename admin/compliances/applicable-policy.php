<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/compliances/applicable-policy');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    #include '../ajax/compliances.php';
    
    #confirm data
    $querys = "SELECT * FROM policyfields WHERE c_id = '$company_id'";
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
            $query="DELETE FROM policyfields WHERE p_id = '$id' AND c_id = '$company_id'";
            $dataDeleted = $con->query($query);
                    
            if ($dataDeleted) {
                array_push($message, 'Policy Deleted Successfully!!');
            }else{
                array_push($message, 'Error 502: Error Deleting Policy!!');
            }
        }
        
    }
    

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $pagetitle = 'Applicable Policy Data';
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM policyfields WHERE p_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
            $info = $IncidentExist->fetch_assoc();
		}else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
        $pagetitle = 'Registered Applicable Policyies';
    }
    
    // Include pagination library file 
    include_once '../../layout/pagination.class.php'; 
    
    // Include database configuration file 
    require_once '../../layout/dbConfig.php'; 
    
    // Set some useful configuration 
    $limit = 10; 
    
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM policyfields WHERE c_id = '$company_id'"); 
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
    $query = $db->query("SELECT * FROM policyfields WHERE c_id = '$company_id' ORDER BY id DESC LIMIT $limit");
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php echo $pagetitle.' | '.$siteEndTitle ?></title>
  <?php require '../../layout/general_css.php' ?>
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
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <?php if($toDisplay == true){ ?>
                <?php if ($in_exist == true) { ?>
                <div class="card">
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Policy Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="applicable-policy"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12">
                                        <label>Policy Title :</label>
                                        <div class="description-text"><?php echo $info['PolicyTitle']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Policy Number :</label>
                                        <div class="description-text"><?php echo $info['PolicyNumber']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Policy Description :</label>
                                        <div class="description-text"><?php echo $info['PolicyDescription']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Policy Effective Date :</label>
                                        <div class="description-text"><?php echo date("m/d/Y", strtotime($info["PolicyEffectiveDate"])); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Policy Review Date :</label>
                                        <div class="description-text"><?php echo date("m/d/Y", strtotime($info["PolicyReviewDate"])); ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Policy Requirements :</label>
                                        <div class="description-text"><?php echo $info['PolicyRequirements']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Compliance Responsibility :</label>
                                        <div class="description-text"><?php echo $info['ComplianceResponsibility']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Related Documents :</label>
                                        <div class="description-text"><?php echo $info['RelatedDocuments']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Policy Approval :</label>
                                        <div class="description-text"><?php echo $info['PolicyApproval']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Policy Review Revision History :</label>
                                        <div class="description-text"><?php echo $info['PolicyReviewRevisionHistory']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Policy Acknowledgment :</label>
                                        <div class="description-text">
                                        <?php 
                                            switch ($info['PolicyAcknowledgment']) {
                                                case '1':
                                                    echo 'True - Policy Acknowledged';
                                                    break;
                                                
                                                case '0':
                                                    echo 'False - Policy Not Acknowledged';
                                                    break;
                                                    
                                                default:
                                                    echo 'Error';
                                                    break;
                                            }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Applicable Policy Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">Applicable Policies</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-policy"><i class="fas fa-plus"></i> New Policy</a>
                    </div>
                    
                    <?php if($hasData == true) { ?>
                    <div class="datalist-wrapper">
                    <!-- Loading overlay -->
                    <div class="loading-overlay"><div class="overlay-content"><?php require '../../layout/loading_data.php' ?></div></div>
                    <div class="card-body">
                        <!-- Data list container -->
                        <div id="dataContainer">
                        <table class="table table-striped sortable"> 
                        <thead> 
                            <tr> 
                                <th scope="col" class="sorting" coltype="id" colorder="">S/N</th>
                                <th scope="col" class="sorting" coltype="id" colorder="">Policy Title</th>
                                <th scope="col" class="sorting" coltype="id" colorder="">Policy Description</th>
                                <th scope="col" class="sorting" coltype="id" colorder="">Policy Effective Date</th>
                                <th scope="col" class="sorting" coltype="id" colorder="">Policy Review Date</th>
                                <th scope="col" class="sorting" coltype="id" colorder="">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($query->num_rows > 0){ $i = 0;
                                while($item = $query->fetch_assoc()){ $i++;
                                    $viewLink = '?id='.$item["p_id"].'" data-toggle="tooltip" title="View Policy" data-placement="right"';
                                    $editLink = 'edit-policy?id='.$item["p_id"].'" data-toggle="tooltip" title="Edit Policy" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="policy" data-id="'.$item["p_id"];
                                    $downloadLink = 'javascript:void(0);" data-toggle="modal" data-target="#exportModal" export-data="policy" export-id="'.$item["p_id"];
                                    #$downloadLink = 'download?download=policy&file=xls&id='.$item["p_id"].'" data-toggle="tooltip" title="Download Policy" data-placement="left"';
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['PolicyTitle']); ?></td>
                                    <td><?php echo ucwords($item['PolicyDescription']); ?></td>
                                    <th><?php echo ucwords($item["PolicyEffectiveDate"]); ?></th>
                                    <td><?php echo ucwords($item["PolicyReviewDate"]); ?></td>
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
                    <?php }else{ #empty data ?>
                    <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                              <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Applicable Policy Registered Yet,
                                <p><a href="new-policy" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Register New Policy</a></p>
                            </div>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require '../../layout/delete_data.php' ?>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/admin/d_policy.js"></script>
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