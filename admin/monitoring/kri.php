<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/kri');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';

    if (isset($_POST['delete-data'])){
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            $query="DELETE  FROM kri WHERE k_id = '$id' AND c_id = '$company_id'";
                    $dataDeleted = $con->query($query);
                            
                    if ($dataDeleted) {
                        array_push($message, 'KRI Deleted Successfully!!');
                    }else{
                        array_push($message, 'Error 502: Error Deleting Data!!');
                    }
        }
        
    }
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $pagetitle = 'Key Risk Indicators';
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM kri WHERE k_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
            $info = $IncidentExist->fetch_assoc();
		}else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
        $pagetitle = 'Registered Key Risk Indicators';
    }
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
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Risk Indicator Details</h3>
                                <div class='header-a'>
                                    <a class="btn btn-primary btn-icon icon-left header-a" href="kri"><i class="fas fa-arrow-left"></i> Back</a>
                                    <a href='edit-kri?id=<?php echo $info['k_id']; ?>' class="btn btn-md btn-outline-secondary">Edit KRI</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    
                                <div class="user-description col-12">
                                    <label>Indicator Name:</label>
                                    <div class="description-text"><?php echo $info['indicator']; ?></div>
                                </div>
                                <div class="user-description col-12">
                                    <label>Description:</label>
                                    <div class="description-text"><?php echo $info['description']; ?></div>
                                </div>
                                <div class="user-description col-12">
                                    <label>Category:</label>
                                    <div class="description-text"><?php echo $info['category']; ?></div>
                                </div>
                                
                                
                                <div class="user-description col-12 col-lg-4">
                                    <label>Owner:</label>
                                    <div class="description-text"><?php echo $info['owner']; ?></div>
                                </div>
                                
                                <div class="user-description col-lg-4 col-12">
                                    <label>Status :</label>
                                    <div class="description-text"><?php echo _getStatus($info['status']); ?></div>
                                </div>
                                <div class="user-description col-lg-4 col-12">
                                    <label>Frequency of Monitoring:</label>
                                    <div class="description-text"><?php echo _getFrequencyTitle($info['frequency']); ?></div>
                                </div>
                                
                                <div class="user-description col-lg-4 col-12">
                                    <label>Threshold:</label>
                                    <div class="description-text"><?php echo $info['threshold']; ?>%</div>
                                </div>
                                <div class="user-description col-lg-4 col-12">
                                    <label>Current Value:</label>
                                    <div class="description-text"><?php echo $info['current']; ?>%</div>
                                </div>
                                <div class="user-description col-lg-4 col-12">
                                    <label>Target Value:</label>
                                    <div class="description-text"><?php echo $info['target']; ?>%</div>
                                </div>
                                
                                <div class="user-description col-lg-4 col-12">
                                    <label>Date Captured:</label>
                                    <div class="description-text"><?php echo $info['category']; ?></div>
                                </div>
                                <div class="user-description col-lg-4 col-12">
                                    <label>Trend:</label>
                                    <div class="description-text"><?php echo _getTrend($info['trend']); ?></div>
                                </div>
                                <div class="user-description col-lg-4 col-12">
                                    <label>Priority:</label>
                                    <div class="description-text"><?php echo _getPriority($info['priority']); ?></div>
                                </div>
                                
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 KRi Doesn't Exist!!,
                                 <p><a href="/help#data-error" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Help</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h3 class="d-inline">Registered KRIs</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-kri"><i class="fas fa-plus"></i> New KRI</a>
                    </div>
                    <div class='card-body'>
                        <?php 
                            $CheckIfIncidentExist = "SELECT * FROM kri WHERE c_id = '$company_id' ORDER BY id desc";
                            $IncidentExist = $con->query($CheckIfIncidentExist);
                            if ($IncidentExist->num_rows > 0) {	$i = 0;
                        ?>
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th style='width:30%;'>Risk Indicator</th>
                                
                                <th>Threshold</th>
                                <th>Current Value </th>
                                <th>Trend </th>
                                <th>Status </th>
                                <th>Priority </th>
                                <!--<th>...</th>-->
                            </tr>
                            </thead>
                            
                            <tbody>
                            <?php 
                                while($item = $IncidentExist->fetch_assoc()){ $i++;
                        //         $editLink = 'edit-treatment?id='.$item['t_id'].'" data-toggle="tooltip" title="Edit Treatment" data-placement="right" class="action-icons btn btn-info btn-action mr-1"';
                        // 		$viewLink = '?id='.$item['t_id'].'" data-toggle="tooltip" title="View Treatment" data-placement="right" class="action-icons btn btn-primary btn-action mr-1"';
                        // 		$deleteLink = 'javascript:void(0);" data-placement="left" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="Treatment" data-id="'.$item["t_id"];
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                    <a href='?id=<?php echo $item['k_id']; ?>' class='bb'>
                                        <?php echo ucwords($item['indicator']); ?>
                                    </a>
                                </td>
                                <td><?php echo $item['threshold']; ?>%</td>
                                <td><?php echo $item['current']; ?>%</td>
                                
                                <td><?php echo _getTrend($item['trend']); ?></td>
                                <td><?php echo _getStatus($item['status']); ?></td>
                                <td><?php echo _getPriority($item['priority']); ?></td>
                                <!--<td>-->
                                <!--    <a href="<?php #echo $viewLink; ?>"><i class="fas fa-eye"></i></a>-->
                                <!--    <a href="<?php #echo $editLink; ?>"><i class="fas fa-edit"></i></a>-->
                                <!--    <a href="<?php #echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>-->
                                <!--</td>-->
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php }else{ ?>
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Key Risk Indicator Registered Yet,
                                <p><a href="new-kri" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New KRI</a></p>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    
                </div>
                <style>.main-footer{margin-top:0px;}</style>
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