<?php
session_start();
if (!isset ($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header ('Location: login.php');
	exit ();
}

include "database.php";
?>

<h2>Usuários atualmente cadastrados no sistema:</h2>

<table class='users_rooms'>

<tr>
	<th class='users_rooms'>Nome de usuário</td>
	<th class='users_rooms'>Nome completo</td>
	<th class='users_rooms'>Administrador?</td>
	<th class='users_rooms'>Operações</td>
</tr>

<?php
$db_ops = new DatabaseOperations ();

foreach ($db_ops->get_all_users_info () as $user_info)
{
	echo "<tr>";
	echo "<td class='users_rooms'>" . $user_info["username"] . "</td>";
	echo "<td class='users_rooms'>" . $user_info["fullname"] . "</td>";
	$admin_print_string = ($user_info["admin"]) ? "Sim" : "Não";
	echo "<td class='users_rooms'>" . $admin_print_string . "</td>";
	echo "<td class='users_rooms'><a href='javascript:EditUserInterface(" . $user_info["id"] . ")'>editar ou remover</a></td>";
	echo "</tr>";
}
?>

<tr>
<td class='users_rooms'><a href="javascript:LoadInterface('edit-users.php')">Cadastrar<br/>novo usuário</a></td>
</tr>

</table>
