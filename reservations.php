<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}
?>

<script>
OnReservationLoad ();
</script>

<h2>Visualizar reservas de salas:</h2>

<form method="post" action="list-reservations.php">
<p>Selecione a data para visualização: <input type="text" id="reservation_date" onchange="ListReservations ()"/></p>
</form>

<div id="reservation_listing"></div>

<div id="reservation_management"></div>
