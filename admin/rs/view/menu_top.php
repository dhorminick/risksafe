<?php

       
        $db=new db;
        $conn=$db->connect();
        $currentUser =  $_SESSION["userid"]; 
        $sql = "SELECT role FROM users WHERE iduser = '$currentUser'";
      
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $currentUserRole = $row['role'];
        }
       
        // $query="SELECT * FROM as_notification WHERE read_status='0' and notification_by=".$_SESSION["userid"]."";

        $query="SELECT * FROM as_notification WHERE read_status='0'";
        $queryforcontact = "SELECT * FROM as_contact WHERE read_status='0'";
        
        if ($result=$conn->query($query)) {	
        $data=array();
        $rows = mysqli_num_rows($result);
        // while ($row=$result->fetch_assoc()) {
        // $data[]=$row;
        // }
        $response=$rows;

        } else {
        $response=false;	
        }
        if ($resultForContact = $conn->query($queryforcontact)) {	
            $dataForContact = array();
            $rowsForContact = mysqli_num_rows($resultForContact);
            // while ($row = $resultForContact->fetch_assoc()) {
            //     $dataForContact[] = $row;
            // }
            // You can use $dataForContact array for further processing with the second query results.
        } else {
            $responseForContact = false;	
        }
        
        $db->disconnect($conn);
      


?>
<?php if( $currentUserRole=='superadmin'){ 
?>
<li><a href="readnotification.php" > Notification(<?php echo $rows; ?>)</a></li>
<li><a href="contactadmin.php" > Contact Requests(<?php echo $rowsForContact; ?>)</a></li>


<?php  }
?>
<li><a href="userprofile.php">My Profile</a></li>

<li><a href="../controller/users.php?action=logout">Logout</a></li>