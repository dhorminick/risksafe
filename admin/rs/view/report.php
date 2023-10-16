<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/db.php');
include_once('../model/report.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';
require '../../vendor/autoload.php';





$report = new report();
echo $report->countChart($_REQUEST["id"], 1, 2);

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "downloadxls") {

    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $objPHPExcel->getProperties()->setCreator("RiskSafe")
        ->setLastModifiedBy("RiskSafe")
        ->setTitle("Assesment Risk Report")
        ->setSubject("Assesment Risk Report")
        ->setDescription("")
        ->setKeywords("")
        ->setCategory("");
    $row = 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Risk Safe')
        ->setCellValue('B' . $row, 'Assesment Risk Report');

    $row = 3;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Assessment Number')
        ->setCellValue('B' . $row, 'Organisation or Team Name')
        ->setCellValue('C' . $row, 'Task or Process Being reviewed')
        ->setCellValue('D' . $row, 'Bussiness/Process Owner')
        ->setCellValue('E' . $row, 'Assessor Name')
        ->setCellValue('F' . $row, 'Date')
        ->setCellValue('G' . $row, 'Next Assessment Date')
        ->setCellValue('H' . $row, 'Approval');
    $row++;
    $rep = $report->getReport($_REQUEST["id"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, $rep['as_number'])
        ->setCellValue('B' . $row, $rep['as_team'])
        ->setCellValue('C' . $row, $rep['as_task'])
        ->setCellValue('D' . $row, $rep['as_owner'])
        ->setCellValue('E' . $row, $rep['as_assessor'])
        ->setCellValue('F' . $row, $rep['as_date'])
        ->setCellValue('G' . $row, $rep['as_next'])
        ->setCellValue('H' . $row, $report->getApproval($rep['as_approval']));

    $row = 6;
    //   $col = PHPExcel_Cell::columnIndexFromString('A') - 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Chart');

    $row = 7;
    //   $col = PHPExcel_Cell::columnIndexFromString('C') - 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $row, 'Consequence');

    $row = 8;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $row, 'Insignificant')
        ->setCellValue('D' . $row, 'Minor')
        ->setCellValue('E' . $row, 'Moderate')
        ->setCellValue('F' . $row, 'Major')
        ->setCellValue('G' . $row, 'Severe')
        ->setCellValue('H' . $row, 'Totals');
    // Likelihood
    $row = 9;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B' . $row++, 'Almost certain')
        ->setCellValue('B' . $row++, 'Likely')
        ->setCellValue('B' . $row++, 'Possible')
        ->setCellValue('B' . $row++, 'Unlikely')
        ->setCellValue('B' . $row++, 'Rare')
        ->setCellValue('B' . $row++, 'Totals');

    $row = 9;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, 'Likelihood');
    //vertical
    $objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setTextRotation(90);


    // $row=9;
    // $objPHPExcel->setActiveSheetIndex(0)
    // ->setCellValue('H'.$row++,$report->countChart($_REQUEST["id"],1,1))
    // ->setCellValue('H'.$row++, $report->countChart($_REQUEST["id"],1,2))
    // ->setCellValue('H'.$row++,$report->countChart($_REQUEST["id"],1,3))
    // ->setCellValue('H'.$row++,$report->countChart($_REQUEST["id"],1,4))
    // ->setCellValue('H'.$row++,$report->countChart($_REQUEST["id"],1,5))
    // ->setCellValue('H'.$row++,$report->countLikelihood($_REQUEST["id"],1));
    $row = 9;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('H' . $row++, '0')
        ->setCellValue('H' . $row++, '0')
        ->setCellValue('H' . $row++, '0')
        ->setCellValue('H' . $row++, '0')
        ->setCellValue('H' . $row++, '0')
        ->setCellValue('H' . $row++, '0');

    // $row=14;
    // $objPHPExcel->setActiveSheetIndex(0)
    // ->setCellValue('C'.$row++,$report->countChart($_REQUEST["id"],1,1))
    // ->setCellValue('D'.$row++, $report->countChart($_REQUEST["id"],1,2))
    // ->setCellValue('E'.$row++,$report->countChart($_REQUEST["id"],1,3))
    // ->setCellValue('F'.$row++,$report->countChart($_REQUEST["id"],1,4))
    // ->setCellValue('G'.$row++,$report->countChart($_REQUEST["id"],1,5))
    // ->setCellValue('H'.$row++,$report->countLikelihood($_REQUEST["id"],1));
    $row = 14;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $row, '0')
        ->setCellValue('D' . $row, '0')
        ->setCellValue('E' . $row, '0')
        ->setCellValue('E' . $row, '0')
        ->setCellValue('F' . $row, '0')
        ->setCellValue('G' . $row, '0')
        ->setCellValue('H' . $row, $report->countLikelihood($_REQUEST["id"], 1));

    $row = 19;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Risk Totals');

    $row = 20;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row++, '')
        ->setCellValue('A' . $row++, 'Total');

    $row = 20;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $row, 'Extreme risks')
        ->setCellValue('C' . $row, 'High risks')
        ->setCellValue('D' . $row, 'Medium risks')
        ->setCellValue('E' . $row, 'Low risks');
    $row++;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $row, $report->countRisks($_REQUEST["id"], 4))
        ->setCellValue('C' . $row, $report->countRisks($_REQUEST["id"], 3))
        ->setCellValue('D' . $row, $report->countRisks($_REQUEST["id"], 2))
        ->setCellValue('E' . $row, $report->countRisks($_REQUEST["id"], 1));

    // Control..
    $row = 25;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Controls');
    $row = 26;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, '')
        ->setCellValue('B' . $row, 'Total');
    $row++;

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Number of controls')
        ->setCellValue('B' . $row, $report->countControls($_REQUEST["id"]));


    // Treatment..
    $row = 30;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Treatment');
    $row = 31;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, '')
        ->setCellValue('B' . $row, 'Total');
    $row++;

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, ' Number of treatments')
        ->setCellValue('B' . $row, $report->countTreatments($_REQUEST["id"]));

    // ...............
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6:L6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A7:L7')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A8:L8')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A19:L19')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A20:L20')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A21:L21')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A25:L25')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A26:B26')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A30:B30')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A31:B31')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A9:A14')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B9:B14')->getFont()->setBold(true);

    //Color...
    $objPHPExcel->getActiveSheet()->getStyle('B21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

    $objPHPExcel->getActiveSheet()->getStyle('C21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');

    $objPHPExcel->getActiveSheet()->getStyle('D21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $objPHPExcel->getActiveSheet()->getStyle('E21')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');

    $objPHPExcel->getActiveSheet()->getStyle('C9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $objPHPExcel->getActiveSheet()->getStyle('D9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');

    $objPHPExcel->getActiveSheet()->getStyle('E9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

    $objPHPExcel->getActiveSheet()->getStyle('F9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

    $objPHPExcel->getActiveSheet()->getStyle('G9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

    $objPHPExcel->getActiveSheet()->getStyle('C10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');


    $objPHPExcel->getActiveSheet()->getStyle('D10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');

    $objPHPExcel->getActiveSheet()->getStyle('E10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');

    $objPHPExcel->getActiveSheet()->getStyle('F10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

    $objPHPExcel->getActiveSheet()->getStyle('G10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

    $objPHPExcel->getActiveSheet()->getStyle('C11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');


    $objPHPExcel->getActiveSheet()->getStyle('D11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $objPHPExcel->getActiveSheet()->getStyle('E11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');

    $objPHPExcel->getActiveSheet()->getStyle('F11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');

    $objPHPExcel->getActiveSheet()->getStyle('G11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

    $objPHPExcel->getActiveSheet()->getStyle('C12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');


    $objPHPExcel->getActiveSheet()->getStyle('D12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');

    $objPHPExcel->getActiveSheet()->getStyle('E12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $objPHPExcel->getActiveSheet()->getStyle('F12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $objPHPExcel->getActiveSheet()->getStyle('G12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');

    $objPHPExcel->getActiveSheet()->getStyle('C13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');


    $objPHPExcel->getActiveSheet()->getStyle('D13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');

    $objPHPExcel->getActiveSheet()->getStyle('E13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');

    $objPHPExcel->getActiveSheet()->getStyle('F13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $objPHPExcel->getActiveSheet()->getStyle('G13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    // // Thick..
    //   $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

    //   $objPHPExcel->getActiveSheet()->getStyle('A3:L3')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

    //   $objPHPExcel->getActiveSheet()->getStyle('A7:L7')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

    //   $objPHPExcel->getActiveSheet()->getStyle('A16:D16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

    //   $objPHPExcel->getActiveSheet()->getStyle('A21:B21')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

    //   $objPHPExcel->getActiveSheet()->getStyle('A26:26')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);


    // .......merge
    $objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
    $objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
    $objPHPExcel->getActiveSheet()->mergeCells('A7:B7');
    $objPHPExcel->getActiveSheet()->mergeCells('A8:B8');
    $objPHPExcel->getActiveSheet()->mergeCells('C7:D7');
    $objPHPExcel->getActiveSheet()->mergeCells('A9:A14');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);    


    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Assesment Risk Report');
    $filePath = 'Assesment_Risk_Report.xlsx';

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Assesment Risk Report.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = new Xlsx($objPHPExcel);
    $writer->save('php://output');
    exit;
}



$datainfo = $report->getReport($_REQUEST["id"]);

//total risks
$db = new db;
$conn = $db->connect();
$totalrisks = $db->rowCount($conn, "as_details", "as_assessment", $_REQUEST["id"]);
$db->disconnect($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("header.php"); ?>
</head>

<body>
    <!-- header -->
    <div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE; ?></a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
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
                <h1 class="page-header">Risk assessment report</h1>
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <form role="form" id="form" action="../view/report.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" />

                                    <div class="form-group">
                                        <button type="submit" name="action" value="downloadxls" class="btn btn-md btn-info pull-right" id="btn_save">Export to MS
                                            Excel</button>
                                    </div>
                                </form>
                                <!-- <button type="button" class="btn btn-md btn-info pull-right" id="btn_xls">Export to MS Excel</button> -->
                                <button style="margin-right:10px;" type="button" class="btn btn-md btn-info pull-right" id="btn_back">&lt;&lt; Back</button>
                            </div>
                            <div class="form-group">
                                <div class="title_text">Assessment number</div>
                                <div class="content_text"><?php echo $datainfo["as_number"]; ?></div>
                            </div>
                            <div class="form-group">
                                <div class="title_text">Organisation or Team Name</div>
                                <div class="content_text"><?php echo $datainfo["as_team"]; ?></div>
                            </div>

                            <div class="form-group">
                                <div class="title_text">Task or Process Being reviewed</div>
                                <div class="content_text"><?php echo $datainfo["as_task"]; ?></div>
                            </div>

                            <div class="form-group">
                                <div class="title_text">Bussiness/Process Owner</div>
                                <div class="content_text"><?php echo $datainfo["as_owner"]; ?></div>
                            </div>

                            <div class="form-group">
                                <div class="title_text">Assessor Name</div>
                                <div class="content_text"><?php echo $datainfo["as_assessor"]; ?></div>
                            </div>
                            <div class="form-group">
                                <div class="title_text">Date</div>
                                <div class="content_text"><?php echo date("m/d/Y", strtotime($datainfo["as_date"])); ?></div>
                            </div>
                            <div class="form-group">
                                <div class="title_text">Next Assessment Date</div>
                                <div class="content_text"><?php echo date("m/d/Y", strtotime($datainfo["as_next"])); ?></div>
                            </div>
                            <div class="form-group">
                                <div class="title_text">Approval</div>
                                <div class="content_text">
                                    <?php

                                    echo $report->getApproval($datainfo["as_approval"]);

                                    ?>

                                </div>
                            </div>

                            <!--  HEAT MAP -->
                            <div class="form-group">
                                <div class="title_text">Chart</div>
                                <div style="width:100%;height:auto;">
                                    <table width="100%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td colspan="2" rowspan="2" align="center" valign="middle">&nbsp;</td>
                                            <td colspan="6" align="center" valign="middle"><strong>Consequence</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">
                                                Insignificant</td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Minor</td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Moderate
                                            </td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Major</td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Severe</td>
                                            <td width="12%" align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong>Totals</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="3%" rowspan="6" align="center" valign="middle" class="tbl_rotate"><strong>Likelihood</strong></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Almost
                                                certain</td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 1, 1); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                                <?php echo $report->countChart($_REQUEST["id"], 1, 2); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                                <?php echo $report->countChart($_REQUEST["id"], 1, 3); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                                <?php echo $report->countChart($_REQUEST["id"], 1, 4); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                                <?php echo $report->countChart($_REQUEST["id"], 1, 5); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $report->countLikelihood($_REQUEST["id"], 1); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Likely
                                            </td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 2, 1); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                                <?php echo $report->countChart($_REQUEST["id"], 2, 2); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                                <?php echo $report->countChart($_REQUEST["id"], 2, 3); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                                <?php echo $report->countChart($_REQUEST["id"], 2, 4); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                                <?php echo $report->countChart($_REQUEST["id"], 2, 5); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $report->countLikelihood($_REQUEST["id"], 2); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Possible
                                            </td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 3, 1); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 3, 2); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                                <?php echo $report->countChart($_REQUEST["id"], 3, 3); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                                <?php echo $report->countChart($_REQUEST["id"], 3, 4); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                                <?php echo $report->countChart($_REQUEST["id"], 3, 5); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $report->countLikelihood($_REQUEST["id"], 3); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Unlikely
                                            </td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 4, 1); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 4, 2); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 4, 3); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 4, 4); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                                <?php echo $report->countChart($_REQUEST["id"], 4, 5); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $report->countLikelihood($_REQUEST["id"], 4); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Rare</td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 5, 1); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 5, 2); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 5, 3); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 5, 4); ?></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                                <?php echo $report->countChart($_REQUEST["id"], 5, 5); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $report->countLikelihood($_REQUEST["id"], 5); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong>Totals</strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo $report->countConsequence($_REQUEST["id"], 1); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo $report->countConsequence($_REQUEST["id"], 2); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo $report->countConsequence($_REQUEST["id"], 3); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo $report->countConsequence($_REQUEST["id"], 4); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo $report->countConsequence($_REQUEST["id"], 5); ?></strong>
                                            </td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $totalrisks; ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="form-group" style="margin-top:20px;">
                                    <div class="title_text">Risk totals</div>

                                    <table width="30%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA"><strong>Total</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Extreme risks</td>
                                            <td align="center" valign="middle" bgcolor="#FF0000">
                                                <strong><?php echo $report->countRisks($_REQUEST["id"], 4); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;High risks</td>
                                            <td align="center" valign="middle" bgcolor="#FF9900">
                                                <strong><?php echo $report->countRisks($_REQUEST["id"], 3); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Medium risks</td>
                                            <td align="center" valign="middle" bgcolor="#FFFF00">
                                                <strong><?php echo $report->countRisks($_REQUEST["id"], 2); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Low risks</td>
                                            <td align="center" valign="middle" bgcolor="#00FF00">
                                                <strong><?php echo $report->countRisks($_REQUEST["id"], 1); ?></strong>
                                            </td>
                                        </tr>
                                    </table>



                                </div>
                                <div class="form-group" style="margin-top:20px;">
                                    <div class="title_text">Controls</div>

                                    <table width="30%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA"><strong>Total</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Number of controls</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong><?php echo $report->countControls($_REQUEST["id"]); ?></strong>
                                            </td>
                                        </tr>
                                    </table>



                                </div>
                                <div class="form-group" style="margin-top:20px;">
                                    <div class="title_text">Treatments</div>

                                    <table width="30%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA"><strong>Total</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Number of treatments</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong><?php echo $report->countTreatments($_REQUEST["id"]); ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                            </div>
                        </div>
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
        $(document).ready(function(e) {

            $("#btn_back").click(function(e) {
                $(location).attr("href", "reports.php");
            });

        });
    </script>
</body>

</html>