<?php
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