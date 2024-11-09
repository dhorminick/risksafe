<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.'/login?r=/customs/risks');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';
    
    if (isset($_POST['delete-data'])){
        $type = sanitizePlus($_POST['data-type']);
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '' || !$type || $type == null || $type == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            $query="DELETE FROM as_customrisks WHERE risk_id = '$id' AND c_id = '$company_id'";
            $dataDeleted = $con->query($query);
                    
            if ($dataDeleted) {
                array_push($message, 'Risk Deleted Successfully!!');
            }else{
                array_push($message, 'Error 502: Error Deleting Data!!');
            }
        }
        
    }

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM as_customrisks WHERE risk_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
            $info = $IncidentExist->fetch_assoc();
		}else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Custom Risks | <?php echo $siteEndTitle ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
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
                    <form method="post">
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Risk Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="risks"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12">
                                        <label>Risk:</label>
                                        <div class="description-text"><?php echo ucwords($info['title']); ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Risk Sub Category:</label>
                                        <div class="description-text"><?php echo ucwords($info['sub']); ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Risk Description:</label>
                                        <div class="description-text"><?php echo nl2br(html_entity_decode($info['description'])); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Risk Owner:</label>
                                        <div class="description-text"><?php echo ucwords($info['owner']); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Selected Industry:</label>
                                        <div class="description-text"><?php echo ucwords( getIndustryTitle($info['industry'], $con) ); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Created Date:</label>
                                        <div class="description-text"><?php echo $info['cus_date']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group text-right">
                                <a href="edit-risk?id=<?php echo $info["risk_id"]; ?>" class="btn btn-md btn-primary btn-icon"> Update Risk Details</a>
                            </div>
                        </div>
                    </form>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                        <div style="text-align: center;"> 
                             <h3>Data Error!!</h3>
                             Custom Risk Doesn't Exist,
                             <p><a href="new-risk" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Risk</a></p></div>
                     </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">Custom Riskss</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-risk"><i class="fas fa-plus"></i> New Risk</a>
                    </div>
                    
                    <div class="card-body">
                        <?php
                            $CheckIfCustomExist = "SELECT * FROM as_customrisks WHERE c_id = '$company_id' ORDER BY id desc";
                            $CustomExist = $con->query($CheckIfCustomExist);
                            if ($CustomExist->num_rows > 0) { $i = 0;
                        ?>
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <tr>
                                <th>S/N</th>
                                <th style='width:50%;'>Title</th>
                                <th>Owner</th>
                                <th>Created On</th>
                                <th style='width:17%;'>...</th>
                            </tr>
                            <?php 
                                while($item = $CustomExist->fetch_assoc()){ $i++;
                                
                                
                                $viewLink = '?id='.$item["risk_id"].'" data-toggle="tooltip" title="View Risk" data-placement="right"';
                                $editLink = 'edit-risk?id='.$item["risk_id"].'" data-toggle="tooltip" title="Edit Risk" data-placement="right"';
                                $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="risk" data-id="'.$item["risk_id"];
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo ucwords($item["title"]); ?></td>
                                <td><?php echo ucwords($item["owner"]); ?></td>
                                <td><?php echo ucwords($item["cus_date"]); ?></td>
                                <td>
                                    <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                        <?php }else{ ?>
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Custom Treatment Created Yet,
                                <p><a href="new-treatment" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Treatment</a></p>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
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
    <style>
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