<?php
// First start the user session and verify if the user is authenticated.
// If not, redirect to the authentication interface on "login.php".
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}

/*
 * This PHP script presents the main system interface to the user. It presents
 * a welcome message and a series of links with javascript handlers that invoke
 * XHR methods to load the remaining interfaces in the space defined by the
 * "main_interface" div. Additionally, it contains an "alert" div, which is
 * used to display alerts from the remaining interfaces, also via calls to
 * XHR methods.
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Sistema de Reserva de Salas</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" />
<script src="//code.jquery.com/jquery-3.0.0.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="reservations.js"></script>
<script src="main.js"></script>
</head>
<body onload='OnMainLoad()'>

<div id='wrapper'>

<h1 class='header'>Sistema de Reserva de Salas</h1>
<p>
Olá, <?php echo $_COOKIE['login_fullname']; ?>!</br>
Por favor, selecione uma das opções abaixo para acessar as funcionalidades do sistema.
</p>

<table class='main_options'>
<tr>
<?php if ($_SESSION['admin']) { ?>
<td class='main_options'><a href="javascript:LoadInterface('list-users.php')">Gerenciar usuários</a></td>
<td class='main_options'><a href="javascript:LoadInterface('list-rooms.php')">Gerenciar salas</a></td>
<?php } ?>
<td class='main_options'><a href="javascript:LoadInterface('reservations.php')">Gerenciar reservas</a></td>
<td class='main_options'><a href="logout.php">Sair do sistema</a></td>
</tr>
</table>

<div id="alerts"></div>

<div id="main_interface" ></div>

</div>

</body>
</html>
