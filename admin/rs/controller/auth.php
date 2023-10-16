<?php 

include_once("../config.php");
include_once('../model/users.php');
include_once("../model/db.php");
$user=new users;
if (!$user->isLogged()) {
	header('Location: ../view/login.php');
	exit;	
}


if (!$user->canAccessAllPages()) {
    $db = new db;
    $conn = $db->connect();
    $currentPage = basename($_SERVER['PHP_SELF']);
    
    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE iduser = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['userid']);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
       
        if ($row = $result->fetch_assoc()) {
            
            if ($currentPage !== 'payment.php' && $row['role'] !== 'superadmin') {
                header('Location: ../view/payment.php');
                exit;
            }
        }
    }
    
    $stmt->close();
    $db->disconnect($conn);
}




?>