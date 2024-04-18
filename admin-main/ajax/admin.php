<?php 
    function countData($con, $table){
        $query="SELECT * FROM $table";
        $result=$con->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return '0';	
		}
    }
    
    function adminData($con, $id){
        $query="SELECT * FROM admin_users WHERE admin_id = '$id'";
        $result=$con->query($query);
		if ($result->num_rows > 0) {
			return $result->fetch_assoc();
		} else {
			return 'error';	
		}
    }

    function countOpen($con, $table){
        $query="SELECT * FROM $table WHERE status = 'open'";
        $result=$con->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return '0';	
		}
    }
    
//     function count_Data($con, $table, $var, $res){
//         $query="SELECT * FROM $table WHERE $var = '$res'";
//         $result=$con->query($query);
// 		if ($result->num_rows > 0) {
// 			return $result->num_rows;
// 		} else {
// 			return '0';	
// 		}
//     }

    function rowCount($conn, $table) {
	
		$query="SELECT * FROM $table";
		if ($result=$conn->query($query)) {
			return $result->num_rows;
		} else {
			return false;	
		}
	
	}

    function listNotifications($conn) {		
		$query="SELECT * FROM notification_admin WHERE status = 'unread' ORDER BY datetime DESC";	
		if ($result=$conn->query($query)) {	
			$data=array();
			while ($row=$result->fetch_assoc()) {
				$data[]=$row;
			}
			$response = $data;
		} else {
			$response = false;	
		}
		return $response;	
	}

    function listTickets($limit, $conn) {		
        if ($limit == 'null') {
            # get all
            $query="SELECT * FROM tickets WHERE status = 'open' ORDER BY t_datetime_modified DESC";
        } else {
            $query="SELECT * FROM tickets WHERE status = 'open' ORDER BY t_datetime_modified DESC LIMIT $limit";
        }
        $result=$conn->query($query);	
		if ($result->num_rows > 0) {	
            while ($row=$result->fetch_assoc()) {
                $response[] = $row;
            }
		} else {
			$response = false;	
		}
		return $response;	
	}

    function listUsers($limit, $conn) {		
        if ($limit == 'null') {
            # get all
            $query="SELECT * FROM users ORDER BY created_on DESC";
        } else {
            $query="SELECT * FROM users ORDER BY created_on DESC LIMIT $limit";
        }
        
        $result=$conn->query($query);	
		if ($result->num_rows > 0) {	
            while ($row=$result->fetch_assoc()) {
                $response[] = $row;
            }
		} else {
			$response = false;	
		}
		return $response;	
	}

    function getCompanyDetails($id, $conn) {		
        $query="SELECT * FROM users WHERE company_id = '$id'";
        $result=$conn->query($query);	
		if ($result->num_rows > 0) {	
            $response = $result->fetch_assoc();
        } else {
			$response = 'error';	
		}
		return $response;	
	}

    function getTicketDetails($id, $conn) {		
        $query="SELECT * FROM tickets WHERE ticket_id = '$id'";
        $result=$conn->query($query);	
		if ($result->num_rows > 0) {	
            $response = $result->fetch_assoc();
        } else {
			$response = 'error';	
		}
		return $response;	
	}

    function getStatus($status){
        switch ($status) {
            case 'free':
                $class = 'Free Trial';
                break;
            
            case 'paid':
                $class = 'Paid';
                break;
            
            default:
                $class = 'Error';
                break;
        }

        return $class;
    }

    function getStatusDuration($status){
        switch ($status) {
            case 'trial':
                $class = '';
                break;
            
            case 'monthly':
                $class = '(Monthly Payment)';
                break;
            
            case 'annually':
                $class = '(Annual Payment)';
                break;
            
            case 'bi-annually':
                $class = '(Bi-Annual Payment)';
                break;
            
            default:
                $class = 'Error';
                break;
        }

        return $class;
    }

    if (isset($_POST["search"])) {
        include '../../layout/db.php';

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
        
        $query = "SELECT * FROM links WHERE link_header LIKE '%$q%' LIMIT 5";
        $exist = $con->query($query);
        if ($exist->num_rows > 0) {
            while ($row=$exist->fetch_assoc()) {
                echo '<a href="'.$row['link_url'].'"><div class="search_result">'.$row['link_header'].'</div></a>';
            }
        }else{
            echo '<div>No Records Found!!</div>';
        }

		
		
		// echo $consequence.' '.$likelihood;
    }
?>