<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}
?>

<html>
<head>
<title>Reservas de Salas</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-3.0.0.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="reservations.js"></script>
<script src="main.js"></script>
</head>
<body>

<h3>Sistema de Reserva de Salas</h3>
<p><?php echo "Olá, " . $_COOKIE['login_fullname']; ?></p>

<table>
<tr>
<?php if ($_SESSION['admin']) { ?>
<td><a href="javascript:LoadInterface('list-users.php')">Gerenciar usuários</a></td>
<td><a href="javascript:LoadInterface('list-rooms.php')">Gerenciar salas</a></td>
<?php } ?>
<td><a href="javascript:LoadInterface('reservations.php')">Gerenciar reservas</a></td>
</tr>
</table>

<div id="main_interface">
<p>Olá! Por favor, selecione uma das opções acima para acessar as funcionalidades do sistema.</p>
</div>

</body>
</html>
