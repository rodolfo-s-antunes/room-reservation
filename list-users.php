<?php
session_start();
if (!isset ($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header ('Location: login.php');
	exit ();
}

include "database.php";
?>

<html>
<head><title>Reservas de Salas</title></head>
<body>

<h3>Usuários atualmente cadastrados no sistema:</h3>

<table>
<tr>
	<td>Nome de usuário</td>
	<td>Nome completo</td>
	<td>Administrador?</td>
</tr>

<?php
$db_ops = new DatabaseOperations ();

foreach ($db_ops->get_all_users_info () as $user_info)
{
	echo "<tr>";
	echo "<td>" . $user_info["username"] . "</td>";
	echo "<td>" . $user_info["fullname"] . "</td>";
	$admin_print_string = ($user_info["admin"]) ? "Sim" : "Não";
	echo "<td>" . $admin_print_string . "</td>";
	echo "<td><a href='edit-users.php?id_user=" . $user_info["id"] . "'>editar ou remover</a></td>";
	echo "<tr>";
}
?>

</table>

<p><a href="edit-users.php">Cadastrar novo usuário</a></p>
<p><a href="index.php">Retornar à página inicial</a></p>

</body>
</html>