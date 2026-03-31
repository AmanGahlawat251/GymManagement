<?php

date_default_timezone_set('Asia/Kolkata');
$start_time = date('Y-m-d H:i:s');
if(1)
//if(strtoupper(php_sapi_name()) == 'CLI')
{
	$root_path = '';
	if(!isset($_SERVER['DOCUMENT_ROOT']) || $_SERVER['DOCUMENT_ROOT'] == '')
	{
		$root_path = '/home/u583683241/public_html';
	}else
	{
		$root_path = $_SERVER['DOCUMENT_ROOT']."/";
		$ABSOLUTE_ROOT_PATH = $root_path;
	}
	$_SERVER['DOCUMENT_ROOT'] = $root_path;
	$_SERVER['HTTP_HOST'] = 'swimgymacademy.com';
	$_SERVER['REMOTE_ADDR'] = "CRON";
	
	$log = array();
	$response = array();
	$record_id = '';
	require_once('includes/constant.php');
	require_once('includes/autoload.php');
	$mysqli = new MySqliDriver();
	// Assume $member_id is already defined or received via request
$member_id = 794; // Replace with actual ID

// Fetch member details from tbl_members
$member = $mysqli->singleRowAssoc_new('*', 'tbl_members', 'id = "'.$member_id.'"');

if ($member) {
    // Prepare employee data
    $apiKey = "11";
    $employeeCode = $member['id']; // adjust if the field name differs
    $employeeName = $member['name']; // adjust if needed
    $cardNumber = "Blank";
    $serialNumber = "CUB7235301317";
    $userName = "sgar";
    $userPassword = "Sgar@2024";
    $commandId = 0;

    // Call the function to add employee
    $response = $mysqli->addEmployee(
        $apiKey,
        $employeeCode,
        $employeeName,
        $cardNumber,
        $serialNumber,
        $userName,
        $userPassword,
        $commandId
    );

    // Optional: log or handle the response
    echo "Employee added successfully. Response: ";
    print_r($response);
} else {
    echo "Member not found with ID: $member_id";
}
}
	?>