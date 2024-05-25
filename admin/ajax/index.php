<?php
$conn = $con;

$currentuser = $company_id;
// $currentuser = '';

// Retrieve the count of ineffective controls
$queryIneffective = "SELECT COUNT(*) FROM as_auditcontrols WHERE con_effect = 1 and c_id = '$currentuser' ";
$resultIneffective = $conn->query($queryIneffective);

if ($resultIneffective) {
  $rowIneffective = $resultIneffective->fetch_assoc();
  $ineffectiveCount = $rowIneffective['COUNT(*)'];
} else {
  // echo "Error retrieving ineffective controls count: " . $conn->error;
}

// Retrieve the count of effective controls
$queryEffective = "SELECT COUNT(*) FROM as_auditcontrols WHERE con_effect = 2 and c_id = '$currentuser' ";
$resultEffective = $conn->query($queryEffective);

if ($resultEffective) {
  $rowEffective = $resultEffective->fetch_assoc();
  $effectiveCount = $rowEffective['COUNT(*)'];
} else {
  // echo "Error retrieving effective controls count: " . $conn->error;
}

//not_slected
$querynot_slected = "SELECT COUNT(*) FROM as_auditcontrols WHERE con_effect = 0 and c_id = '$currentuser' ";
$resultnot_slected = $conn->query($querynot_slected);

if ($resultnot_slected) {
  $rownot_slected = $resultnot_slected->fetch_assoc();
  $not_slectedCount = $rownot_slected['COUNT(*)'];
} else {
  // echo "Error retrieving not_slected controls count: " . $conn->error;
}

//Effective Compliance Standard
//Retrieve total compliance standard

$Compliancetotal = "SELECT COUNT(*) FROM as_compliancestandard WHERE c_id = '$currentuser'";
$resultComplianceTotal = $conn->query($Compliancetotal);

if ($resultComplianceTotal) {
  $rowTotal = $resultComplianceTotal->fetch_assoc();
  $totalComplianceCount = $rowTotal['COUNT(*)'];
} else {
  echo "Error retrieving total Compliance count: " . $conn->error;
}

// Retrieve the count of Ineffective Complinace standard
$queryIneffective = "SELECT COUNT(*) FROM as_compliancestandard WHERE co_status = 'Ineffective' and c_id = '$currentuser' ";
$resultIneffective = $conn->query($queryIneffective);

if ($resultIneffective) {
  $rowIneffective = $resultIneffective->fetch_assoc();
  $ineffectiveComplianceCount = $rowIneffective['COUNT(*)'];
} else {
  echo "Error retrieving Inefective Complinace Standard count: " . $conn->error;
}

//Effective Compliance Standard
$queryEffective = "SELECT COUNT(*) FROM as_compliancestandard WHERE co_status = 'Effective' and c_id = '$currentuser' ";
$resultEffective = $conn->query($queryEffective);

if ($resultEffective) {
  $rowEffective = $resultEffective->fetch_assoc();
  $effectivComplianceCount = $rowEffective['COUNT(*)'];
} else {
  echo "Error retrieving Effective Complinace Standard count: " . $conn->error;
}


// Retrieve the total count of controls
$queryTotal = "SELECT COUNT(*) FROM as_auditcontrols WHERE c_id = '$currentuser'";
$resultTotal = $conn->query($queryTotal);

if ($resultTotal) {
  $rowTotal = $resultTotal->fetch_assoc();
  $totalCount = $rowTotal['COUNT(*)'];
} else {
  echo "Error retrieving total controls count: " . $conn->error;
}

///risk action type
$sql_details = "SELECT `as_assessment`, `as_action` FROM `as_details` WHERE `c_id` = '$currentuser'";
// --WHERE `c_id` IN (SELECT `c_id` FROM `as_assessment` WHERE `c_id` = '$currentuser')
$result_details = $conn->query($sql_details);

if ($result_details->num_rows > 0) {
  // Step 3: Count occurrences of as_action values for the current user's assessment
  $labels = array(
    "Avoid (Discontinue risky activity)",
    "Accept (Retain by informed decision)",
    "Remove (Remove risky activity)",
    "Take on Risk to increase opportunity",
    "Change Likelihood",
    "Change Consequence",
    "Share risk with 3rd party (Insurance or Joint Venture)"
  );

  $data = array_fill(0, 7, 0); // Initialize the data array with zeros

  while ($row = $result_details->fetch_assoc()) {
    $as_action = $row['as_action'];
    // Increment the count for the corresponding action
    $data[$as_action - 1]++;
  }
} else {
    $labels = [];
    $data = [];
  // echo "No records found in as_details table for the current user's assessment.";
}


//open incedent
$closed = "Closed";
$open = "Open";
$In_Progress = "In Progress";
// Retrieve the count of closed controls
$queryClose = "SELECT COUNT(*) FROM as_incidents WHERE in_status = '$closed' AND in_user = '$currentuser' ";

$resultClose = $conn->query($queryClose);

