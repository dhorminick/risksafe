<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/applicable.php');
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
$apply = new applicable();

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
    ->setTitle("Applicable Policy Summary Report")
    ->setSubject("Applicable Policy Summary Report")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");
  $row = 1;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Risk Safe')
    ->setCellValue('B' . $row, 'Applicable Policy Summary Report');

  $row = 3;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Policy Title')
    ->setCellValue('B' . $row, 'Policy Number')

    ->setCellValue('C' . $row, 'Policy Description')
    ->setCellValue('D' . $row, 'Policy Effective Date')
    ->setCellValue('E' . $row, 'Policy Review Date')
    ->setCellValue('F' . $row, 'Applicability')
    ->setCellValue('G' . $row, 'Policy Requirements')
    ->setCellValue('H' . $row, 'Compliance Responsibility')
    ->setCellValue('I' . $row, 'Related Documents')
    ->setCellValue('J' . $row, 'Policy Approval')
    ->setCellValue('K' . $row, 'Policy Review Revision History')
    ->setCellValue('L' . $row, 'Policy Acknowledgment');
  $row++;
  $applypolicy = $apply->getApplicable($id);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, $applypolicy['PolicyTitle'])
    ->setCellValue('B' . $row, $applypolicy['PolicyNumber'])
    ->setCellValue('C' . $row, $applypolicy['PolicyDescription'])
    ->setCellValue('D' . $row, $applypolicy['PolicyEffectiveDate'])
    ->setCellValue('E' . $row, $applypolicy['PolicyReviewDate'])
    ->setCellValue('F' . $row, $applypolicy['Applicability'])
    ->setCellValue('G' . $row, $applypolicy['PolicyRequirements'])
    ->setCellValue('H' . $row, $applypolicy['ComplianceResponsibility'])
    ->setCellValue('I' . $row, $applypolicy['RelatedDocuments'])
    ->setCellValue('J' . $row, $applypolicy['PolicyApproval'])
    ->setCellValue('K' . $row, $applypolicy['PolicyReviewRevisionHistory'])
    ->setCellValue('L' . $row, $applypolicy['PolicyAcknowledgment']);

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
  $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);


  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Test Summary Report');

  // Redirect output to a client’s web browser (Excel2007)
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Applicable Policy Summary Report.xlsx"');
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
        <h1 class="page-header">Applicable Policy</h1>
        <div class="alert <?php if (isset($msgClass)) echo $msgClass;?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
    	<?php if (isset($msg)) echo $msg;?>
  	</div>
        <div class="col-lg-12 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="clearfix mb20 background">
                <div class="heading">
                <button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ New Applicable
                  Policy</button>
                </div>
               
              </div>

              <?php
      if($currentUserRole=='superadmin'){ 
            ?>
              <div>
              <button type="button" class="btn btn-md btn-info pull-right" id="sendnotify">Send Notification Today Policy Due date </button>
              </div>
              <?php } ?>
              <table class="table table-striped table-bordered table-hover" id="table" style="width:100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Policy Title</th>
                    <th>Policy Number</th>
                    <th>Policy Description</th>
                    <th>Policy Effective Date</th>
                    <th>Policy Review Date</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="7">Loading...</td>
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
          { "width": "25" },
          { "width": "25" },
          { "width": "25" },
          { "width": "25" },
          { "width": "120" }
        ],
        "ajax": "../controller/applicable.php?action=list"
      });

      $("#btn_add").click(function (e) {
        $(location).attr("href", "../view/applicable.php?action=add");
      });
      var lastColumn = table.column(-1).nodes();

      $(lastColumn).css("display", "flex"); // 

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
            res = $.ajax({ type: "GET", url: "../controller/applicable.php?action=delete&id=" + id, async: false })
            $('#table').DataTable().ajax.reload();
            dialogItself.close();
          }
        }]
      });//end dialog	
    }

    $("#sendnotify").click(function (e) {
     
     $(location).attr("href", "../controller/applicable.php?action=policyreviewdate");
   });





  </script>
</body>

</html>