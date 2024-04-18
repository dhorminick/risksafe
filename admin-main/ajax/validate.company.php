<?php
    $connection = connection_status();

    if ($connection == 1) { #change back to 0
        echo 'Connection Error!!';
    }else{
        $file_dir = '../../';
        include $file_dir.'layout/db.php';
        function sanitizePlus($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = strip_tags($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        function getToArray($array){
            $getArray = [];

            $convertArray = explode("&", $array);
            for ($i=0; $i < count($convertArray); $i++) { 
                $keyValue = explode('=', $convertArray[$i]);
                $getArray[$keyValue [0]] = $keyValue [1];
            }

            return $getArray;
        }

        if (isset($_POST["validateCompany"])) {
            $value = $_POST["validateCompany"];
            $getArray = getToArray($value);

            $company = sanitizePlus($getArray["c_id"]);

            if ($company == null || $company == '' || !$company) {
                echo 'Error 402: Missing Parameters!!';
            }else{
                $ConfirmUserExist = "SELECT * FROM users WHERE company_id = '$company'";
                $ConfirmedUser = $con->query($ConfirmUserExist);
                if ($ConfirmedUser->num_rows > 0) {
                    $row = $ConfirmedUser->fetch_assoc();
                    $details = $row['company_details'];
                    $details = unserialize($details);
                    $details = $details['company_name'];
                }else{
                    $details = 'Error Fetching Company!!';
                }

                echo $details;
            }
        }
    }
?>