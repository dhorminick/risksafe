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
    include '../../layout/admin_config.php';
    include '../ajax/assessment.php';
    
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Anti Money Laundering Assessments | <?php echo $siteEndTitle ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                        <h3 class="d-inline">My AMLs</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="new-assessment?type=aml"><i class="fas fa-plus"></i> New AML</a>
                    </div>
                    <div class="card-body">
                        <?php
                            $list_one = listAssessmentsOfAml(0, 20, $con, $company_id);
                            $details_count = $list_one;
                            if($list_one !== false){
                            if(count($details_count) <= 1){$details[] = $list_one;}else{$details = $list_one;}
                            foreach ($list_one as $item) {}
                            
                            $id = $item['as_type'];
                        	$query = "SELECT ty_name FROM as_types WHERE idtype='$id'";
                        	$result = $con->query($query);
                        	if ($row = $result->fetch_assoc()) {
                        		$response["type"] = $row["ty_name"];
                        	}
                        	$as_HasValue = $item["has_values"];
                            if($as_HasValue == 'true'){
                                $editLink = "edit-assessment?id=".$item["as_id"];
                            }else{
                                $editLink = "add-assessment-details?id=".$item["as_id"];
                            }
        
                        ?>
                        <?php if($on_mobile == false) { ?>
                        <?php if ($details === 'a:0:{}') { #empty data?> 
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Anti-Money Laundering Assessment Created Yet,
                                <p><a href="new-assessment?type=aml" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New AML</a></p>
                            </div>
                        </div>
                        <?php }else{ $arrcount = count($details); ?>
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <tr>
                                <th>S/N</th>
                                <th>Team or Organisation</th>
                                <th>Task or Process</th>
                                <th>Type of Assessment </th>
                                <th>Date </th>
                                <th>...</th>
                            </tr>
                        <?php for ($i=0; $i < $arrcount; $i++) {   ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo ucwords($item['as_team']); ?></td>
                            <td><?php echo ucwords($item['as_task']); ?></td>
                            <td><?php echo ucwords($row["ty_name"]); ?></td>
                            <td><?php echo date("m/d/Y", strtotime($item["as_date"])); ?></td>
                            <td>
                                <a href="assessment-details?id=<?php echo $item["as_id"]; ?>" data-toggle="tooltip" title="View Assessment" data-placement="right" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                <a href="<?php echo $editLink; ?>" data-toggle="tooltip" title="Edit Assessment" data-placement="right"  class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0);" data-placement="left"  class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="assessment" data-id="<?php echo $item["as_id"]; ?>"><i class="fas fa-trash-alt"></i></a>
                                <a href="download?download=xls&id=<?php echo $item["as_id"]; ?>" data-toggle="tooltip" title="Download Assessment" data-placement="left"  class="action-icons btn btn-success btn-action mr-1"><i class="fas fa-download"></i></a>
                            </td>
                        </tr>
                        <?php }} if ($details !== 'a:0:{}') { echo '</table>'; } #closing tag for table ?>
                        <?php }}else{ #empty data ?>
                            <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                               <div style="text-align: center;"> 
                                    <h3>Empty Data!!</h3>
                                    No Anti-Money Laundering Assessment Created Yet,
                                    <p><a href="new-assessment?type=aml" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New AML</a></p>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if($on_mobile == true) { ?>
                        <?php if($details == []){ ?>
                        <?php }else{ ?>
                        <table class="risk-desc">
                            <?php for ($i=0; $i < $arrcount; $i++) { ?>
                            <tr>
                                <th>S/N</th>
                                <td><?php echo $i+1; ?></td>
                            </tr>
                            <tr>
                                <th>Team or Organisation</th>
                                <td><?php echo ucwords($item['as_team']); ?></td>
                            </tr>
                            <tr>
                                <th>Task or Process</th>
                                <td><?php echo ucwords($item['as_task']); ?></td>
                            </tr>
                            <tr>
                                <th>Type of Assessment</th>
                                <td><?php echo ucwords($row["ty_name"]); ?></td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td><?php echo date("m/d/Y", strtotime($item["as_date"])); ?></td>
                            </tr>
                            <tr>
                                <th>Action</th>
                                <td>
                                    <a href="assessment-details?id=<?php echo $item["as_id"]; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="assessment" data-id="<?php echo $item["as_id"]; ?>"><i class="fas fa-trash-alt"></i></a>
                                    <a href="download?download=xls&id=<?php echo $item["as_id"]; ?>" class="action-icons btn btn-success btn-action mr-1"><i class="fas fa-download"></i></a>
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