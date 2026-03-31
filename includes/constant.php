<?php
date_default_timezone_set("Asia/Kolkata");
if(!isset($_SESSION))
{
  session_start();
}


 if($_SERVER['HTTP_HOST'] == 'elitecodeexperts.com' || $_SERVER['HTTP_HOST'] == 'elitecodeexperts.com')
{
	
	define('APPLICATION_URL',"https://elitecodeexperts.com/");
	define('APPLICATION_domain',"swimgymacademy.com");
	define('ENVIRONMENT',"LIVE");
	################## DB ##########################################
	define('HOST', 'localhost');
	define('USER', 'u583683241_swimgym');
	define('PASSWORD', 'jG#97r5V39hv'); 
	define('DATABASE', 'u583683241_swimdata');
	################## DB ############################################
    define("ABSOLUTE_ROOT_PATH", $_SERVER['DOCUMENT_ROOT'].'/backend/');
    define('Email_domain',"swimgymacademy.com");
	define('FROM_EMAIL', 'info@'.Email_domain);
	define('EMAIL_HOST', 'smtp.hostinger.com');
    define('USER_EMAIL', 'admin@swimgymacademy.com');
	define('MAIL_PASSWORD', 'Sgar@2024'); 
	define('PORT', 587);
}

else if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'www.localhost')
{
	define('APPLICATION_URL',"https://localhost/gym/");
	define('APPLICATION_domain',"evosapiensmovement");
	define('ENVIRONMENT',"LOCAL");
	################## DB ##########################################
	define('HOST', 'localhost');
	define('USER', 'root');
	define('PASSWORD', ''); 
	define('DATABASE', 'gym');
	################## DB ############################################
    define("ABSOLUTE_ROOT_PATH", $_SERVER['DOCUMENT_ROOT'].'/gym/');
}
else
{
    echo "Invalid config"; exit;
}
if(ENVIRONMENT == "LOCAL" )
{
      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
}
else
{
      error_reporting(0);
      //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
}
define('APPLICATION_NAME',"Evosapiens Movement");
define('APP_NAME',"Evosapiens Movement");
define('COMPANY_NAME',"Evosapiens Movement");
define('APP_FULL_NAME',"Evosapiens Movement");
define('DEVELOPER_EMAIL', 'lavii15march@gmail.com'); 
define('DEVELOPER_NAME', 'Aman Gahlawat'); 
define('EXCEPTION_EMAIL', 'exception@'.APPLICATION_domain);
define('USERS', 'tbl_user');
define('FAMILY_ID', 'tbl_family_id');
define('PLANS', 'tbl_plans');
define('MEMBERS', 'tbl_members');
define('EMPLOYEES', 'employees');
define('PT_PLANS', 'pt_plans');
define('PT_MEMBERS', 'pt_members');
define('PT_SESSIONS', 'pt_sessions');
define('CLASSES', 'classes');
define('CLASS_SCHEDULE', 'class_schedule');
define('CLASS_MEMBERS', 'class_members');
define('MESSAGE_LOGS', 'message_logs');
define('MEMBERSHIP_TYPES', 'membership_types');

// WhatsApp (provider-based; configure later)
define('WHATSAPP_ENABLED', '0'); // set to '1' when you configure API
define('WHATSAPP_API_URL', '');
define('WHATSAPP_TOKEN', '');
define('WHATSAPP_FROM', '');
define('RECENT', 'tbl_recent_members');
define('REVENUE', 'tbl_revenue');
define('ENQUIRIES', 'tbl_enquiry');
define('HISTORY', 'tbl_membership_history');
define('NOTIFICATIONS', 'tbl_sent_notifications');
define('ATTENDANCE', 'tbl_attendance');
define('APPLICATION_FULL_NAME',"Evosapiens Movement");
define('LOGO_PATH',APPLICATION_URL."images/logos/logo.png");
define('DEFAULT_PROFILE_PICTURE',APPLICATION_URL."images/avatar/1.png");
define('LOGO_ALT',APPLICATION_FULL_NAME);
define('FAVICON_PATH',APPLICATION_URL."images/logos/logo.png");
use includes\PHPMailer\PHPMailer\PHPMailer;
use includes\PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once(ABSOLUTE_ROOT_PATH.'/dompdf/autoload.inc.php');
 
use Dompdf\Dompdf;
$dompdf = new Dompdf();
?>