<?php	
	session_start();
	include '../inc/db.php';
	$uid = $_SESSION['userSession'];
	
	unset($_SESSION['userSession']);
	session_destroy();
	//Lets chnage the boolean in db for logged out user
	Database::getInstance()->logout_user($uid);
	header("Location: ../index.html");
	exit;
?>