<?php
/*
 * This is a simple PHP script that handles the procedures to finalize
 * a session from the user in the system. After destroying the user
 * session information, this script redirects the ser to the "login.php" script.
 */
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}
else {
	$_SESSION = array();
	session_destroy();
	header('Location: login.php');
	exit();
}
?>