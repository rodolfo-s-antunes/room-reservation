<?php
// First start the user session and verify if the user is authenticated.
// If not, redirect to the authentication interface on "login.php".
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}

/*
 * This script handles the exibition of the room reservation grid, which allows
 * the user the view the current status of rooms for a given date and to request
 * new reservations or to cancel his own, already made reservations. This
 * interface is expected to be loaded through the "reservations.php" script via XHR.
 *
 * IMPORTANT: this script uses two constants defined in the "config.php" global
 * configuration file to control the available time slots for room reservations:
 * reservation_hour_min: defines the first hour in the day a room may be reserved
 * reservation_hour_min: defines the last hour in the day a room may be reserved
 */

// Include the configuration variables and the database module.
include "config.php";
include "database.php";

// Check if a date was received as a POST parameter.
if (isset ($_POST["reservation_date"]))
{
	$reservation_date = $_POST["reservation_date"];
	// Instantiate the class to handle database operations
	$db_ops = new DatabaseOperations ();
	// Obtain an array with all rooms available for reservation in the system
	$available_rooms = $db_ops->get_room_descriptions ();
	// Obtain a matrix that presents all rooms and its respective reservations
	// for the date passed as parameter to the script
	$reservations_by_date = $db_ops->get_reservations_by_date ($reservation_date);

	echo "<table class='reservation_grid'>";
	echo "<tr><th class='reservation_grid_room'>Sala \ Hora</td>";
	// Print the time slots available for room reservation as headers of the
	// table. The range of available time slots is controlled by the constants
	// available in the system configuration file, "config.php".
	foreach (range (reservation_hour_min, reservation_hour_max) as $hour)
	{
		echo "<th class='reservation_grid_hour'>" . $hour . "h</td>";
	}
	echo "</tr>";
	// Create an array only with the available room numbers and sort it.
	$rooms = array_keys ($available_rooms);
	sort ($rooms);
	// Cicle through each room in the system
	foreach ($rooms as $room)
	{
		echo "<tr><th class='reservation_grid_room'>$room</td>";
		// For each room, cicle through each available time slot and output
		// the status of the room at that time in the table.
		foreach (range (reservation_hour_min, reservation_hour_max) as $hour)
		{
			// Check if the current room has a reservation registered for the
			// current time slot.
			if (array_key_exists ($room, $reservations_by_date) && array_key_exists ($hour, $reservations_by_date[$room]))
			{
				// If a reservation is found, mark the table cell with an "x" symbol.
				// Create an XHR hyperlink to allow the user the cancel the reservation.
				// Also, set as hyperlink tooltip the full name of the user that
				// created the reservation.
				$reserved_user = $reservations_by_date[$room][$hour]["fullname"];
				$reserved_id = $reservations_by_date[$room][$hour]["id"];
				echo "<td class='reservation_grid_busy'>";
				echo "<a class='busy' title='Reservado por ${reserved_user}' href='javascript:RequestCancelReservation($reserved_id)'>&times;</a>";
			}
			else
			{
				// If no reservation is found, mark the table cell with a "o" symbol.
				// Create an XHR hyperlink to allow the user to create a new reservation
				// for this room.
				$roomid = $available_rooms[ $room ]["id"];
				echo "<td class='reservation_grid_free'>";
				echo "<a class='free' href='javascript:RequrestReservation($roomid,\"$reservation_date\",$hour)'>&bull;</a>";
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	// Below the room reservation grid, output some information about how to interpret the
	// information available in the grid and how to create or cancel reservations.
	echo "<p>";
	echo "Um horário marcado com um &bull; e fundo verde indica um horário livre.<br/>";
	echo "Um horário marcado com um &times; e fundo vermelho indica um horário ocupado.<br/>";
	echo "Clique em um horário livre para efetuar uma reserva.<br/>";
	echo "Clique em um horário ocupado para cancelar uma reserva se você a tiver efetuado.<br/>";
	echo "Mantenha o cursor sobre um horário ocupado para ver o nome do usuário que efetuou a reserva.";
	echo "</p>";
}

?>
