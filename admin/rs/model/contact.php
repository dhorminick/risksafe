<?php

include_once("../config.php");
include_once("db.php");

class contact
{


    public function __construct()
    {
    }


    public function addContact($name, $email, $subject, $question)
    {
        $datetime = date("Y-m-d H:i:s");
        $db = new db();
        $conn = $db->connect();
        $query = "INSERT INTO as_contact (`name`, `email`, `subject`, `question`,`read_status`,`date`)
              VALUES ('$name', '$email', '$subject', '$question','0','$datetime' )";

        $result = mysqli_query($conn, $query);
        if ($result) {
            $db->disconnect($conn);
            return true;
        } else {
            echo "Error11: " . mysqli_error($conn);
            exit();
        }
    }


    // List applicable entries
    public function listcontact($start, $length)
    {
        $db = new db();
        $conn = $db->connect();

        $query = "SELECT * FROM as_contact ORDER BY id DESC LIMIT $start, $length";
        $countQuery = "SELECT COUNT(*) AS total_count FROM as_contact";


        // Fetch the paginated list of users
        $result = $conn->query($query);

        if ($result !== false && $result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Fetch the total count of records based on the user's role
            $countResult = $conn->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $num_total = $countRow["total_count"];

            $db->disconnect($conn);
            return array("data" => $data, "num_total" => $num_total);
        } else {
            $db->disconnect($conn);
            return array("data" => array(), "num_total" => 0);
        }
    }


    public function deletecontact($id)
    {

        $db = new db;
        $conn = $db->connect();
        $query = "DELETE FROM as_contact WHERE id=" . $id . ";";

        if ($conn->query($query)) {
            $response = true;
        } else {
            $response = false;
        }
        $db->disconnect($conn);
        return $response;
    }
}
