<?php
	define("ABSOLUTE_ROOT", ABSOLUTE_ROOT_PATH.'/declarations/');
	define("ABSOLUTE_ROOT_INV", ABSOLUTE_ROOT_PATH.'/invoice/');
	define("ABSOLUTE_ROOT_DOWNLOAD", APPLICATION_URL.'declarations/');
	define("ABSOLUTE_ROOT_INV_DOWNLOAD", APPLICATION_URL.'invoice/');
	//print_r(ABSOLUTE_ROOT_PATH);exit;
	
spl_autoload_register(function ($class_name) {
	$file =  ABSOLUTE_ROOT_PATH.'includes/classes/'.$class_name . '.php';
    if(file_exists($file))
    require_once($file);
	else
	die($file.' File Not Found');
});

?>