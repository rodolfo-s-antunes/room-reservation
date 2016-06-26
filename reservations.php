<?php
// First start the user session and verify if the user is authenticated.
// If not, redirect to the authentication interface on "login.php".
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header('Location: login.php');
	exit();
}

/*
 * This script is simply an entry point to the "list-reservations.php" interface.
 * It presents a form that allows the user to input the date for which reservations
 * should be presented. This form is powered by the jQuery datepicker type to
 * allow higher browser compatibility. Also, when the form is loaded, it is 
 * preset with the current date.
 */

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
