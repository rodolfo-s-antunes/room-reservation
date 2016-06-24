<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}
echo "Olá, " . $_COOKIE['login_fullname'] . "!\n";
?>