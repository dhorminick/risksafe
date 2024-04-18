<?php
        $file_dir = '../../';
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        use PhpOffice\PhpSpreadsheet\IOFactory;
        require_once $file_dir.'classes/PHPExcel.php';
        require_once $file_dir.'classes/PHPExcel/IOFactory.php';
        include '../../layout/db.php';
        include '../ajax/customs.php';
    
    if (isset($_GET["file"]) && $_GET["file"] == 'controls') {
        $startDate = "";
        $endDate = "";
        
        $query="SELECT MAX(cus_date) AS max FROM as_customcontrols WHERE c_id = '$company_id'";
        $result=$con->query($query);
    	if ($result->num_rows > 0) {
    	    $row=$result->fetch_assoc();
    	    $largestNumber = $row['max'];
    	}else{
    	    $largestNumber = 0;
    	}
    	
    	$query="SELECT MIN( cus_date ) AS max FROM as_customcontrols WHERE c_id = '$company_id'";
    	if ($result=$con->query($query)) {	
    	    $row=$result->fetch_assoc();
    	    $smallestNumber = $row['max'];
    	}else{
    	    $smallestNumber = 0;
    	}
    	
    	$largestNumber_1 = DateTime::createFromFormat('Y-m-d', $largestNumber);
        $smallestNumber_1 = DateTime::createFromFormat('Y-m-d', $smallestNumber);
        
                
        $largestNumber_1 = date_format($largestNumber_1, "d-m-Y");
        $smallestNumber_1 = date_format($smallestNumber_1, "d-m-Y");
        
        echo $largestNumber.' - '.$largestNumber_1; exit();
        $startDate = sanitizePlus($_GET['startDate']);
    	$endDate = sanitizePlus($_GET['endDate']);
    	
    	if($startDate && $startDate !== '' && $startDate !== null && $endDate && $endDate !== '' && $endDate !== null){
    	if($startDate > $smallestNumber){
    	    array_push($message, 'Error : Earliest Recorded Control Is - '.$smallestNumber_1);
    	}else if($endDate > $largestNumber){
    	    array_push($message, 'Error : Latest Recorded Control Is - '.$largestNumber_1);
    	}else{
    	    
    	    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        	$objPHPExcel->getProperties()->setCreator("RiskSafe")
        								 ->setLastModifiedBy("RiskSafe")
        								 ->setTitle("Controls Dashboard Report")
        								 ->setSubject("Controls Dashboard Report")
        								 ->setDescription("")
        								 ->setKeywords("")
        								 ->setCategory("");
        	$row = 1;						 
        	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Risk Safe')
        	            ->setCellValue('B'.$row, 'Controls Dashboard Report From '.$startDate.' To '.$endDate);	            								 
        	
        	$row = 2;		
        	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Control ID')
	            ->setCellValue('B'.$row, 'Control Name')
	            ->setCellValue('C'.$row, 'Control Category')
	            ->setCellValue('D'.$row, 'Test Date')
	            ->setCellValue('E'.$row, 'Effectiveness')
	            ->setCellValue('F'.$row, 'Observation')
				->setCellValue('G'.$row, 'Frequency')
				->setCellValue('H'.$row, 'Next Test Date');
				
			$row++;	
			
        	$startDate1 = DateTime::createFromFormat('Y-m-d', $startDate);
            $endDate1 = DateTime::createFromFormat('Y-m-d', $endDate);
            
            $startDate1 = date_format($startDate1, "m/d/Y");
            $endDate1 = date_format($endDate1, "m/d/Y");
            
            if($endDate1 > $startDate1){
              
                $list = listControlsForReport($startDate, $endDate, $company_id, $con);
                
                foreach ($list as $item) {
        			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $item['control_id'])
        					->setCellValue('B'.$row, $item['title'])
        					->setCellValue('C'.$row, listControlsCategoryRep($item['category']))
        					->setCellValue('D'.$row, date("m/d/Y", $item['cus_date']))
        					->setCellValue('E'.$row, getEffectiveness($item['effectiveness']))
        					->setCellValue('F'.$row, $item['description'])
        					->setCellValue('G'.$row, getFrequency($item['frequency']))
        					->setCellValue('H'.$row, getNext($item['cus_date'], $item['frequency']));				
        			$row++;	
        		}
        		
        		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
        		
        		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
             
                
                $objPHPExcel->setActiveSheetIndex(0);
        		$objPHPExcel->getActiveSheet()->setTitle('Controls Dashboard Report');
        		
        		// Redirect output to a client’s web browser (Excel2007)
        		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        		header('Content-Disposition: attachment;filename="Controls Dashboard Report For: '.$startDate.' - '.$endDate.'.xlsx"');
        		header('Cache-Control: max-age=0');
        		// If you're serving to IE 9, then the following may be needed
        		header('Cache-Control: max-age=1');
        		
        		// If you're serving to IE over SSL, then the following may be needed
        		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        		header ('Pragma: public'); // HTTP/1.0
        		
        		$writer = new Xlsx($objPHPExcel);
        		$writer->save('php://output');
        		
            }else{
                array_push($message, 'Error: End Date Should Not Be Earlier Than Start Date');
            }
    }
    	}else{
    	    echo 'Error Getting Report Dates!';
    	}
    }
    
    if (isset($_GET["download"]) && $_GET["download"] == '' && isset($_GET["id"]) && $_GET["id"] !== '') {}
    
    if (isset($_GET["download"]) && $_GET["download"] == '' && isset($_GET["id"]) && $_GET["id"] !== '') {}
    
    if (isset($_GET["download"]) && $_GET["download"] == '' && isset($_GET["id"]) && $_GET["id"] !== '') {}
    
?>