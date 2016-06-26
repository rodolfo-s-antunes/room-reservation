<?php
// First start the user session and verify if the user is authenticated.
// If not, redirect to the authentication interface on "login.php".
session_start();
if (!isset ($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header ('Location: login.php');
	exit ();
}

/*
 * This script present the list of all rooms currently registered in the system
 * and their information. It also enables the user to request the addition, update,
 * or removal of rooms through XHR calls to the "edit-rooms.php" script. This interface
 * is supposed to be accessed only by users registered as administrators in the system.
 * However, this verification is made by the "index.php" script, prior to invoking this
 * interface.
 */

include "database.php";
?>

<h2>Salas atualmente cadastradas no sistema:</h2>

<table class='users_rooms'>
<tr>
	<th class='users_rooms'>Número da sala</td>
	<th class='users_rooms'>Descrição</td>
	<th class='users_rooms'>Operações</td>
</tr>

<?php
$db_ops = new DatabaseOperations ();

// Obtain an array with the information of all rooms available in the system.
// For each room in the array, print a table row containing its information
// and an option to update or remove the room.
foreach ($db_ops->get_all_rooms_info () as $room_info)
{
	echo "<tr>";
	echo "<td class='users_rooms'>" . $room_info["number"] . "</td>";
	echo "<td class='users_rooms'>" . $room_info["description"] . "</td>";
	echo "<td class='users_rooms'><a href='javascript:EditRoomInterface(" . $room_info["id"] . ")'>editar ou remover</a></td>";
	echo "<tr>";
}

// Finally, include an option to register a new room at the bottom of the table.
?>

<tr>
<td class='users_rooms'><a href="javascript:LoadInterface('edit-rooms.php')">Cadastrar<br/>nova sala</a></td>
</tr>

</table>
