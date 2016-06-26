<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}

include "database.php";
$db_ops = new DatabaseOperations ();

if (isset ($_POST['reservation_date']) && isset ($_POST['reservation_hour']) && isset ($_POST['reservation_roomid']))
{
	if ($db_ops->check_user_reservations ($_COOKIE["login_username"], $_POST['reservation_date'], $_POST['reservation_hour'])) {
		echo "<h2 class='alert_notok' id='alert_message'>Impossível reservar: o usuário já possui outra sala reservada para o mesmo horário.</h2>";
	}
	else
	{
		$roomid = $_POST['reservation_roomid'];
		$room = $db_ops->get_room_info ($roomid)["number"];
		$date = $_POST['reservation_date'];
		$hour = $_POST['reservation_hour'];
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

elseif (isset ($_POST['reservation_id']))
{
	$reservation_info = $db_ops->get_reservation ($_POST['reservation_id']);
	if ($reservation_info["username"] != $_COOKIE["login_username"])
	{
		echo "<h2 class='alert_notok' id='alert_message'>Impossível cancelar reserva: apenas o usuário que realizou a reserva pode fazê-lo.</h2>";
	}
	else
	{
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