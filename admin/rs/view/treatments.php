<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/treatment.php');
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
$apply = new treatment();

if(isset($_GET['status']) && ($_GET['status']!="")){
  $status=$_GET['status'];
  if($status==0){
    $msg='No due date for treatment to send users';
    $msgClass = "alert-danger";
  }elseif($status==1){
    $msg='Mail has been sent to all treatement  users who has due date';
    $msgClass = "alert-danger";

  }else{
    $msg='';
    $msgClass=''; 
  }
}

$db = new db();
$conn = $db->connect();
$currentUser = $_SESSION["userid"];

$sql = "SELECT role FROM users  WHERE iduser = '$currentUser'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentUserRole = $row['role'];

}




if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "downloadxls") {

  $id = $_REQUEST['id'];

  $objPHPExcel = new PHPExcel();
  $objPHPExcel->getProperties()->setCreator("RiskSafe")
    ->setLastModifiedBy("RiskSafe")
    ->setTitle("Treatments Summary Report")
    ->setSubject("Treatments Summary Report")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");
  $row = 1;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Risk Safe')
    ->setCellValue('B' . $row, 'Treatments Summary Report');

  $row = 3;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Treatment')
    ->setCellValue('B' . $row, 'Cost/Benefits')
 
    ->setCellValue('C' . $row, 'Owner')
    ->setCellValue('D' . $row, 'Start date')
    ->setCellValue('E' . $row, 'Due date')
    ->setCellValue('F' . $row, 'Status')
    ->setCellValue('G' . $row, 'Treatment User')
    ->setCellValue('H' . $row, 'Treatment Team')
    ->setCellValue('I' . $row, 'Treatment Assessor')
    ->setCellValue('J' . $row, 'Treatment Progress');

  $row++;
  $treatmentdata = $apply->getTreatment($id);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, $treatmentdata['tre_treatment'])
    ->setCellValue('B' . $row, $treatmentdata['tre_cost_ben'])
    ->setCellValue('C' . $row, $treatmentdata['tre_owner'])
    ->setCellValue('D' . $row, $treatmentdata['tre_start'])
    ->setCellValue('E' . $row, $treatmentdata['tre_due'])
    ->setCellValue('F' . $row, $treatmentdata['tre_status'])
    ->setCellValue('G' . $row, $treatmentdata['tre_user'])
    ->setCellValue('H' . $row, $treatmentdata['tre_team'])
    ->setCellValue('I' . $row, $treatmentdata['tre_assessor'])
    ->setCellValue('J' . $row, $treatmentdata['tre_progress']);

  $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getFont()->setBold(true);

  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);



  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Test Summary Report');

  // Redirect output to a client’s web browser (Excel2007)
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Treatments Summary Report.xlsx"');
  header('Cache-Control: max-age=0');
  // If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');

  // If you're serving to IE over SSL, then the following may be needed
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
  header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header('Pragma: public'); // HTTP/1.0

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');
  exit;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once("header.php"); ?>
</head>
<style>
  .background{
    height: auto;
			background: rgb(0, 46, 76);
			background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%);
			display: flex;
      margin-bottom: 1rem;
			justify-content: flex-end;
			align-items: center;
			padding: 2rem 2rem;
			border-bottom-left-radius: 2rem;
			border-top-right-radius: 2rem;
			
  }
  .heading {
			display: flex;
			align-items: center;
		}
    #btn_add{
      border: none;
      outline: none;
    }
  </style>
<body>
  <!-- header -->
  <div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span
            class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;">
          <?php echo APP_TITLE; ?>
        </a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"
              style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span
                class="caret"></span></a>
            <ul id="g-account-menu" class="dropdown-menu" role="menu">
              <?php include_once("menu_top.php"); ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- /container -->
  </div>
  <!-- /Header -->

  <!-- Main -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-sm-12">
        <!-- Left column -->
        <?php include_once("menu.php"); ?>
        <!-- /col-3 -->
      </div>
   
      <div class="col-lg-9 col-md-12">
        <h1 class="page-header">Treatments</h1>
        <div class="alert <?php if (isset($msgClass)) echo $msgClass;?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
    	<?php if (isset($msg)) echo $msg;?>
  	</div>
        <div class="col-lg-12 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="clearfix mb20 background">
                <div class="heading">
                <button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ New Treatment</button>
                </div>

            
              </div>
              <?php
      if($currentUserRole=='superadmin'){ 
            ?>
              <div>
              <button type="button" class="btn btn-md btn-info pull-right" id="sendnotify">Send Notification Today Treatment Due date </button>
              </div>
              <?php } ?>
              <table class="table table-striped table-bordered table-hover" id="table" style="width:100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Treatment</th>
                    <th>Cost/Benefits</th>
                    <th>Progress Update</th>
                    <th>Owner</th>
                    <th>Start date</th>
                    <th>Due date</th>
                    <!-- <th>Days left</th> -->
                    <th>Status</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="9">Loading...</td>
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/col-span-9-->
  </div>
  </div>

  <!-- /Main -->

  <?php include_once("footer.php"); ?>
  <script>
    $(document).ready(function (e) {

      var table = $('#table').dataTable({
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "bFilter": false,
        "ordering": false,
        "columns": [
          { "width": "25" },
          {},
          { "width": "65" },
          
          { "width": "65" },
          { "width": "65" },
          { "width": "65" },
          { "width": "65" },
          { "width": "60" },
          // { "width": "65" },
          { "width": "100" }
        ],
        "ajax": "../controller/treatment.php?action=list"
      });

      $("#btn_add").click(function (e) {
        $(location).attr("href", "../view/treatment.php?action=add");
      });


    });

    function del(id) {
      BootstrapDialog.show({
        message: 'Are you sure you want to delete this entry?',
        buttons: [{
          label: 'No, go back',
          action: function (dialogItself) {
            dialogItself.close();
          },

        }, {
          label: 'Yes, delete',
          action: function (dialogItself) {
            //kod za booking
            res = $.ajax({ type: "GET", url: "../controller/treatment.php?action=delete&id=" + id, async: false })
            $('#table').DataTable().ajax.reload();
            dialogItself.close();
          }
        }]
      });//end dialog	
    }


    $("#sendnotify").click(function (e) {
     
        $(location).attr("href", "../controller/treatment.php?action=treatmentmatchdate");
      });

  </script>
</body>

</html>