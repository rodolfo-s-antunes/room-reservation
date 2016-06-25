function OnBodyLoad ()
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

function RequrestReservation (room_id, date, hour, confirm)
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
		success: ReservationManagementCallback
	});
}

function CancelReservation (reservation_id, confirm)
{
	$.ajax ({
		type: 'POST',
		url: "edit-reservations.php",
		data: {
			reservation_id: reservation_id,
			reservation_confirm: confirm
		},
		success: ReservationManagementCallback
	});
}

function ReservationManagementCallback (result)
{
	$ ("#reservation_management").html (result);
	ListReservations ();
}

function ReservationListingCallback (result)
{
	$ ("#reservation_listing").html (result);
}

