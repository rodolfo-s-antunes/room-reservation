<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}
?>

<html>
<header><title>Reservas de Salas</title></header>
<body>

<h3><?php echo "Olá, " . $_COOKIE['login_fullname']; ?></h3>
<p>Escolha uma das operações abaixo:</p>

<?php if ($_COOKIE['login_admin']) { ?>

<p><a href="#">Gerenciar usuários</a></p>
<p><a href="#">Gerenciar salas</a></p>

<?php } ?>

<p><a href="#">Gerenciar reservas</a></p>
</body>
</html>


