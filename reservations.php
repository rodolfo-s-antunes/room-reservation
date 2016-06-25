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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-3.0.0.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="reservations.js"></script>
</head>
<body onload="OnBodyLoad()">

<h3>Visualizar reservas de salas:</h3>

<form method="post" action="list-reservations.php">
<p>Selecione a data para visualização: <input type="text" id="reservation_date" onchange="ListReservations ()"/></p>
</form>

<div id="reservation_listing"></div>

<div id="reservation_management"></div>

</body>
</html>