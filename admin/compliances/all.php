<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/compliances/all');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    
    #confirm data
    $querys = "SELECT * FROM as_compliancestandard WHERE c_id = '$company_id'";
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
            $query="DELETE FROM as_compliancestandard WHERE compli_id = '$id' AND c_id = '$company_id'";
            $dataDeleted = $con->query($query);
                    
            if ($dataDeleted) {
                array_push($message, 'Compliance Data Deleted Successfully!!');
            }else{
                array_push($message, 'Error 502: Error Deleting Compliance!!');
            }
        }
        
    }
    
    // Include pagination library file 
    include_once $file_dir.'layout/pagination.class.php'; 
    
    // Include database configuration file 
    require_once $file_dir.'layout/dbConfig.php'; 
    
    // Set some useful configuration 
    $limit = 10; 
    
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM as_compliancestandard WHERE c_id = '$company_id'"); 
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
    $_query = $db->query("SELECT * FROM as_compliancestandard WHERE c_id = '$company_id' ORDER BY idcompliance DESC LIMIT $limit");
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Compliance Standard | <?php echo $siteEndTitle ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/sort.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">My Compliances</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-compliance"><i class="fas fa-plus"></i> New Compliance</a>
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
                                <th scope="col" class="sorting" style='width: 5%;' coltype="idcompliance" colorder="">S/N</th>
                                <th scope="col" class="sorting" style='width: 34%;' coltype="com_compliancestandard" colorder="">Compliance Task or Obligation</th>
                                <th scope="col" class="sorting" style='width: 30%;' coltype="action" colorder="">Action</th>
                                <th scope="col" class="sorting" style='width: 10%;' coltype="frequency" colorder="">Status</th>
                                <th scope="col" class="sorting" style='width: 21%;' coltype="idcompliance" colorder="">...</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php 
                            if($_query->num_rows > 0){ $i = 0;
                                while($item = $_query->fetch_assoc()){ $i++;
                                    
                                    $viewLink = 'compliance-details?id='.$item["compli_id"].'" data-toggle="tooltip" title="View Compliance" data-placement="right"';
                                    $editLink = 'edit-compliance?id='.$item["compli_id"].'" data-toggle="tooltip" title="Edit Compliance" data-placement="right"';
                                    $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="compliance" data-id="'.$item["compli_id"];
                                    // $downloadLink = 'download?download=compliance&file=xls&id='.$item["compli_id"].'" data-toggle="tooltip" title="Download Compliance" data-placement="left"';
                                    $downloadLink = 'javascript:void(0);" data-toggle="modal" data-target="#exportModal" export-data="compliance" export-id="'.$item["compli_id"];
                                    
                                    if($item['type'] == 'imported'){$c_control = $item['imported_controls'];}else{$c_control = $item['com_controls'];} if($c_control == '' || $c_control == null){$c_control = 'None Documented!!';}
                                    if($item["co_status"] == null || $item["co_status"] == ''){$item["co_status"] = 'Un-Assessed';}
                            ?> 
                                <tr> 
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo mb_strimwidth(ucwords($item["com_compliancestandard"]), 0, 70, "..."); ?></td>
                                    <th><?php echo mb_strimwidth(ucwords($c_control), 0, 70, "..."); ?></th>
                                    <td><?php echo ucwords($item["co_status"]); ?></td>
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
                                No Compliance Data Registered Yet,
                                <p><a href="new-compliance" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Compliance</a></p>
                            </div>
                    </div>
                    <?php } ?>
                </div>
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
    <script src="<?php echo $file_dir; ?>assets/js/admin/d_compliance.js"></script>
    <script>
		 $(".delete").click(function(e) { 
            var i_d = $(this).attr('data-id');
            var t_ype = $(this).attr('data-type');
            if (i_d == '' || !i_d || i_d == null || t_ype == '' || !t_ype || t_ype == null) {
                alert('Error 402!!');
                //refresh
                //window.location.assign("audits");
            } else {
                $("#data-id").val();
                $("#data-id").val(i_d);
                $("#data-type").val();
                $("#data-type").val(t_ype);
                $("#view-id").html();
                $("#view-id").html(i_d);
                $(".view-type").html();
                $(".view-type").html(t_ype);
            }
        });
	</script>
	<style>
	    .action-icons{
	        margin-top:5px !important;
	    }
	</style>
</body>
</html>