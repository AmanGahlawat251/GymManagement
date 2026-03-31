<?php
if(!isset($_SESSION))
{
    session_start();
}		 
unset($_SESSION['user_id']);
unset($_SESSION['login_id']);
unset($_SESSION['name']);	



session_destroy();//	exit;
echo "<script>window.location.href='index.php';</script>";
?>