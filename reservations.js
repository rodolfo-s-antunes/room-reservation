function OnReservationLoad ()
{
	$ ("#reservation_date").datepicker ({ dateFormat: 'dd/mm/yy' });
	$ ("#reservation_date").datepicker ("setDate", new Date());
	ListReservations ();
}

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

function ReservationListingCallback (result)
{
	$ ("#reservation_listing").html (result);
}

function RequrestReservation (room_id, date, hour)
{
	DoReservation(room_id, date, hour, 0, ReservationRequestAlertCallback)
}

function ConfirmReservation (room_id, date, hour)
{
	DoReservation(room_id, date, hour, 1, ReservationConfirmAlertCallback)
}

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

function RequestCancelReservation (reservation_id)
{
	DoCancelReservation(reservation_id, 0, ReservationRequestAlertCallback)
}

function ConfirmCancelReservation (reservation_id)
{
	DoCancelReservation(reservation_id, 1, ReservationConfirmAlertCallback)
}

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

function ReservationRequestAlertCallback (result)
{
	$ ("#alerts").html (result);
	$ ("#alerts").fadeIn (500);
	if ($ ("#alert_message").attr ("class") == "alert_notok")
		$ ("#alerts").delay (3000).fadeOut (1000);
}

function ReservationConfirmAlertCallback (result)
{
	$ ("#alerts").html (result);
	ListReservations ();
	$ ("#alerts").delay (2000).fadeOut (1000);
}
