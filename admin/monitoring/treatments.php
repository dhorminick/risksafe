<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/monitoring/treatments');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    include '../ajax/treatment.php';
    
    if (isset($_POST['delete-data'])){
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            $query="DELETE  FROM as_treatments WHERE t_id = '$id' AND c_id = '$company_id'";
                    $dataDeleted = $con->query($query);
                            
                    if ($dataDeleted) {
                        array_push($message, 'Treatment Deleted Successfully!!');
                    }else{
                        array_push($message, 'Error 502: Error Deleting Data!!');
                    }
        }
        
    }
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $pagetitle = 'Treatments';
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM as_treatments WHERE t_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
            $info = $IncidentExist->fetch_assoc();
		}else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
        $pagetitle = 'My Treatments';
    }
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
                                <h3 class="d-inline">Treatment Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="treatments"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12">
                                        <label>Treatment :</label>
                                        <div class="description-text"><?php echo $info['tre_treatment']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Cost / Benefits :</label>
                                        <div class="description-text"><?php echo $info['tre_cost_ben']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Owner :</label>
                                        <div class="description-text"><?php echo $info['tre_owner']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Start date :</label>
                                        <div class="description-text"><?php echo date("m/d/Y", strtotime($info["tre_start"])); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Due date :</label>
                                        <div class="description-text"><?php echo date("m/d/Y", strtotime($info["tre_due"])); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Status :</label>
                                        <div class="description-text"><?php echo $status; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Treatment Team :</label>
                                        <div class="description-text"><?php echo $info['tre_team']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Treatment Assessor :</label>
                                        <div class="description-text"><?php echo $info['tre_assessor']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Treatment Progress :</label>
                                        <div class="description-text"><?php echo $info['tre_progress']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Treatment Doesn't Exist!!,
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
                        <h3 class="d-inline">My Treatments</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-treatment"><i class="fas fa-plus"></i> New Treatment</a>
                    </div>
                    <div class='card-body'>
                        <?php
                            $list_one = listTreatments(0, 20, $company_id, $con);
                            $details_count = $list_one;
                            if(count($details_count) <= 1){$details[] = $list_one;}else{$details = $list_one;}
                            
                            if($list_one !== false){
                        ?>
                        <?php if($on_mobile == false) { ?>
                        <?php if ($details === 'a:0:{}') { #empty data?> 
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Treatment Created Yet,
                                <p><a href="new-treatment" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Treatment</a></p>
                            </div>
                        </div>
                        <?php }else{ $arrcount = count($details); ?>
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <tr>
                                <th>S/N</th>
                                <th>Treatment</th>
                                <th>Cost / Benefits</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status </th>
                                <th>...</th>
                            </tr>
                        <?php 
                            $i = 0;
                            foreach ($list_one as $item) { $i++;
                            
                            //STATUS
                    		switch ($item["tre_status"]) {
                    			case 1:
                    				$status = "In progress";
                    				break;
                    			case 2:
                    				$status = "Completed";
                    				break;
                    			case 3:
                    				$status = "Cancelled";
                    				break;
                    		}
                    		
                    		$editLink = 'edit-treatment?id='.$item['t_id'].'" data-toggle="tooltip" title="Edit Treatment" data-placement="right" class="action-icons btn btn-info btn-action mr-1"';
                    		$viewLink = '?id='.$item['t_id'].'" data-toggle="tooltip" title="View Treatment" data-placement="right" class="action-icons btn btn-primary btn-action mr-1"';
                    		$deleteLink = 'javascript:void(0);" data-placement="left" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="Treatment" data-id="'.$item["t_id"];
                    		$downloadLink = 'download?download=treatment&id='.$item["t_id"].'" data-toggle="tooltip" title="Download Treatment" data-placement="left"  class="action-icons btn btn-success btn-action mr-1';   
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo ucwords($item['tre_treatment']); ?></td>
                            <td><?php echo ucwords($item['tre_cost_ben']); ?></td>
                            <td><?php echo date("m/d/Y", strtotime($item["tre_start"])); ?></td>
                            <td><?php echo date("m/d/Y", strtotime($item["tre_due"])); ?></td>
                            <td><?php echo $status; ?></td>
                            <td>
                                <a href="<?php echo $viewLink; ?>"><i class="fas fa-eye"></i></a>
                                <a href="<?php echo $editLink; ?>"><i class="fas fa-edit"></i></a>
                                <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                <a href="<?php echo $downloadLink; ?>"><i class="fas fa-download"></i></a>
                            </td>
                        </tr>
                        <?php }} if ($details !== 'a:0:{}') { echo '</table>'; } #closing tag for table ?>
                        <?php }}else{ #empty data ?>
                            <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                               <div style="text-align: center;"> 
                                    <h3>Empty Data!!</h3>
                                    No Risk Treatment Created Yet,
                                    <p><a href="new-treatment" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Treatment</a></p>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if($on_mobile == true) { ?>
                        <?php if($details == []){ ?>
                        <?php }else{ ?>
                        <table class="risk-desc">
                            <?php 
                                $i = 0;
                                foreach ($list_one as $item) { $i++;
                                
                                //STATUS
                        		switch ($item["tre_status"]) {
                        			case 1:
                        				$status = "In progress";
                        				break;
                        			case 2:
                        				$status = "Completed";
                        				break;
                        			case 3:
                        				$status = "Cancelled";
                        				break;
                        		}
                        		
                        		$editLink = 'edit-treatment?id='.$item['t_id'].'" data-toggle="tooltip" title="Edit Treatment" data-placement="right" class="action-icons btn btn-info btn-action mr-1"';
                        		$viewLink = '?id='.$item['t_id'].'" data-toggle="tooltip" title="View Treatment" data-placement="right" class="action-icons btn btn-primary btn-action mr-1"';
                        		$deleteLink = 'javascript:void(0);" data-placement="left" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="Treatment" data-id="'.$item["t_id"];
                        		$downloadLink = 'download?download=treatment&id='.$item["t_id"].'" data-toggle="tooltip" title="Download Treatment" data-placement="left"  class="action-icons btn btn-success btn-action mr-1';   
                            ?>
                            <tr>
                                <th>S/N</th>
                                <td><?php echo $i; ?></td>
                            </tr>
                            <tr>
                                <th>Treatment</th>
                                <td><?php echo ucwords($item['tre_treatment']); ?></td>
                            </tr>
                            <tr>
                                <th>Cost / Benefits</th>
                                <td><?php echo ucwords($item['tre_cost_ben']); ?></td>
                            </tr>
                            <tr>
                                <th>Start Date</th>
                                <td><?php echo date("m/d/Y", strtotime($item["tre_start"])); ?></td>
                            </tr>
                            <tr>
                                <th>Due Date</th>
                                <td><?php echo date("m/d/Y", strtotime($item["tre_due"])); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo $status; ?></td>
                            </tr>
                            <tr>
                                <th>Action</th>
                                <td>
                                    <a href="<?php echo $viewLink; ?>"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo $editLink; ?>"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                    <a href="<?php echo $downloadLink; ?>"><i class="fas fa-download"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <th style="border:none !important;">&nbsp;</th>
                                <td style="border:none !important;">&nbsp;</td>
                            </tr>
                            <?php } ?>
                        </table>
                        <?php }} ?>
                            
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
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