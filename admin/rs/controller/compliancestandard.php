<?php
include_once("../controller/auth.php");
include_once("../config.php");
include_once("../model/db.php");
include_once("../model/compliancestandard.php");

$compliance = new compliancestandard();

// LIST OF TREATMENTS
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "list") {
    $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
    $length = isset($_REQUEST["length"]) ? $_REQUEST["length"] : 10;

    $db = new db();
    $conn = $db->connect();
    $num = $db->rowCount($conn, "as_compliancestandard", "com_user_id", $_SESSION["userid"]);

    $list = $compliance->listCompliances($start, $length);

    $fulldata = array();
    $data = array();

    $fulldata["draw"] = isset($_REQUEST["draw"]) ? $_REQUEST["draw"] : 1;
    $fulldata["recordsTotal"] = $num;
    $fulldata["recordsFiltered"] = $num;

    if (is_array($list) && !empty($list)) {
        foreach ($list as $item) {
            $response = array();
            $response["nr"] = $item["idcompliance"];
            $response["Compliance Standard"] = $item["com_compliancestandard"];
            $response["Legislation"] = $item["com_legislation"];
            $response["Control Requirements"] = $item["com_controls"];
            $response["Compliance Status"] = $item["co_status"];
            $response["Compliance Officer"] = $item["com_officer"];
            $response["link"] = '<div style="text-align: center;">
            <a class="btn btn-xs btn-info" title="View" href="compliance.php?id=' . $item["idcompliance"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
            <a title="Edit" class="btn btn-xs btn-primary" href="compliancestandard.php?action=edit&id=' . $item["idcompliance"] . '"><i class="glyphicon glyphicon-pencil"></i></a>
            <a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["idcompliance"] . '\');"><i class="glyphicon glyphicon-trash"></i></a>
            <a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="compliances.php?action=downloadxls&id=' . $item["idcompliance"] . '"><i class="glyphicon glyphicon-download"></i></a>
            </div>';
            $data[] = $response;
        }
    }

    $fulldata["data"] = $data;

    header('Content-Type: application/json');
    echo json_encode($fulldata);
    exit();
}


// Add COMPLIANCE STANDARD
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add") {

    $compliancestandard = $_REQUEST["compliancestandard"];
    $legislation = $_REQUEST["legislation"];
    $existing_tr = $_REQUEST["existing_tr"];
    $existing_ct = $_REQUEST["existing_ct"];
    $control = $_REQUEST["control"];
    $training = $_REQUEST["training"];
    $compliancestatus = $_REQUEST["compliancestatus"];
    $officer = $_REQUEST["officer"];
    $file = $_FILES["documentation"];
    $fileName = "";

    if (isset($_FILES["documentation"]) && $_FILES["documentation"]['error']===0) {
        if ($file) {
            $targetDir = "../uploads/"; // Directory to store uploaded files
            $fileName = uniqid() . '_' . basename($file["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats (you can modify this as needed)
            // $allowedExtensions = array("pdf", "doc", "docx", "png", "jpg");
            // if (!in_array($fileType, $allowedExtensions)) {
            //     header("Location: ../view/compliancestandard.php?response=err&action=add&error=invalid_file_type");
            //     exit();

            // }

            //Move uploaded file to target directory
            if (!move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                $error = error_get_last();
                echo "File upload failed: " . $error['message'];
                exit();
            }
        }
    }


    $result = $compliance->addCompliance(
        $compliancestandard,
        $legislation,
        $control,
        $training,
        $compliancestatus,
        $officer,
        $targetFilePath,
        $existing_tr,
        $existing_ct
    );

    if ($result) {
        $message='Compliance standard has been created';
        if ($compliance->savenotification($_SESSION["userid"],$message,0,0,$result)) { 
        header("Location: ../view/compliances.php?id=" . $result);

    }} else {
        header("Location: ../view/compliancestandard.php?response=err&action=add");
    }
}

// Edit COMPLIANCE STANDARD
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "edit") {
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
    $compliancestandard = isset($_REQUEST["compliancestandard"]) ? $_REQUEST["compliancestandard"] : "";
    $legislation = isset($_REQUEST["legislation"]) ? $_REQUEST["legislation"] : "";
    $control = isset($_REQUEST["control"]) ? $_REQUEST["control"] : "";
    $training = isset($_REQUEST["training"]) ? $_REQUEST["training"] : "";
    $compliancestatus = isset($_REQUEST["compliancestatus"]) ? $_REQUEST["compliancestatus"] : "";
    $officer = isset($_REQUEST["officer"]) ? $_REQUEST["officer"] : "";
    $existing_tr = isset($_REQUEST["existing_tr"]) ? $_REQUEST["existing_tr"] : "";
    $existing_ct = isset($_REQUEST["existing_ct"]) ? $_REQUEST["existing_ct"] : "";
    $file = isset($_FILES["documentation"]) ? $_FILES["documentation"] : "";
    $fileName = "";
    if ($file["name"]) {
        $targetDir = "./uploads/"; // Directory to store uploaded files
        $fileName = basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats (you can modify this as needed)
        // $allowedExtensions = array("pdf", "doc", "docx", "png", "jpg");
        // if (!in_array($fileType, $allowedExtensions)) {
        //     header("Location: ../view/compliancestandard.php?response=err&action=edit&error=invalid_file_type");
        //     exit();
        // }

        // Move uploaded file to target directory
        // if (!move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        //     header("Location: ../view/compliancestandard.php?response=err&action=edit&error=file_upload_failed");
        //     exit();
        // }
    }

    $result = $compliance->editCompliance(
        $id,
        $compliancestandard,
        $legislation,
        $control,
        $training,
        $compliancestatus,
        $officer,
        $targetFilePath,
        $existing_tr,
        $existing_ct
    );

    if ($result) {
        header("Location: ../view/compliances.php");
    } else {
        header("Location: ../view/compliancestandard.php?response=err&action=edit");
    }
}

// DELETE COMPLIANCE STANDARD
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete") {
    $result = $compliance->deleteCompliance($_REQUEST["id"]);

    echo $result;
}
?>