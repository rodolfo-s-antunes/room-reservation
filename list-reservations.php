<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}
?>

<html>
<head><title>Reservas de Salas</title></head>
<body>

<h3>Visualizar reservas de salas:</h3>

<form method="post" action="list-reservations.php">
<p>Escolha a data para visualização: <input type="date" name="reservation_date" /></p>
<input type="submit" name="submit" value="Entrar" />
</form>

<?php
include "database.php";

if (isset ($_POST["submit"]))
{
	$db_ops = new DatabaseOperations ();
	$available_rooms = $db_ops->get_room_descriptions ();
	$reservations_by_date = $db_ops->get_reservations_by_date ($_POST["reservation_date"]);

	echo "<table>";
	echo "<tr><td>Sala</td>";
	foreach (range (6, 21) as $hour)
	{
		echo "<td>" . $hour . "h</td>";
	}
	echo "</tr><tr>";
	$rooms = array_keys ($available_rooms);
	sort ($rooms);
	foreach ($rooms as $room)
	{
		echo "<td>$room</td>";
		foreach (range (6, 21) as $hour)
		{
			echo "<td>";
			if (array_key_exists ($room, $reservations_by_date) && array_key_exists ($hour, $reservations_by_date[$room]))
			{
				$reserve_user = $reservations_by_date[$room][$hour]["fullname"];
				echo "<a href='#' title='Reservado por $reserve_user.'>X</a>";
			}
			else
				echo "<a href='#'>O</a>";
			echo "</td>";
		}
		echo "</tr><tr>";
	}
	echo "</table>";
}

?>

</body>
</html>