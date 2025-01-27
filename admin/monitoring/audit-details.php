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
    include '../ajax/audits.php';
    
    function __getControl($s, $type, $con, $company_id){
        
        if($type == 'custom'){
            $query = "SELECT * FROM as_customcontrols WHERE c_id = '$company_id' AND control_id = '$s' LIMIT 1";
            $result=$con->query($query);
            if ($result->num_rows > 0) {
                $info = $result->fetch_assoc();
                $response = ucwords($info['title']);
            }else{
                $response = 'Error 402: Control Not Found!!';	    
            }
        }else if($type == 'recommended'){
            $query = "SELECT * FROM as_controls WHERE id = '$s' LIMIT 1";
		    $result = $con->query($query);
            if ($result->num_rows > 0) {
                $info = $result->fetch_assoc();
                $response = ucwords($info['control_name']);
            }else{
                $response = 'Error 402: Control Not Found!!';	    
            }
        }else if($type === 'monitoring'){
	        $query = "SELECT * FROM as_monitoring WHERE m_id = '$s'";
    		$result = $con->query($query);
    		
    		if ($result->num_rows > 0) {
    		    $row=$result->fetch_assoc();
    		    
        		$response = ucwords($row['title']);  
    		}else{
    		    $response = 'Error 402: Control Not Found!!';
    		}
	    }else{
            $response = 'Error 402: Control Not Found!!';
        }
		
		return $response;
	}

    if (isset($_POST['delete-data'])){
        $type = sanitizePlus($_POST['data-type']);
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            $query = "DELETE FROM as_auditcriteria WHERE cri_id = '$id'";
            $dataDeleted = $con->query($query);
                            
            if ($dataDeleted) {
                array_push($message, 'Criteria Deleted Successfully!!');
            }else{
                array_push($message, 'Error 502: Error Deleting Data!!');
            }
        }
        
    }

    if (isset($_POST['update-effectiveness'])){
        $id = sanitizePlus($_POST['id']);
        $effect = sanitizePlus($_POST['effect']);
        $observation = sanitizePlus($_POST['observation']);
        $rootcause = sanitizePlus($_POST['rootcause']);
        $treatment = sanitizePlus($_POST['treatment']);
        $frequency = sanitizePlus($_POST['frequency']);

            # code...
            $query = "UPDATE as_auditcontrols SET con_effect = '$effect', con_observation = '$observation', con_rootcause = '$rootcause', con_treatment = '$treatment', con_frequency='$frequency' WHERE aud_id = '$id' AND c_id = '$company_id'";
            $sent = $con->query($query);
            if ($sent) {
                array_push($message, 'Control Effectiveness Updated Successfully!!');
            } else {
                array_push($message, 'Error 502: Error Updating Data!!');
            }
        // if (!$effect || $effect == null || $effect == '' || !$observation || $observation == null || $observation == '' || !$rootcause || $rootcause == null || $rootcause == '' || !$treatment || $treatment == null || $treatment == '' || !$frequency || $frequency == null || $frequency == '') {
        //     array_push($message, 'Error While Updating Data: Missing Parameters!!');
        // } else {
        // }
        
    }

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $aud_Id = sanitizePlus($_GET['id']);
        $toEdit = true;
        #confirm assessment
        $CheckIfAuditExist = "SELECT * FROM as_auditcontrols WHERE aud_id = '$aud_Id'";
        $AuditExist = $con->query($CheckIfAuditExist);
        if ($AuditExist->num_rows > 0) {	
            $audit_exist = true;	
			$audit = $AuditExist->fetch_assoc();
			
			$control_type = $audit['control_type'];
            if($control_type == 'null' || $control_type == null){
                $control_type = 'custom';
            }
        }else{
            $audit_exist = false;
        }
    }else{
        $toEdit = false;
    }  
		
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Audit Details | <?php echo $siteEndTitle ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
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
                    <?php if($toEdit == true){ ?>
                        <?php if($audit_exist == false){ ?>
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Data Error!!</h3>
                                 Audit Of Control Doesn't Exist,
                                 <p><a href="/help#data-error" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Audit</a></p>
                                </div>
                        </div>
                        <?php }else{ ?>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header" style='display:flex;justify-content:space-between;'>
                                <h3 class="d-inline">Audit Review:</h3>
                                <div class='header-a'>
                                    <a class="btn btn-primary btn-icon icon-left" href="audits" style='margin-right:5px;'><i class="fas fa-arrow-left"></i> Back</a>
                                    <a href='edit-audit?id=<?php echo $audit['aud_id']; ?>' class="btn btn-md btn-outline-secondary">Edit Audit</a>
                                </div>
                                
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12">
                                        <label>Company :</label>
                                        <div class="description-text"><?php echo $audit['con_company']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Industry Type :</label>
                                        <div class="description-text"><?php echo $audit['con_industry']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Business Unit or Team :</label>
                                        <div class="description-text"><?php echo $audit['con_team']; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Process, Task or Activity :</label>
                                        <div class="description-text"><?php echo $audit['con_task']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Assessor :</label>
                                        <div class="description-text"><?php echo $audit['con_assessor']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-3">
                                        <label>Issued On :</label>
                                        <div class="description-text"><?php echo date("m-d-Y", strtotime($audit["con_date"])); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-3">
                                        <label>Time :</label>
                                        <div class="description-text"><?php echo $audit['con_time']; ?></div>
                                    </div>
                                     <div class="user-description col-12">
                                        <label>Site :</label>
                                        <div class="description-text"><?php echo $audit['con_site']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Street :</label>
                                        <div class="description-text"><?php echo $audit['con_street']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Building :</label>
                                        <div class="description-text"><?php echo $audit['con_building']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-5">
                                        <label>Country :</label>
                                        <div class="description-text"><?php echo $audit['con_country']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-5">
                                        <label>State :</label>
                                        <div class="description-text"><?php echo $audit['con_state']; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-2">
                                        <label>Zip Code :</label>
                                        <div class="description-text"><?php echo $audit['con_zipcode']; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-header">
                                <h3 class="d-inline">Audit Criteria Questions</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="add-criteria?id=<?php echo $aud_Id; ?>"><i class="fas fa-plus"></i> Add Criteria</a>
                            </div>
                            
                            <div class="card-body">
                                <?php
                                    $list_one = listCriteria($aud_Id , 0 , 20, $con);
                                    $details_count = $list_one;
                                    if($list_one !== false){
                                    if(count($details_count) <= 1){$details[] = $list_one;}else{$details = $list_one;}
                                    
                                ?>
                                <?php if($on_mobile == false) { ?>
                                <?php if ($details === 'a:0:{}') { #empty data?> 
                                <div style="width:100%;min-height:200px;display:flex;justify-content:center;align-items:center;">
                                       <div style="text-align: center;"> 
                                            <h3>Empty Data!!!</h3>
                                            No Criteria Question Added Yet,
                                            <p><a href="add-criteria?id=<?php echo $aud_Id; ?>" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Add New Criteria</a></p>
                                        </div>
                                    </div>
                                <?php }else{ $arrcount = count($details); ?>
                                <table class="table table-striped table-bordered table-hover" id="table">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Question</th>
                                        <th>Expected Outcome</th>
                                        <th>Outcome</th>
                                        <th>...</th>
                                    </tr>
                                    <?php 
                                        $i = 0;
                                        foreach ($list_one as $item) { $i++; #for ($i=0; $i < $arrcount; $i++) {} 
                                        switch ($item["cri_outcome"]) {
                                            case 0:
                                                $item["cri_outcome"]="N/A";
                                                break;
                                            case 1:
                                                $item["cri_outcome"]="Pass";
                                                break;
                                            case 2:
                                                $item["cri_outcome"]="Fail";
                                                break;
                                            default:
                                                $item["cri_outcome"]="Error!";
                                                break;
                                        }
                                        $editLink = 'edit-criteria?id='.$item["cri_id"].'" data-toggle="tooltip" title="Edit Criteria" data-placement="right"';
                                        $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="Audit Criteria" data-id="'.$item["cri_id"];
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo ucwords($item["cri_question"]); ?></td>
                                        <td><?php echo ucwords($item["cri_expected"]); ?></td>
                                        <td><?php echo ucwords($item["cri_outcome"]); ?></td>
                                        <td>
                                            <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                            <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php }} if ($details !== 'a:0:{}') { echo '</table>'; } #closing tag for table ?>
                                <?php }}else{ #empty data ?>
                                <div style="width:100%;min-height:200px;display:flex;justify-content:center;align-items:center;">
                                       <div style="text-align: center;"> 
                                            <h3>Empty Data!</h3>
                                            No Criteria Question Added Yet,
                                            <p><a href="add-criteria?id=<?php echo $aud_Id; ?>" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Add New Criteria</a></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                <?php if($on_mobile == true) { ?>
                                <?php if($details == []){ ?>
                                <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                                      <div style="text-align: center;"> 
                                        <h3>Empty Data!!</h3>
                                        No Compliance Created Yet,
                                        <p><a href="new-compliance" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Compliance</a></p>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <table class="risk-desc">
                                    <?php 
                                        $i=0; foreach ($list_one as $item) { $i++;
                                        switch ($item["cri_outcome"]) {
                                            case 0:
                                                $item["cri_outcome"]="N/A";
                                                break;
                                            case 1:
                                                $item["cri_outcome"]="Pass";
                                                break;
                                            case 2:
                                                $item["cri_outcome"]="Fail";
                                                break;
                                            default:
                                                $item["cri_outcome"]="Error!";
                                                break;
                                        }
                                        $editLink = 'edit-criteria?id='.$item["cri_id"].'" data-toggle="tooltip" title="Edit Criteria" data-placement="right"';
                                        $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="Audit Criteria" data-id="'.$item["cri_id"];
                                    ?>
                                    <tr>
                                        <th>S/N</th>
                                        <td><?php echo $i; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Question</th>
                                        <td><?php echo ucwords($item["cri_question"]); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Expected Outcome</th>
                                        <td><?php echo ucwords($item["cri_expected"]); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Outcome</th>
                                        <td><?php echo ucwords($item["cri_outcome"]); ?></td>
                                    </tr>
                                    <tr>
                                        <th>...</th>
                                        <td>
                                            <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                            <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
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
                            
                            <div class='card-header'><h3 class="d-inline">Audited Control Details:</h3></div>
                            <div class='card-body'>
                                <div class="row section-rows customs">
                                    <div class="user-description col-12">
                                        <label>Control:</label>
                                        <div class="description-text"><?php echo __getControl($audit['con_control'], $control_type, $con, $company_id) ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Control Rationale:</label>
                                        <div class="description-text"><?php echo nl2br($audit['con_observation']); ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Control Root Cause:</label>
                                        <div class="description-text"><?php echo nl2br($audit['con_rootcause']); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Control Effectiveness:</label>
                                        <div class="description-text"><?php echo getEffectiveness($audit['con_effect']); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Frequency Of Application (FoA):</label>
                                        <div class="description-text"><?php echo get_Frequency($audit['con_frequency']); ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Next Audit:</label>
                                        <div class="description-text"><?php echo nextDate($audit["con_date"], $audit["con_frequency"], 'm-d-Y'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class='card-footer'>
                                <div class="form-group text-right">
                                    <a href='edit-audit?id=<?php echo $aud_Id; ?>' class="btn btn-md btn-icon icon-left btn-primary"><i class='fas fa-pen'></i> Edit Audit Data</a>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>

                                </div>  
                            </div>
                        </div>
                        <?php } ?>
                    <?php }else{ ?>
                    <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Parameters Error!!</h3>
                                 No Audit Of Control Selected!!
                                 <p><a href="audits" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> View All Audits</a></p>
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
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .main-footer {
            margin-top: 0px;
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