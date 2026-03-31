<?php
set_time_limit(300);
if(!isset($_SESSION))
{
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
if($current_page != 'index.php' && $current_page != 'ajax.php' && $current_page != 'table_response.php' && $current_page != 'logs.php')
{
	$URL="index.php";
	echo "<script>location.href='$URL'</script>";
	exit;
}

extract($_POST);
extract($_GET);
extract($_REQUEST);


/*
if ($mysqli->validatePermCode($pagecode) != 'Y')
{
  $URL = 'index.php?' . $mysqli->encode("stat=403");
  echo "<script>location.href='$URL'</script>";
  exit;
}
*/

if(!isset($_SESSION))
{
    echo "<script language='javascript' type='text/javascript'>";
    //echo "alert('Your Session has been Expired');";
    echo "</script>";
    $URL="index.php?".$mysqli->encode('stat=login');
	echo "<script>location.href='$URL'</script>";
	exit;
}

if(isset($_SESSION))
{ 
	if(isset($_SESSION['user_id']))
	{
		if($_SESSION['user_id']=='' || empty($_SESSION['user_id']))
		{
			echo "<script language='javascript' type='text/javascript'>";
		//	echo "alert('Your Session has been Expired');";
			echo "</script>";
			$URL="index.php?".$mysqli->encode('stat=login');
			echo "<script>location.href='$URL'</script>";
			exit;
		}
	}
	else
	{
			echo "<script language='javascript' type='text/javascript'>";
			//echo "alert('Your Session has been Expired');";
			echo "</script>";
			$URL="index.php?".$mysqli->encode('stat=login');
			echo "<script>location.href='$URL'</script>";
			exit;
	}
}
    ?>