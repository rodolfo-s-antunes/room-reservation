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
		echo "<h3>Impossível reservar: o usuário já possui outra sala reservada para o mesmo horário.</h3>";
	}
	else
	{
		$roomid = $_POST['reservation_roomid'];
		$room = $db_ops->get_room_info ($roomid)["number"];
		$date = $_POST['reservation_date'];
		$hour = $_POST['reservation_hour'];
		if (!$_POST['reservation_confirm'])
		{
			echo "<h3>Confirma reserva da sala $room para as ${hour}h do dia ${date}?</h3>";
			echo "<a href='javascript:RequrestReservation($roomid,\"$date\",$hour,1)'>Confirmar reserva</a>";
		}
		else
		{
			$db_ops->add_reservation ($_COOKIE["login_userid"], $roomid, $date, $hour);
			echo "<h3>Reserva confirmada.</h3>";
		}
	}
}

elseif (isset ($_POST['reservation_id']))
{
	$reservation_info = $db_ops->get_reservation ($_POST['reservation_id']);
	if ($reservation_info["username"] != $_COOKIE["login_username"])
	{
		echo "<h3>Impossível cancelar reserva: apenas o usuário que realizou a reserva pode fazê-lo.</h3>";
	}
	else
	{
		if (!$_POST['reservation_confirm'])
		{
			$room = $reservation_info["number"];
			$date = $reservation_info["date"];
			$hour = $reservation_info["hour"];
			$resid = $reservation_info["id"];
			echo "<h3>Confirma cancelamento da reserva da sala $room para as ${hour}h do dia ${date}?</h3>";
			echo "<p><a href='javascript:CancelReservation($resid,1)'>Confirmar cancelamento</a></p>";
		}
		else
		{
			$db_ops->delete_reservation ($_POST['reservation_id']);
			echo "<h3>Reserva cancelada.</h3>";
		}
	}
}

?>