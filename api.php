<?php
	$log = array();
	$response = array();
	$record_id = '';
	require_once('includes/constant.php');
	require_once('includes/autoload.php');
	$mysqli = new MySqliDriver();
	
	$apiKey = "11";
							$employeeCode = 1040;
							$employeeName = 'Jagjeet';
							$isBlock = "false"; // or "false"
							$serialNumber = "CUB7235301317";
							$userName = "sgar";
							$userPassword = "Sgar@2024";
							$commandId = 0;
							$block = $mysqli->blockUnblockUser($apiKey, $employeeCode, $employeeName, $serialNumber, $isBlock, $userName, $userPassword, $commandId); 
							print_r($block);exit;
							
  //$sql = "SELECT * FROM ".MEMBERS." where 1";
//	$result = $mysqli->executeQry($sql);
//	while ($row = $mysqli->fetch_assoc($result)) {
		//extract($row);
		
		$apiKey = "11";
		$employeeCode = $id;
		$employeeName = $name;
		$cardNumber = "Blank";
		$serialNumber = "CUB7235301317";
		$userName = "sgar";
		$userPassword = "Sgar@2024";
		$commandId = 0;exit;
		//print_r($employeeName);exit;
		 //$res = $mysqli->addEmployee($apiKey, $employeeCode, $employeeName, $cardNumber, $serialNumber, $userName, $userPassword, $commandId);   
		print_r($res);echo'<br>';exit;
		/* $apiKey = "11";
			$employeeCode = $id.'-'.$member_id;
			$employeeName = $name;
			$isBlock = "true"; // or "false"
			$serialNumber = "CUB7235301317";
			$userName = "sgar";
			$userPassword = "Sgar@2024";
			$commandId = 0;
			$block = $mysqli->blockUnblockUser($apiKey, $employeeCode, $employeeName, $serialNumber, $isBlock, $userName, $userPassword, $commandId);   */
	//} 

?>
