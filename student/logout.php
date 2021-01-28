<?php	
	session_start();
	include '../inc/db.php';
	$user_id = $_SESSION['userSession'];
	
	unset($_SESSION['userSession']);
	session_destroy();
	//Lets chnage the boolean in db for logged out user
	Database::getInstance()->logout_user($user_id);
	header("Location: ../index.html");
	exit;
?>