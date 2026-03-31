<?php
extract($_POST);

if (!isset($_SESSION)) {
	session_start();
}

if ($tab != 'login') {
	require_once('check_session.php');
}


if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	echo "<script language='javascript' type='text/javascript'>";
	echo "alert('Request not identified as ajax request');";
	echo "</script>";
	$URL = "index.php";
	echo "<script>location.href='$URL'</script>";
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	echo "<script language='javascript' type='text/javascript'>";
	echo "alert('Bad Request method');";
	echo "</script>";
	$URL = "index.php";
	echo "<script>location.href='$URL'</script>";
}
//echo $tab;exit;

$response = array();
$log = array();
$record_id = '';
$logid = $mysqli->Resquest_Response_log("", strtoupper($tab), '', json_encode($_POST), ''); 

if($tab == "get_plans"){

	$html = '';
	 $sql= "SELECT * from tbl_plans WHERE plan_type = '".$type."'";
	$result = $mysqli->executeQry($sql);
	 $html .= '<option value="">Select Plan</option>';
	while ($row = $mysqli->fetch_assoc($result)) {
	 extract($row);
	 
	 $html .= '<option value="'.$id.'">'.$title.'</option>';
 }
		
	echo $html;


}
if($tab == "get_families"){

	$html = '';
	 $sql= "SELECT * from ".FAMILY_ID." WHERE 1 ORDER BY id DESC";
	$result = $mysqli->executeQry($sql);
	 $html .= '<option value="">--Select--</option>';
	while ($row = $mysqli->fetch_assoc($result)) {
	 extract($row); 
	 $sql123 = "select count(id) as count_rows from ".MEMBERS." where member_id = '".$family_id."'";
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
	
	  if ($num != 4) {
            $html .= '<option value="' . $family_id . '">' . $family_id . ' (' . $num . ')</option>';
        } else {
            $html .= '<option value="' . $family_id . '" disabled>' . $family_id . ' (' . $num . ')</option>';
        }
 }
	 if ($allOptionsHaveFourMembers) {
        $html .= '<script>document.getElementById("family_id").disabled = true;</script>';
    }	
	echo $html;


}
if ($tab == 'print_declaration') {
    $html = ''; // Initialize HTML content
    $file_name = ''; // Initialize file name

    // Uncomment and adapt the database fetching logic as needed
    
    $sql = "SELECT * FROM " . MEMBERS . " where id ='".$id."'";
    $result = $mysqli->executeQry($sql);
    $rows = $mysqli->fetch_assoc($result);
       
   /*  
    $html .= '
    <div class="col-md-6">
      <p><strong>Full Name:</strong> '.$rows['name'].'</p>
      <p><strong>Age:</strong> '.$rows['age'].'</p>
      <p><strong>Email Address:</strong> '.$rows['address'].'</p>
    </div>
    <div class="col-md-6 text-end">
     <img src="'.APPLICATION_URL.'uploads/profile/'.$rows['picture'].'" alt="Member Photo" class="img-fluid" width="100" height="100">
    </div>

  <h3>CERTIFICATE FROM THE DOCTOR:</h3>
    <p>It is certified that Mr./Mrs. _________________ is not suffering from any _________________ chronic/contiguos disease or any disablity which prevents him/her from gymnasium and exercise. As such he/she may be allowed to gymnasium and exercise.</p><br><br><p>Doctor Signature________<br><br><p> Name & Stamp with regn no._______</p>
'; */

    // Generate a unique file name for the PDF
    $file_name = 'Declaration_' . date("Ymd") . rand() . ".pdf";

    // HTML content for the declaration form with CSS
    $html_content = '
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>'.$file_name.'</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table {
        font-size: x-small;
    }
    tfoot tr td {
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray;
    }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td align="center">
<img src="'.APPLICATION_URL.'backend/images/logos/logo.png" class="logo-abbr center" width="70" height="52"/>
            <h3>Evosapiens Movement</h3>
        </td>
    </tr>
  </table>

  <br/>

  <!-- Replace divs with table structure -->
  <table width="100%">
    <tr>
        <td colspan="2">
            <table width="100%">
                <tr>
                    <td>
                        <p><strong>Full Name:</strong> '.$rows['name'].'</p>
                        <p><strong>Age:</strong> '.$rows['age'].'</p>
                        <p><strong>Mobile No:</strong> '.$rows['mobile'].'</p>
                        <p><strong>Email:</strong> '.$rows['email'].'</p>
                        <p><strong>Address:</strong> '.$rows['address'].'</p>
                    </td>
                    <td align="right">
                        <img src="'.APPLICATION_URL.'backend//uploads/profile/'.$rows['picture'].'" alt="Member Photo" class="img-fluid" width="100" height="100">
                    </td>
                </tr>
            </table>
        </td>
    </tr>

   <tr>
    <td colspan="2">
        <h3 style="text-align: center;">CERTIFICATE FROM THE DOCTOR:</h3>
        <p>It is certified that Mr./Mrs. _________________ is not suffering from any _________________ chronic/contiguous disease or any disability which prevents him/her from gymnasium and exercise. As such, he/she may be allowed to gymnasium and exercise.</p>
        <br>
        <p>Doctor Signature ________</p>
        <br>
        <p>Name & Stamp with regn no. _______</p>
        <br>
    </td>
</tr>

<tr>
    <td colspan="2" style="padding-top: 50px;">
        <div>
            <p style="text-align: center; color: red; font-size: larger; font-weight: bold;">Important Rules & Regulations</p>
            <p style="font-style: oblique; color: red;">1. The completed doctor-filled declaration form must be submitted within 3 days of its generation; otherwise, it will be deemed invalid.</p>
            <p style="font-style: oblique; color: red;">2. Fees once paid are non-returnable, non-refundable, and non-transferrable under any circumstances.</p>
            <p style="font-style: oblique; color: red;">3. Guests accompanied by members will be allowed entry based on gym capacity, after payment. Only one guest per member will be permitted entry after payment.</p>
            <p style="font-style: oblique; color: red;">4. All instructions given by the in-charge or trainer must be strictly followed. The manager or trainer reserves the right to refuse any person for misbehaving or infringing upon the rules.</p>
            <p style="font-style: oblique; color: red;">5. The gym remains closed every Tuesday.</p>
            <p style="font-style: oblique; color: red;">6. Any violation of these rules and regulations will result in the immediate and permanent cancellation of membership.</p>
            <br>
            <br>
			<table style="width: 100%;">
                <tr>
                    <td><p>Member Signature _______</p></td>
                    <td style="text-align: right;"><p>Guardian Signature _______</p></td>
                </tr>
            </table>
			<br>
            <br>
            <p>This declaration form is generated by Evosapiens Movement.</p>
            <p style="font-style: oblique;">Declaration Generated on - ' . date("d/m/Y h:i") . '</p>
            
           
        </div>
    </td>
</tr>


  </table>

</body>
</html>

';
	//print_r($html_content);exit;
    // Generate PDF using the HTML content
    $file = $mysqli->create_pdf($html_content, ABSOLUTE_ROOT . $file_name,'A4');

    // Return the path to the generated PDF file
    echo ABSOLUTE_ROOT_DOWNLOAD . $file_name;
}
if ($tab == 'print_invoice') {
    $html = ''; // Initialize HTML content
    $file_name = ''; // Initialize file name

    // Uncomment and adapt the database fetching logic as needed
    
    $sql = "SELECT * FROM " . MEMBERS . " where id ='".$id."'";
    $result = $mysqli->executeQry($sql);
    $rows = $mysqli->fetch_assoc($result);
       
    $plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$rows['plan_id'].'"');
	$sql123 = "select count(id) as count_rows from ".MEMBERS." where member_id = '".$rows['member_id']."'";
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
    $file_name = 'Invoice_' . date("Ymd") . rand() . ".pdf";

    $html_content = '<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>'.$file_name.'</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        
        <td align="left">
            <img src="'.APPLICATION_URL.'backend/images/logos/logo.png"  class="logo-abbr center" width="70" height="52"/><h3>Evosapiens Movement</h3>
            
        </td>
    </tr>

  </table>

  <table width="100%">
    <tr>
        <td><strong>Billed To:</strong> '.$rows['name'].'</td>
    </tr><tr>
        <td><strong>Phone No :</strong> '.$rows['mobile'].'</td>
        
    </tr><tr>
        <td><strong>Email :</strong> '.$rows['email'].'</td>
        
    </tr><tr>
       <td><strong>Membership ID:</strong> '.$rows['member_id'].'</td>
        
    </tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Plan</th>
        <th>Base Price</th>
        <th>Duration</th>
        <th>Members(s)</th>
        <th>Paid</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>'.$plan_details['title'].'</td>
        <td align="right">'.$plan_details['price'].'</td>
        <td align="right">'.$rows['start_date'].' - '.$rows['end_date'].'</td>
        <td align="right">'.$num.'</td>
        <td align="right">'.$rows['discounted_price'].'</td>
      </tr>
     
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4"></td>
            <td align="right">Subtotal </td>
            <td align="right">'.$rows['discounted_price'].'</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td align="right">Total </td>
            <td align="right" class="gray">'.$rows['discounted_price'].'</td>
        </tr>
    </tfoot>
  </table>
  <table width="100%">
    <tr>
        <td>
            <p><strong>Note:</strong> Fees once paid are non-returnable, non-transferable, and non-refundable under any circumstances.</p>
            <p>This invoice is generated by Evosapiens Movement.</p>
            <p style="font-style: oblique;">Invoice Generated on - 18/04/2024 02:15</p>
        </td>
    </tr>
</table>


</body>
</html>';
	//print_r($html_content);exit;
    // Generate PDF using the HTML content
    $file = $mysqli->create_pdf($html_content, ABSOLUTE_ROOT_INV . $file_name,'A5');

    // Return the path to the generated PDF file
    echo ABSOLUTE_ROOT_INV_DOWNLOAD . $file_name;
}

?>