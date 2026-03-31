<?php
if(!isset($_SESSION))
{
  session_start();
}
require_once('includes/constant.php');
require_once('includes/autoload.php');


if(ENVIRONMENT == "LOCAL")
{
      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
}
else
{
      error_reporting(0);
}
//require_once('includes/classes/class.json.php');
$url = $_SERVER['REQUEST_URI'];

$mysqli = new MySqliDriver();
$Qstring = $mysqli->option($url); 
//print_r($Qstring); exit;


extract($Qstring);
register_shutdown_function(array(&$mysqli, 'ShutDown')); 
if(!isset($stat))
{
  $stat = "";  
}
#echo $mysqli->encode('pass@123');
//echo $mysqli->decode($stat);

$Encript_arr = array();
$Encript_arr[] = $mysqli->encode('login');
$Encript_arr[] = $mysqli->encode('table_response');
$Encript_arr[] = $mysqli->encode('ajax');
$Encript_arr[] = $mysqli->encode('custom_ajax');
$Encript_arr[] = $mysqli->encode('logout');
$Encript_arr[] = $mysqli->encode('Dashboard');	
$Encript_arr[] = $mysqli->encode('messages');		
$Encript_arr[] = $mysqli->encode('plans');		
$Encript_arr[] = $mysqli->encode('users');		
$Encript_arr[] = $mysqli->encode('employees');		
$Encript_arr[] = $mysqli->encode('pt_plans');
$Encript_arr[] = $mysqli->encode('pt_members');
$Encript_arr[] = $mysqli->encode('error_404');
$Encript_arr[] = $mysqli->encode('classes');
$Encript_arr[] = $mysqli->encode('class_schedule');
$Encript_arr[] = $mysqli->encode('class_members');
$Encript_arr[] = $mysqli->encode('messaging');
$Encript_arr[] = $mysqli->encode('membership_types');
$Encript_arr[] = $mysqli->encode('enquiry');
$Encript_arr[] = $mysqli->encode('feedback');
$Encript_arr[] = $mysqli->encode('export_ajax');
$Encript_arr[] = $mysqli->encode('trainer_dashboard');


if (!empty($stat)) { 
    if (in_array($stat, $Encript_arr)) 
    {
		$stat = $mysqli->decode($stat);
		 
    }
	// Role-based route restriction.
	// ADMIN is treated as SUPERADMIN (backward compatibility).
	$role = $_SESSION['user_type'] ?? '';
	$role = ($role === 'ADMIN') ? 'SUPERADMIN' : $role;
	if ($role === 'RECEPTIONIST') {
		$allowedStats = ['login', 'logout', 'error_404', 'Dashboard', 'users', 'table_response', 'ajax', 'custom_ajax', 'export_ajax'];
		if (!in_array($stat, $allowedStats, true)) {
			include "error_404.php";
			exit;
		}
	} elseif ($role === 'TRAINER') {
		$allowedStats = ['login', 'logout', 'error_404', 'trainer_dashboard', 'pt_members', 'table_response', 'ajax', 'custom_ajax', 'export_ajax'];
		if (!in_array($stat, $allowedStats, true)) {
			include "error_404.php";
			exit;
		}
	}
	 
    switch ($stat) {
		
        case "login":
            include "login.php";
            break;
		
		case "Dashboard":
            include "Dashboard.php";
            break;
			
		case "users":
            include "users.php";
            break;
		
		case "plans":
            include "plans.php";
            break;

		case "employees":
            include "employees.php";
            break;

		case "pt_plans":
            include "pt_plans.php";
            break;

		case "pt_members":
            include "pt_members.php";
            break;
		case "classes":
            include "classes.php";
            break;
		case "class_schedule":
            include "class_schedule.php";
            break;
		case "class_members":
            include "class_members.php";
            break;

		case "messaging":
			include "messaging.php";
			break;

		case "membership_types":
			include "membership_types.php";
			break;

		case "trainer_dashboard":
			include "trainer_dashboard.php";
			break;
			
		case "enquiry":
            include "enquiry.php";
            break;
		
		case "feedback":
            include "feedback.php";
            break;
		
		case "messages":
            include "messages.php";
            break;
		
		case "ajax":
            include "includes/ajax.php";
            break;
			
		case "export_ajax":
            include "includes/export_ajax.php";
            break;
			
		case "custom_ajax":
            include "includes/custom_ajax.php";
            break;
		
		case "table_response":
            include "includes/table_response.php";
            break;
		
		case "logout":
            include "logout.php";
            break;
			
			case "error_404":
            include "error_404.php";
            break;
		
		default:
            include "error_404.php";		
	}
	
}
else if(!empty($_SESSION['user_type']))
{
	// Default route when `stat` is not provided.
	$user_type = $_SESSION['user_type'];
	if($user_type == 'TRAINER') {
		include "trainer_dashboard.php";
		exit;
	}
	include 'Dashboard.php';
}
else
{
	include "login.php";
	exit;
}
?>