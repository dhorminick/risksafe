<?php
    if (isset($_POST["search"])) {
        include '../layout/db.php';

		function sanitizePlus2($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}

        function getToArray2($array){
            $getArray = [];

            $convertArray = explode("&", $array);
            for ($i=0; $i < count($convertArray); $i++) { 
                $keyValue = explode('=', $convertArray[$i]);
                $getArray[$keyValue [0]] = $keyValue [1];
            }

            return $getArray;
        }
		
		$value = $_POST["search"];
        $getArray = getToArray2($value);
		
        $q = sanitizePlus2($getArray['q']);
        
        $query = "SELECT * FROM links WHERE link_param LIKE '%$q%' LIMIT 5";
        $exist = $con->query($query);
        if ($exist->num_rows > 0) {
            while ($row=$exist->fetch_assoc()) {
                echo '<a href="/'.$row['link_url'].'"><div class="search_result">'.$row['link_header'].'</div></a>';
            }
        }else{
            echo '<div>No Records Found!!</div>';
        }

		
		
		// echo $consequence.' '.$likelihood;
    }
?>