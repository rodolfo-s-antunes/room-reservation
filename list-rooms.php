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

<h3>Salas atualmente cadastradas no sistema:</h3>

<table>
<tr>
	<td>Número da sala</td>
	<td>Descrição</td>
</tr>

<?php
$db_ops = new DatabaseOperations ();

foreach ($db_ops->get_all_rooms_info () as $room_info)
{
	echo "<tr>";
	echo "<td>" . $room_info["number"] . "</td>";
	echo "<td>" . $room_info["description"] . "</td>";
	echo "<td><a href='edit-rooms.php?id_room=" . $room_info["id"] . "'>editar ou remover</a></td>";
	echo "<tr>";
}
?>

</table>

<p><a href="edit-rooms.php">Cadastrar nova sala</a></p>
<p><a href="index.php">Retornar à página inicial</a></p>

</body>
</html>
