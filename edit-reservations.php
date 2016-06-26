<?php
// First start the user session and verify if the user is authenticated.
// If not, redirect to the authentication interface on "login.php".
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}

/*
 * This PHP script contains all the code related to edition of reservations.
 * That is, the operations related to the creation and removal of room
 * reservations by users. This script is always invoed thorugh XHR calls
 * from the "list-reservations.php" interface, and its responses are handled
 * by callbacks implemented in the "reservations.js" javascript code. All
 * data sent to this script is expected to be received through POST methods.
 */

// include and instantiate the class that handles database operations.
include "database.php";
$db_ops = new DatabaseOperations ();

/*
 * check if the the POST headers include parameters for the date, hour, and id
 * of the room to be reserved. In that case, assume that a new room reservation
 * should be registered.
 */
if (isset ($_POST['reservation_date']) && isset ($_POST['reservation_hour']) && isset ($_POST['reservation_roomid']))
{
	/*
	 * Check if the user requesting the reservation (obtained from the cookie
	 * created in the login) does not already have a room reservation for the date
	 * and time slot requested in the POST parameters. If such a reservation already
	 * exists, return an alert message indicating that the reservation is not
	 * possible.
	 */
	if ($db_ops->check_user_reservations ($_COOKIE["login_username"], $_POST['reservation_date'], $_POST['reservation_hour'])) {
		echo "<h2 class='alert_notok' id='alert_message'>Impossível reservar: o usuário já possui outra sala reservada para o mesmo horário.</h2>";
	}
	else
	{
		/*
		 * If the user is "free" on the requested time slot, proceed with the reservation
		 * store some POST parameters in variables for ease of use, and obtain additional
		 * info about the requested room from the database.
		 */
		$roomid = $_POST['reservation_roomid'];
		$room = $db_ops->get_room_info ($roomid)["number"];
		$date = $_POST['reservation_date'];
		$hour = $_POST['reservation_hour'];
		/*
		 * Before registering the actual reservation on the database, show an alert to the
		 * user requesting to confirm if the reservation should be realy registered. If
		 * the user confirms the reservation through the alert, proceed to register it 
		 * into the database and inform the user that the reservation is confirmed.
		 */
		if (!$_POST['reservation_confirm'])
		{
			echo "<h2 class='alert_ok' id='alert_message'>Confirma reserva da sala $room para as ${hour}h do dia ${date}?</h2>";
			echo "<p class='alert_ok'><a href='javascript:ConfirmReservation($roomid,\"$date\",$hour)'>confirmar</a> - <a href='javascript:HideAlert()'>cancelar</a></p>";
		}
		else
		{
			$db_ops->add_reservation ($_COOKIE["login_userid"], $roomid, $date, $hour);
			echo "<h2 class='alert_ok' id='alert_message'>Reserva confirmada.</h2>";
		}
	}
}

/*
 * If the POST parameters do not contain the data for a new reservation, check
 * if the script receives the id of an already existing reservation. In that
 * case, assume the user is requesting to remove this reservation from the system.
 */
elseif (isset ($_POST['reservation_id']))
{
	// First obtain the complete reservation info from the database.
	$reservation_info = $db_ops->get_reservation ($_POST['reservation_id']);
	/*
	 * First check if the user that is requesting the reservation removal is the
	 * same that registered the reservation. If not, return an alert informing
	 * that is impossible to remove the reservation.
	 */
	if ($reservation_info["username"] != $_COOKIE["login_username"])
	{
		echo "<h2 class='alert_notok' id='alert_message'>Impossível cancelar reserva: apenas o usuário que realizou a reserva pode fazê-lo.</h2>";
	}
	else
	{
		/*
		 * If the user is allowed to remove the reservation, first send an alert
		 * to the user requesting to confirm if the reservation should be removed
		 * from the system. If the user confirms the removal through the alert,
		 * proceed (in the else statement) to remove the reservation in the database
		 * and inform the user that the operation was successful.
		 */
		if (!$_POST['reservation_confirm'])
		{
			$room = $reservation_info["number"];
			$date = $reservation_info["date"];
			$hour = $reservation_info["hour"];
			$resid = $reservation_info["id"];
			echo "<h2 class='alert_ok' id='alert_message'>Confirma cancelamento da reserva da sala $room para as ${hour}h do dia ${date}?</h2>";
			echo "<p class='alert_ok'><a href='javascript:ConfirmCancelReservation($resid)'>confirmar</a> - <a href='javascript:HideAlert()'>cancelar</a></p>";
		}
		else
		{
			$db_ops->delete_reservation ($_POST['reservation_id']);
			echo "<h2 class='alert_ok' id='alert_message'>Reserva cancelada.</h2>";
		}
	}
}

?>