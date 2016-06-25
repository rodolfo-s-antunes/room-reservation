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
<script src="jquery.js"></script>
<script src="reservations.js"></script>
</head>
<body>

<h3>Visualizar reservas de salas:</h3>

<form method="post" action="list-reservations.php">
<p>Escolha a data para visualização: <input type="date" name="reservation_date" /></p>
<input type="submit" name="submit" value="Listar Reservas" />
</form>

<?php
include "database.php";

if (isset ($_POST["submit"]))
{
	$reservation_date = $_POST["reservation_date"];
	$db_ops = new DatabaseOperations ();
	$available_rooms = $db_ops->get_room_descriptions ();
	$reservations_by_date = $db_ops->get_reservations_by_date ($reservation_date);

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
				$reserved_user = $reservations_by_date[$room][$hour]["fullname"];
				$reserved_id = $reservations_by_date[$room][$hour]["id"];
				#echo "<a href='edit-reservations.php?reservation_id=$reserved_id' title='Reservado por $reserved_user.'>X</a>";
				echo "<a href='javascript:CancelReservation($reserved_id,0)'>X</a>";
			}
			else
			{
				$roomid = $available_rooms[ $room ]["id"];
				#echo "<a href='edit-reservations.php?reservation_date=$reservation_date&reservation_hour=$hour&reservation_room=$room&reservation_roomid=$roomid'>O</a>";
				echo "<a href='javascript:RequrestReservation($roomid,$room,\"$reservation_date\",$hour,0)'>O</a>";
			}
			echo "</td>";
		}
		echo "</tr><tr>";
	}
	echo "</table>";
}

?>

<div id="reservation_management" />

</body>
</html>