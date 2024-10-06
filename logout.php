<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	setcookie("TOKEN--DONOTSHARE", "", time()-3600);
	header("Location: /");
	die();
?>