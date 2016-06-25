function RequrestReservation (room_id, room, date, hour, confirm)
{
	$.ajax ({
		type: 'POST',
		url: "edit-reservations.php",
		data: {
			reservation_date: date,
			reservation_hour: hour,
			reservation_room: room,
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
}

