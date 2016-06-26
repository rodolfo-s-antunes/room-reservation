<?php
// First start the user session and verify if the user is authenticated.
// If not, redirect to the authentication interface on "login.php".
session_start();
if (!isset ($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header ('Location: login.php');
	exit ();
}

/*
 * This script present the list of all users currently registered in the system
 * and their information. It also enables the user to request the addition, update,
 * or removal of users through XHR calls to the "edit-users.php" script. This interface
 * is supposed to be accessed only by users registered as administrators in the system.
 * However, this verification is made by the "index.php" script, prior to invoking this
 * interface.
 */

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

// Obtain an array with the information of all users available in the system.
// For each user in the array, print a table row containing its information
// and an option to update or remove the user.
foreach ($db_ops->get_all_users_info () as $user_info)
{
	echo "<tr>";
	echo "<td class='users_rooms'>" . $user_info["fullname"] . "</td>";
	echo "<td class='users_rooms'>" . $user_info["username"] . "</td>";
	$admin_print_string = ($user_info["admin"]) ? "Sim" : "Não";
	echo "<td class='users_rooms'>" . $admin_print_string . "</td>";
	echo "<td class='users_rooms'><a href='javascript:EditUserInterface(" . $user_info["id"] . ")'>editar ou remover</a></td>";
	echo "</tr>";
}

// Finally, include an option to register a new user at the bottom of the table.
?>

<tr>
<td class='users_rooms'><a href="javascript:LoadInterface('edit-users.php')">Cadastrar<br/>novo usuário</a></td>
</tr>

</table>