if ($resultClose) {
  $rowClose = $resultClose->fetch_assoc();
  $CloseCount = $rowClose['COUNT(*)'];
} else {
  echo "Error retrieving status closed incident count: " . $conn->error;
}

//open incident
$queryOpen = "SELECT COUNT(*) FROM as_incidents WHERE in_status = '$open' AND c_id = '$currentuser' ";
$resultOpen = $conn->query($queryOpen);

if ($resultOpen) {
  $rowOpen = $resultOpen->fetch_assoc();
  $OpenCount = $rowOpen['COUNT(*)'];
} else {
  echo "Error retrieving status open incident count: " . $conn->error;
}
//progress incident
$queryprogress = "SELECT COUNT(*) FROM as_incidents WHERE in_status = '$In_Progress' and c_id = '$currentuser' ";
$resultprogress = $conn->query($queryprogress);

if ($resultprogress) {
  $rowprogress = $resultprogress->fetch_assoc();
  $progressCount = $rowprogress['COUNT(*)'];
} else {
  echo "Error retrieving status open incident count: " . $conn->error;
}

//total incident
$queryincidentsTotal = "SELECT COUNT(*) FROM as_incidents WHERE c_id = '$currentuser'";
$resultincidentsTotal = $conn->query($queryincidentsTotal);

if ($resultincidentsTotal) {
  $rowincidentsTotal = $resultincidentsTotal->fetch_assoc();
  $totalincidentsCount = $rowincidentsTotal['COUNT(*)'];
} else {
  echo "Error retrieving total controls count: " . $conn->error;
}

// Retrieve incident counts for each priority
$queryproretyhigh = "SELECT COUNT(*) AS highCount FROM as_incidents WHERE in_priority = 'High' AND c_id = '$currentuser'";
$resultproretyhigh = $conn->query($queryproretyhigh);

$queryproretyvhigh = "SELECT COUNT(*) AS vhighCount FROM as_incidents WHERE in_priority = 'V High' AND c_id = '$currentuser'";
$resultproretyvhigh = $conn->query($queryproretyvhigh);

$queryproretyMedium = "SELECT COUNT(*) AS mediumCount FROM as_incidents WHERE in_priority = 'Medium' AND c_id = '$currentuser'";
$resultproretyMedium = $conn->query($queryproretyMedium);

$queryproretyLow = "SELECT COUNT(*) AS lowCount FROM as_incidents WHERE in_priority = 'Low' AND c_id = '$currentuser'";
$resultproretyLow = $conn->query($queryproretyLow);

// Initialize incident count variables
$highCount = 0;
$vhighCount = 0;
$mediumCount = 0;
$lowCount = 0;

// Check if the queries were successful
if ($resultproretyhigh && $resultproretyvhigh && $resultproretyMedium && $resultproretyLow) {
  // Fetch the counts from the query results
  $rowproretyhigh = $resultproretyhigh->fetch_assoc();
  $highCount = $rowproretyhigh['highCount'];

  $rowproretyvhigh = $resultproretyvhigh->fetch_assoc();
  $vhighCount = $rowproretyvhigh['vhighCount'];

  $rowproretyMedium = $resultproretyMedium->fetch_assoc();
  $mediumCount = $rowproretyMedium['mediumCount'];

  $rowproretyLow = $resultproretyLow->fetch_assoc();
  $lowCount = $rowproretyLow['lowCount'];
}

// Check if there is no data available
if ($highCount == 0 && $vhighCount == 0 && $mediumCount == 0 && $lowCount == 0) {
  $incidentCounts = array($vhighCount, $highCount, $mediumCount, $lowCount);
  $incidentPriorities = array("V High", "High", "Medium", "Low");
//   $dataPoints = array( 
// 	array("label"=>"Very High", "y"=>$vhighCount),
// 	array("label"=>"High", "y"=>$highCount),
// 	array("label"=>"Medium", "y"=>$mediumCount),
// 	array("label"=>"Low", "y"=>$lowCount)
//   );
  $chartData = $vhighCount.' , '.$highCount.' , '.$mediumCount.' , '.$lowCount.',';
  $chartColour = '"Red", "Orange", "Yellow", "Green",';
  $chartLabel = '"Very High", "High", "Medium", "Low",';
} else {
  // Create an array with the incident counts and priorities
  $incidentCounts = array($vhighCount, $highCount, $mediumCount, $lowCount);
//   $dataPoints = array( 
// 	array("label"=>"Very High", "y"=>$vhighCount),
// 	array("label"=>"High", "y"=>$highCount),
// 	array("label"=>"Medium", "y"=>$mediumCount),
// 	array("label"=>"Low", "y"=>$lowCount)
//   );
  $chartData = $vhighCount.' , '.$highCount.' , '.$mediumCount.' , '.$lowCount.',';
  $chartColour = '"Red", "Orange", "Yellow", "Green",';
  $chartLabel = '"Very High", "High", "Medium", "Low",';
  $incidentPriorities = array("V High", "High", "Medium", "Low");
}


// Total  Policies

$Policiestotal = "SELECT COUNT(*) FROM policyfields WHERE c_id = '$currentuser'";
$resultPoliciesTotal = $conn->query($Policiestotal);

