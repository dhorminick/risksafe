<?php
    session_start();
    $file_dir = '../../';
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    include '../ajax/assessment.php';
    
    $downloaded = false;
    if (isset($_GET["download"]) && isset($_GET['id'])){
        $loadPage = true;
        $download = sanitizePlus($_GET["download"]);
        $file_id = sanitizePlus($_GET["id"]);
        #if ($download == "xls") {


            $id = $file_id;

            // Filter the excel data
            function filterData(&$str)
            {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            }
        
            // Excel file name for download
            $fileName = "Risk Assessments Summary Report For " . date('Y-m-d') . ".xls";
        
            // Column names
            $fields = array('Type of Assessment', 'Team or Company','Description of Task or Process', 'Task/Process', 'Business/Process Owner', 'Assessment #', 'Approval');

            $fields2 = array('Risk','Description','Risk Description','Likelihood','Consequence','Risk Rating','Controls','Control Effectiveness or Gaps','Action Type','Treatment Plan','Due Date','Action Owner');
        
            // Generate HTML table with column names as first row
            $excelData = '<table>';
        
            // Display column names as first row
            $excelData .= '<tr>';
            foreach ($fields as $field) {
                $excelData .= '<th style="font-weight:bold;font-size:36pt">' . $field . '</th>';
            }
            $excelData .= '</tr>';
            $excelData2 = '<table>';
        
            // Display column names as first row
            $excelData2 .= '<tr>';
            foreach ($fields2 as $field) {
                $excelData2 .= '<th style="font-weight:bold;font-size:36pt">' . $field . '</th>';
            }
            $excelData2 .= '</tr>';
        
            // Fetch records from the database
            $assessment = getAssessment($id, $con);
        
            if ($assessment == 'false') {
                $assessError = true;
            }else{
                $assessError = false;
                // Generate table row with data values
                $excelData .= '<tr>';
                $lineData = array($assessment['ty_name'], $assessment['as_team'], $assessment['as_task'], $assessment['as_owner'], $assessment['idassessment'], getApproval($assessment['as_approval']));
                array_walk($lineData, 'filterData');
                foreach ($lineData as $value) {
                    $excelData .= '<td>' . $value . '</td>';
                }
                $excelData .= '</tr>';
            
                $excelData .= '</table>';
        
                $list = getAssessmentDetForReport($id, $con);
                if ($list == 'false') {
                    $assessError = true;
                }else{
                    $assessError = false;
                    $excelData2 .= '<tr>';
                    $lineData2 = array();
                    foreach ($list as $item) {
                        $lineData2 = array($item['ri_name'], $item['cat_name'], $item['as_descript'], $item['li_like'], $item['con_consequence'], getRating($item['as_rating']),listControlsForReport($item['iddetail'], $con),$item['as_effect'],$item['ac_type'],listTreatmentsForReport($item['iddetail'], $con),$item['as_duedate'],$item['as_owner']);
                    }
                    
                    
                    array_walk($lineData2, 'filterData');
                    foreach ($lineData2 as $value) {
                        $excelData2 .= '<td>' . $value . '</td>';
                    }
                    $excelData2 .= '</tr>';
                
                    $excelData2 .= '</table>';
                    // Headers for download
                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=\"$fileName\"");
                
                    // Render excel data
                    echo $excelData;
                    echo'<br>';
                    echo $excelData2;

                    $downloaded = true;
                    exit();
                }
            }
        #}
    }else{
        $loadPage = false;
    }

?>