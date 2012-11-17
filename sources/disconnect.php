<?php
	session_start();
	
	$_SESSION= array();
	
	setcookie("vbiblio", "", time()-3600);
	setcookie("vbiblio_check", "", time()-3600);

	session_destroy();
	
	header('Location:formLogin.php');
?>
