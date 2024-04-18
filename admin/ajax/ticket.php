<?php
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
	function countData($con, $table){
        $query="SELECT * FROM $table";
        $result=$con->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return '0';	
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
?>