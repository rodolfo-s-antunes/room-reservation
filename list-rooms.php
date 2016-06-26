<?php
session_start();
if (!isset ($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header ('Location: login.php');
	exit ();
}

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

foreach ($db_ops->get_all_rooms_info () as $room_info)
{
	echo "<tr>";
	echo "<td class='users_rooms'>" . $room_info["number"] . "</td>";
	echo "<td class='users_rooms'>" . $room_info["description"] . "</td>";
	echo "<td class='users_rooms'><a href='javascript:EditRoomInterface(" . $room_info["id"] . ")'>editar ou remover</a></td>";
	echo "<tr>";
}
?>

<tr>
<td class='users_rooms'><a href="javascript:LoadInterface('edit-rooms.php')">Cadastrar<br/>nova sala</a></td>
</tr>

</table>
