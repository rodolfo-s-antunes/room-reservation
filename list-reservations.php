<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}

include "database.php";

if (isset ($_POST["reservation_date"]))
{
	$reservation_date = $_POST["reservation_date"];
	$db_ops = new DatabaseOperations ();
	$available_rooms = $db_ops->get_room_descriptions ();
	$reservations_by_date = $db_ops->get_reservations_by_date ($reservation_date);

	echo "<table class='reservation_grid'>";
	echo "<tr><th class='reservation_grid_room'>Sala \ Hora</td>";
	foreach (range (6, 21) as $hour)
	{
		echo "<th class='reservation_grid_hour'>" . $hour . "h</td>";
	}
	echo "</tr>";
	$rooms = array_keys ($available_rooms);
	sort ($rooms);
	foreach ($rooms as $room)
	{
		echo "<tr><th class='reservation_grid_room'>$room</td>";
		foreach (range (6, 21) as $hour)
		{
			if (array_key_exists ($room, $reservations_by_date) && array_key_exists ($hour, $reservations_by_date[$room]))
			{
				$reserved_user = $reservations_by_date[$room][$hour]["fullname"];
				$reserved_id = $reservations_by_date[$room][$hour]["id"];
				echo "<td class='reservation_grid_busy'>";
				echo "<a class='busy' title='Reservado por ${reserved_user}' href='javascript:RequestCancelReservation($reserved_id)'>&times;</a>";
			}
			else
			{
				$roomid = $available_rooms[ $room ]["id"];
				echo "<td class='reservation_grid_free'>";
				echo "<a class='free' href='javascript:RequrestReservation($roomid,\"$reservation_date\",$hour)'>&bull;</a>";
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	echo "<p>";
	echo "Um horário marcado com um &bull; e fundo verde indica um horário livre.<br/>";
	echo "Um horário marcado com um &times; e fundo vermelho indica um horário ocupado.<br/>";
	echo "Clique em um horário livre para efetuar uma reserva.<br/>";
	echo "Clique em um horário ocupado para cancelar uma reserva se você a tiver efetuado.<br/>";
	echo "Mantenha o cursor sobre um horário ocupado para ver o nome do usuário que efetuou a reserva.";
	echo "</p>";
}

?>