if ($resultPoliciesTotal) {
  $rowTotal = $resultPoliciesTotal->fetch_assoc();
  $totalPoliciesCount = $rowTotal['COUNT(*)'];
} else {
  echo "Error retrieving total Policies count: " . $conn->error;
}

// Overdue policies

$overduePolicyReview = "SELECT COUNT(*) FROM policyfields WHERE PolicyReviewDate < CURDATE() and c_id = '$currentuser' ";
$resultOverdue = $conn->query($overduePolicyReview);

if ($resultOverdue) {
  $rowOverdue = $resultOverdue->fetch_assoc();
  $OverdueCount = $rowOverdue['COUNT(*)'];
} else {
  echo "Error retrieving total overduepolicies count: " . $conn->error;
}


// Treatment success
// Retrieve the count of closed controls
$querysuccess = "SELECT COUNT(*) FROM as_treatments WHERE tre_status = 2 and c_id = '$currentuser' ";
$resultsuccess = $conn->query($querysuccess);

if ($resultsuccess) {
  $rowsuccess = $resultsuccess->fetch_assoc();
  $successCount = $rowsuccess['COUNT(*)'];
} else {
  echo "Error retrieving status closed incident count: " . $conn->error;
}

//cancled incident
$queryCancelled = "SELECT COUNT(*) FROM as_treatments WHERE tre_status = 3 and c_id = '$currentuser' ";
$resultCancelled = $conn->query($queryCancelled);

if ($resultCancelled) {
  $rowCancelled = $resultCancelled->fetch_assoc();
  $CancelledCount = $rowCancelled['COUNT(*)'];
} else {
  echo "Error retrieving status open incident count: " . $conn->error;
}
//progress incident
$query_progress = "SELECT COUNT(*) FROM as_treatments WHERE tre_status = 1 and c_id = '$currentuser' ";
$result_progress = $conn->query($query_progress);

if ($result_progress) {
  $row_progress = $result_progress->fetch_assoc();
  $progress_Count = $row_progress['COUNT(*)'];
} else {
  echo "Error retrieving status open incident count: " . $conn->error;
}

//total incident
$querytreTotal = "SELECT COUNT(*) FROM as_treatments WHERE c_id = '$currentuser'";
$resulttreTotal = $conn->query($querytreTotal);

if ($resulttreTotal) {
  $rowtreTotal = $resulttreTotal->fetch_assoc();
  $totaltreCount = $rowtreTotal['COUNT(*)'];
} else {
  echo "Error retrieving total controls count: " . $conn->error;
}
?>
<?php
// Audit the percentages
if ($totalCount != 0) {
  $effectivePercentage = number_format(($effectiveCount / $totalCount) * 100, 0);
  $ineffectivePercentage = number_format(($ineffectiveCount / $totalCount) * 100, 0);
  $notSelectedPercentages = number_format(($not_slectedCount / $totalCount) * 100, 0);
} else {
  $effectivePercentage = 0;
  $ineffectivePercentage = 0;
  $notSelectedPercentages = 0;
}

?>
<?php
// Compliance Standard the percentages
if ($totalComplianceCount != 0) {
  $effectiveComplinacePercentage = number_format(($effectivComplianceCount / $totalComplianceCount) * 100, 0);
  $ineffectiveComplinacePercentage = number_format(($ineffectiveComplianceCount / $totalComplianceCount) * 100, 0);
  if ($totalPoliciesCount != 0) {
    $overduePoliciesPercentage = number_format(($OverdueCount / $totalPoliciesCount) * 100, 0);
  } else {
    $overduePoliciesPercentage = 0;
  }
} else {
  $effectiveComplinacePercentage = 0;
  $ineffectiveComplinacePercentage = 0;
  $overduePoliciesPercentage = 0;
}

?>
<?php
// Overdue Policies the percentages
if ($totalPoliciesCount != 0) {

  $overduePoliciesPercentage = number_format(($OverdueCount / $totalPoliciesCount) * 100, 0);
} else {
  $overduePoliciesPercentage = 0;
}

?>
<?php
// Incidents the percentages
if ($totalincidentsCount != 0) {
  $openPercentage = number_format(($OpenCount / $totalincidentsCount) * 100, 0);
  $closedPercentage = number_format(($CloseCount / $totalincidentsCount) * 100, 0);
  $progressPercentage = number_format(($progressCount / $totalincidentsCount) * 100, 0);
} else {
  $openPercentage = 0;
  $closedPercentage = 0;
  $progressPercentage = 0;
}
?>
<?php
// Tretments the percentages
if ($totaltreCount != 0) {
  $completePercentage = number_format(($successCount / $totaltreCount) * 100, 0);
  $cancelPercentage = number_format(($CancelledCount / $totaltreCount) * 100, 0);
  $tprogressPercentage = number_format(($progress_Count / $totaltreCount) * 100, 0);
} else {
  $completePercentage = 0;
  $cancelPercentage = 0;
  $tprogressPercentage = 0;
}

?>