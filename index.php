<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	require_once dirname(__FILE__).'/core/Ename.php';
	$ename = Ename::instance();
	$ename->run();
?>
