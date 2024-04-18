<?php
$file_dir = '../../';
    $message = [];
    
    include $file_dir.'layout/db.php';
    $company_id = '8UT8QR55FR';
    
    $query = "SELECT * FROM as_assessment WHERE c_id = '$company_id'"; 
        $query_run = mysqli_query($con, $query);

        if(mysqli_num_rows($query_run) > 0) {
            $as_row = $query_run->fetch_assoc();
            // $as__id = $as_row['as_id'];
            
            foreach($query_run as $data) {
                $as__id = $data['as_id'];
                echo 'Team: '.$data['as_team'].'<br> Task: '.$data['as_task'].'<br>';
            
                #get risks
                $query_2 = "SELECT * FROM as_details WHERE c_id = '$company_id' AND as_id = '$as__id' ORDER BY iddetail DESC"; 
                $query_run_2 = mysqli_query($con, $query_2);

                #if exist
                if(mysqli_num_rows($query_run_2) > 0) {

                    foreach($query_run_2 as $data_2) {
                        echo 'Risk: '.$data_2['as_descript'].'<br> Hazard: '.$data_2['as_effect'].'<br>'.$as__id.'<br>';
                        
                    }
                }else{
                    #no risk
                    echo 'No Risk';
                }
                
                echo '<br>';
            }
        }else{
            #no assessment
            echo 'No Assessment';
        }

?>