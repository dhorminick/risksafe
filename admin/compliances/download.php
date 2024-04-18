<?php
    session_start();
    $file_dir = '../../';
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    include '../ajax/compliances.php';
    
    $downloaded = false;
    if (isset($_GET["download"]) && isset($_GET['id'])){
        $loadPage = true;
        $download = sanitizePlus($_GET["download"]);
        $file_id = sanitizePlus($_GET["id"]);

        if ($download == "procedure-downloadxls") {


            $id = $file_id;

            // Filter the excel data
            function filterData(&$str)
            {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            }

            // Excel file name for download
            $fileName = "Applicable Procedure Summary Report For " . date('Y-m-d') . ".xls";

            // Column names
            $fields = array('Procedure Title', 'Procedure Number', 'Procedure Description', 'Procedure Effective Date', 'Procedure Review Date', 'Applicability','Compliance Requirements','Resources','Procedure Approval','Procedure Review','Procedure Acknowledgment');

            // Generate HTML table with column names as first row
            $excelData = '<table>';

            // Display column names as first row
            $excelData .= '<tr>';
            foreach ($fields as $field) {
                $excelData .= '<th style="font-weight:bold;font-size:36pt">' . $field . '</th>';
            }
            $excelData .= '</tr>';

            // Fetch records from the database
            $applypolicy = getApplicableProcedure($id, $con);

            // Generate table row with data values
            $excelData .= '<tr>';
            $lineData = array($applypolicy['ProcedureTitle'], $applypolicy['ProcedureNumber'], $applypolicy['ProcedureDescription'], $applypolicy['ProcedureEffectiveDate'], $applypolicy['ProcedureReviewDate'], $applypolicy['Applicability'],$applypolicy['ComplianceRequirements'],$applypolicy['Resources'],$applypolicy['ProcedureApproval'],$applypolicy['ProcedureReview'],$applypolicy['ProcedureAcknowledgment']);
            array_walk($lineData, 'filterData');
            foreach ($lineData as $value) {
                $excelData .= '<td>' . $value . '</td>';
            }
            $excelData .= '</tr>';

            $excelData .= '</table>';

            // Headers for download
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$fileName\"");

            // Render excel data
            echo $excelData;

        }

        if ($download == "policy-downloadxls") {
            require_once '../_external/PHPExcel.php';
            $id = $file_id;

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

        }
    }else{
        $loadPage = false;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Download Compliances | <?php echo $siteEndTitle ?></title>
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
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                    </div>
                    <?php if($downloaded == true) {?>
                        <div class="card-body">
                            <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                                <div style="text-align: center;"> 
                                        <h3>Download Complete!!</h3>
                                        <p style="margin-top:10px;"><a href="../index.php" class="btn btn-primary btn-icon icon-left"><i class="fas fa-arrow-left"></i> Back</a></p>
                                    </div>
                            </div>
                        </div>
                    <?php }else{ ?>
                    <div class="card-body">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                               <div style="text-align: center;"> 
                                    <h3>Empty Data!!</h3>
                                    <p style="margin-top:10px;"><a href="../index.php" class="btn btn-primary btn-icon icon-left"><i class="fas fa-arrow-left"></i> Back</a></p>
                                </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .risk-desc {
        width: 100%;
        border-collapse: separate !important;
        }
        .risk-desc td {
        text-align: left;
        border: 2px solid #e4e6fc;
        border-radius: 0px 5px 5px 0px !important;
        padding: 10px 15px !important;
        width: 60%;
        font-weight: 400;
        }
        .risk-desc th {
        text-align: right;
        border: 2px solid #e4e6fc;
        border-radius: 5px 0px 0px 5px !important;
        padding: 10px 15px !important;
        width: 40%;
        }
    </style>
</body>
</html>