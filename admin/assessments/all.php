<?php
    $file_dir = '../../';
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    // include_once("../rs/controller/auth.php");
    // include_once("../../layout/config.php");
    // include('../rs/model/assessment.php');
    // include_once("../rs/model/db.php");

    if(isset($_POST["create-assessment"])){
        $update_userid = $_SESSION["userid"];
        $update_type = $_REQUEST["type"];
        $update_team = $_REQUEST["team"];
        $update_task = $_REQUEST["task"];
        $update_description = $_REQUEST["description"];
        $update_owner = $_REQUEST["owner"];
        $update_date = $_REQUEST["date"];
        $update_assessor = $_REQUEST["assessor"];
        $update_approval = $_REQUEST["approval"]; 
    }

    // $assess = new assessment;
    // $db = new db;
	// $conn = $db->connect();
    // $num = $db->rowCount($conn, "as_assessment", "as_user", $_SESSION["userid"]);
    // $_REQUEST["start"] = 1;
    // $_REQUEST["length"] = 5;
	// $list = $assess->listAssessments($_REQUEST["start"], $_REQUEST["length"]);

	// $fulldata = array();
	// $data = array();

	// $fulldata["draw"] = $_REQUEST["draw"];
	// $fulldata["recordsTotal"] = $num;
	// $fulldata["recordsFiltered"] = $num;

    // foreach ($list as $item) {
	// 	$response = array();

	// 	$response["nr"] = $item["idassessment"];
	// 	$response["team"] = $item["as_team"];
	// 	$response["task"] = $item["as_task"];
	// 	$id = $item['as_type'];
	// 	$query = "SELECT ty_name FROM as_types WHERE idtype=" . $id;
	// 	$result = $conn->query($query);
	// 	if ($row = $result->fetch_assoc()) {
	// 		$response["type"] = $row["ty_name"];
	// 	}

	// 	$response["date"] = date("m/d/Y", strtotime($item["as_date"]));
	// 	$response["link"] = '<div style="text-align: center;">
	// 			<a class="btn btn-xs btn-info" title="View" href="assessment.php?id=' . $item["idassessment"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
	// 			<a class="btn btn-xs btn-success" title="Edit" href="editassessment.php?id=' . $item["idassessment"] . '"><i class="glyphicon glyphicon-pencil"></i></a>
	// 			<a class="btn btn-xs btn-danger" title="Delete" href="javascript:del(\'' . $item["idassessment"] . '\')"><i class="glyphicon glyphicon-remove"></i></a>
	// 			<a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="assessments.php?action=downloadxls&id=' . $item["idassessment"] . '"><i class="glyphicon glyphicon-download"></i></a>
	// 		</div>';
	// 	$data[] = array_values($response);
	// }

	// $fulldata["data"] = $data;
    // echo json_encode($fulldata);
    // class assessment {
    //     public function listAssessments($start, $end) {
        
    //         $db=new db;
    //         $conn=$db->connect();
    //         $query="SELECT * FROM as_assessment WHERE as_user=".$_SESSION["userid"]." ORDER BY idassessment DESC, as_date DESC LIMIT " . $start . ", " . $end;
    //         if ($result=$conn->query($query)) {	
    //             $data=array();
    //             while ($row=$result->fetch_assoc()) {
    //                 $data[]=$row;
    //             }
                
    //             $response=$data;
    //         } else {
    //             $response=false;	
    //         }
    //         $db->disconnect($conn);
    //         return $response;
        
    //     }

    //     public function rowCount($conn, $table, $cond, $value) {
	
    //         if (trim($cond)<>"") {
    //             $query="SELECT * FROM " . $table . " WHERE " . $cond . "=" . $value;
    //         } else {
    //             $query="SELECT * FROM " . $table;
    //         }
    //         if ($result=$conn->query($query)) {
    //             return $result->num_rows;
    //         } else {
    //             return false;	
    //         }
        
            
    //     }
    // }
    // if (isset($_GET['lim'])) {
    //     $query="SELECT * FROM as_assessment WHERE as_user=".$_SESSION["userid"]." ORDER BY idassessment DESC, as_date DESC LIMIT " . $start . ", " . $end;
    // } else {
    //     $query="SELECT * FROM as_assessment WHERE as_user=".$_SESSION["userid"]." ORDER BY idassessment DESC, as_date DESC";
    // }
    
    // if ($result=$con->query($query)) {	
    //     $data=array();
    //     while ($row=$result->fetch_assoc()) {
    //         $data[]=$row;
    //     }  
    //     $response=$data;
    // } else {
    //     $response=false;	
    // }

    // $db = new db;
	// $conn = $db->connect();
	// $num = $db->rowCount($conn, "as_assessment", "as_user", $_SESSION["userid"]);

	// $list = $assess->listAssessments($_REQUEST["start"], $_REQUEST["length"]);

	// $fulldata = array();
	// $data = array();

	// $fulldata["draw"] = $_REQUEST["draw"];
	// $fulldata["recordsTotal"] = $num;
	// $fulldata["recordsFiltered"] = $num;

	// foreach ($list as $item) {
	// 	$response = array();

	// 	$response["nr"] = $item["idassessment"];
	// 	$response["team"] = $item["as_team"];
	// 	$response["task"] = $item["as_task"];
	// 	$id = $item['as_type'];
	// 	$query = "SELECT ty_name FROM as_types WHERE idtype=" . $id;
	// 	$result = $conn->query($query);
	// 	if ($row = $result->fetch_assoc()) {
	// 		$response["type"] = $row["ty_name"];
	// 	}

	// 	$response["date"] = date("m/d/Y", strtotime($item["as_date"]));
	// 	$response["link"] = '<div style="text-align: center;">
	// 			<a class="btn btn-xs btn-info" title="View" href="assessment.php?id=' . $item["idassessment"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
	// 			<a class="btn btn-xs btn-success" title="Edit" href="editassessment.php?id=' . $item["idassessment"] . '"><i class="glyphicon glyphicon-pencil"></i></a>
	// 			<a class="btn btn-xs btn-danger" title="Delete" href="javascript:del(\'' . $item["idassessment"] . '\')"><i class="glyphicon glyphicon-remove"></i></a>
	// 			<a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="assessments.php?action=downloadxls&id=' . $item["idassessment"] . '"><i class="glyphicon glyphicon-download"></i></a>
	// 		</div>';
	// 	$data[] = array_values($response);
	// }

	// $fulldata["data"] = $data;

	// echo json_encode($fulldata);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Otika - Admin Dashboard Template</title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
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
                        <h4 class="d-inline">My Risk Assessments</h4>
                        <a class="card-header-action" href="new-assessment.php"><button class="btn btn-primary"><i class="fas fa-plus"></i> New Assesment</button></a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Team or Organisation</th>
                                    <th>Task or Process</th>
                                    <th>Type of Assessment</th>
                                    <th>Date</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/js/js/jquery.min.js"></script> 
    <script src="<?php echo $file_dir; ?>assets/js/js/bootstrap.min.js"></script> 
    <script src="<?php echo $file_dir; ?>assets/js/js/jquery-ui.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/js/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/js/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/js/dialog/js/bootstrap-dialog.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/js/time/jquery.timepicker.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/js/scripts.js"></script>

    <!-- Datatable JS -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    <script>
		$(document).ready(function (e) {
            
			var table = $('#table').dataTable({
				"processing": true,
				"serverSide": true,
				"stateSave": true,
				"bFilter": false,
				"ordering": false,
				"columns": [
					{ "width": "20" },
					{},
					{ "width": "35%" },
					{ "width": "25" },
					{ "width": "55" },
					{ "width": "100" }
				],
				"ajax": "../rs/controller/assessment.php?action=listassess"
			});
		});

		function del(id) {

			BootstrapDialog.show({
				title: "<i class='glyphicon glyphicon-trash'></i> Warning",
				type: BootstrapDialog.TYPE_DANGER,
				message: 'Are you sure you want to delete this assessment?',
				buttons: [{
					label: 'Cancel',
					action: function (dialogItself) {
						dialogItself.close();
					},

				}, {
					label: 'Delete',
					cssClass: 'btn-danger',
					action: function (dialogItself) {
						res = $.ajax({ type: "GET", url: "../rs/controller/assessment.php?action=deleteassess&id=" + id, async: false })
						$('#table').DataTable().ajax.reload();
						dialogItself.close();
					}
				}]
			});//end dialog	
		}
	</script>
</body>
</html>