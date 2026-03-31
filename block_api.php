<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$log = array();
	$response = array();
	$record_id = '';
	require_once('includes/constant.php');
	require_once('includes/autoload.php');
	$mysqli = new MySqliDriver();

			// SOAP API URL
			$url = 'http://166.0.244.12:82/iclock/webapiservice.asmx?op=GetTransactionsLog';

			// Get current date and time
			$currentDateTime = date('Y-m-d H:i');
			$currentDate = date('Y-m-d');
			$fromDateTime = '2024-06-16 00:00';
			$toDateTime = $currentDateTime;
			// SOAP request
			$request = '<?xml version="1.0" encoding="utf-8"?>
			<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			  <soap:Body>
				<GetTransactionsLog xmlns="http://tempuri.org/">
				   <FromDateTime>' . $fromDateTime . '</FromDateTime>
				  <ToDateTime>' . $toDateTime . '</ToDateTime>
				  <SerialNumber>CUB7235301317</SerialNumber>
				  <UserName>sgar</UserName>
				  <UserPassword>Sgar@2024</UserPassword>
				  <strDataList></strDataList>
				</GetTransactionsLog>
			  </soap:Body>
			</soap:Envelope>';

			// SOAP headers
			$headers = array(
				'Content-Type: text/xml; charset=utf-8',
				'Content-Length: ' . strlen($request),
				'SOAPAction: "http://tempuri.org/GetTransactionsLog"'
			);

			// Initialize cURL session
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			// Execute the request and fetch response
			$response = curl_exec($ch);

			// Close cURL session
			curl_close($ch);
			// Set the content type header for XML
			//header('Content-Type: application/xml');
			$xml = new SimpleXMLElement($response);


			// Register namespaces to access SOAP elements
			$namespaces = $xml->getNamespaces(true);
			$soapBody = $xml->children($namespaces['soap'])->Body;

			// Access the elements within the default namespace
			$bodyElements = $soapBody->children($namespaces[''])->GetTransactionsLogResponse;

			// Extract the strDataList element content
			$strDataList = trim((string)$bodyElements->strDataList);

			// Explode the strDataList content into an array
			$response_array = explode(PHP_EOL, $strDataList);

			$newArray = [];

			foreach ($response_array as $element) {
				$exploded = explode(' ', str_replace("\t", ' ', $element));
				$newArray[] = $exploded;
			}
			$signTimes = [];

			/// Group records by ID and date
			foreach ($newArray as $entry) {
				 // Extract the numeric part of the ID and the member_id
                if (preg_match('/^(\d+)-(.+)$/', $entry[0], $matches)) {
                    $id = $matches[1]; // Numeric ID
                    $member_id = $matches[2]; // Member ID
                } else {
                    continue; // Skip if no valid ID format found
                }
				
				$date = $entry[1];
				$time = $entry[2];
				
				if (!isset($signTimes[$id])) {
					$signTimes[$id] = [];
				}
				if (!isset($signTimes[$id][$date])) {
					$signTimes[$id][$date] = [
						'sign_in' => $time,
						'sign_out' => '00:00',
						'member_id' => $member_id
					];
				} else {
					if ($time < $signTimes[$id][$date]['sign_in']) {
						$signTimes[$id][$date]['sign_in'] = $time;
					}
					if ($time > $signTimes[$id][$date]['sign_out'] || $signTimes[$id][$date]['sign_out'] == '00:00') {
						$signTimes[$id][$date]['sign_out'] = $time;
					}
				}
			}

		 // Prepare and execute SQL queries
			foreach ($signTimes as $id => $dates) {
				foreach ($dates as $date => $times) {
					$sign_in = $times['sign_in'];
					$sign_out = $times['sign_out'] == '00:00' ? '' : $times['sign_out'];
					$member_id = $times['member_id'];
					// Check if the record already exists
					$exists = $mysqli->singleRowAssoc_new('*', 'tbl_attendance', 'user_id = "'.$id.'" and attendance_date = "'.$date.'"');

					
					// Only proceed if member_id is not blank
            if (!empty($member_id)) {
                // Check if the attendance record already exists
                $exists = $mysqli->singleRowAssoc_new('*', 'tbl_attendance', 'user_id = "' . $id . '" and attendance_date = "' . $date . '" and member_id = "' . $member_id . '"');

                if (!empty($exists)) {
                    // Update the existing record
                    if ($sign_out) {
                        $sql_update = "UPDATE tbl_attendance SET sign_out = '$sign_out' WHERE user_id = '$id' AND member_id = '$member_id' AND attendance_date = '$date'";
                        $result_up = $mysqli->executeQry($sql_update);
                    }
                } else {
					$RecTimeStamp = $mysqli->RecTimeStamp("Y-m-d H:i:s");
                    // Insert a new record
                     $sql_insert = "INSERT IGNORE INTO tbl_attendance (user_id, member_id, attendance_date, sign_in, sign_out, recTimestamp) VALUES ('$id', '$member_id', '$date', '$sign_in', '$sign_out','$RecTimeStamp')";
                    $result_in = $mysqli->executeQry($sql_insert);
                }
            }
				}
			} 
			
?>
