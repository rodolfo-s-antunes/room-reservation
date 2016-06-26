/*
 * This function is executed when the interface on the "reservation.php" file
 * is loaded. It initializes the jQuery datepicker used for date selection and
 * sets it to the corrent date as default. Finally, it calls the function to
 * load the list of reservations from the current day via XHR.
 */
function OnReservationLoad ()
{
	$ ("#reservation_date").datepicker ({ dateFormat: 'dd/mm/yy' });
	$ ("#reservation_date").datepicker ("setDate", new Date());
	ListReservations ();
}

/*
 * This script creates a XHR call to load the reservation grid implemented in
 * the "list-reservations.php" script. It gets the date parameter from the value
 * specified in the "reservation_date" form.
 */
function ListReservations ()
{
	var reservation_date = $ ("#reservation_date").datepicker ("getDate");
	$.ajax ({
		type: 'POST',
		url: "list-reservations.php",
		data: {
			reservation_date: $.datepicker.formatDate ("yy-mm-dd", reservation_date)
		},
		success: ReservationListingCallback
	});
}

/*
 * This function is the callback from the XHR call to present the room reservation
 * grid. It simply presents the output received from the call to the "reservation_listing"
 * div that is defined in the "reservations.php" HTML code.
 */
function ReservationListingCallback (result)
{
	$ ("#reservation_listing").html (result);
}

/*
 * This method is called when the user requests a room reservation through the
 * "list-reservations.php" interface. This script makes a second call to the
 * script that actually creates the XHR call, sending the reservation information
 * and the flag that it should not be confired yet (so the user has the option to
 * confirm it through an alert that is shown in the interface).
 */
function RequrestReservation (room_id, date, hour)
{
	DoReservation(room_id, date, hour, 0, ReservationRequestAlertCallback)
}

/*
 * This method is called when the user confirms the requested reservation in the
 * alert that is shown in the system interface. It also calls the secondary script
 * that creates the actual XHR call, but now with the flag informing that the
 * reservation should be actually made.
 */
function ConfirmReservation (room_id, date, hour)
{
	DoReservation(room_id, date, hour, 1, ReservationConfirmAlertCallback)
}

/*
 * This method generates a XHR call to the "edit-reservations.php" script. It
 * is indrectly called by the two above methods, depending on the request or
 * confirmation received in the user interface.
 */
function DoReservation (room_id, date, hour, confirm, callback)
{
	$.ajax ({
		type: 'POST',
		url: "edit-reservations.php",
		data: {
			reservation_date: date,
			reservation_hour: hour,
			reservation_roomid: room_id,
			reservation_confirm: confirm
		},
		success: callback
	});
}

/*
 * This method is called when the user requests to cancel a reservation through the
 * "list-reservations.php" interface. It makes a secondary call to another method that
 * will generate the actual XHR call, sending as parameter a flag indicating that
 * the user should first confirm the removal of the reservation.
 */
function RequestCancelReservation (reservation_id)
{
	DoCancelReservation(reservation_id, 0, ReservationRequestAlertCallback)
}

/*
 * This method is called when the user confirms the removal of a reservation from within
 * the alert that is shown in the user interface. It makes a secondary call to another
 * method that creates the actual XHR call.
 */
function ConfirmCancelReservation (reservation_id)
{
	DoCancelReservation(reservation_id, 1, ReservationConfirmAlertCallback)
}

/*
 * This method creates the actual XHR call to the "edit-reservations.php" script in order
 * to request or confirm that a reservations should be removed. It is indirectly called
 * by the above two methods.
 */
function DoCancelReservation (reservation_id, confirm, callback)
{
	$.ajax ({
		type: 'POST',
		url: "edit-reservations.php",
		data: {
			reservation_id: reservation_id,
			reservation_confirm: confirm
		},
		success: callback
	});
}

/*
 * This method is the callback of the XHR calls to create or remove reservations.
 * It is invoked by the methods that request a confirmation from the user,
 * that is, prior to actually executing the operations.
 */
function ReservationRequestAlertCallback (result)
{
	$ ("#alerts").html (result);
	$ ("#alerts").fadeIn (500);
	if ($ ("#alert_message").attr ("class") == "alert_notok")
		$ ("#alerts").delay (3000).fadeOut (1000);
}

/*
 * This method is the callback of the XHR calls to create or remove reservations.
 * It is invoked by the methods that request the actual operation execution, that is,
 * after the user has confirmed the operation.
 */
function ReservationConfirmAlertCallback (result)
{
	$ ("#alerts").html (result);
	ListReservations ();
	$ ("#alerts").delay (2000).fadeOut (1000);
}
