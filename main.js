function OnMainLoad ()
{
	$ ("#alerts").hide ();
}

function HideAlert ()
{
	$ ("#alerts").fadeOut (1000);
}

function LoadInterface (interface)
{
	$.ajax ({
		url: interface,
		success: LoadInterfaceCallback
	});
}

function LoadInterfaceCallback (result)
{
	$ ("#main_interface").html (result);
}

function EditUserInterface (id_user)
{
	$.ajax ({
		type: "POST",
		url: "edit-users.php",
		data: {
			id_user: id_user
		},
		success: LoadInterfaceCallback
	});
}

function EditRoomInterface (id_room)
{
	$.ajax ({
		type: "POST",
		url: "edit-rooms.php",
		data: {
			id_room: id_room
		},
		success: LoadInterfaceCallback
	});	
}

function UpdateUserInformation ()
{
	var oper = $ ("[name='submit']").attr ("value");
	var parms = {
		username: $ ("[name='username']").val (),
		fullname: $ ("[name='fullname']").val (),
		password: $ ("[name='password']").val (),
		admin: ($ ("[name='admin']").is (":checked")) ? 1 : 0,
		submit: oper
	};
	if (oper == "Atualizar")
		parms['id_user'] = $ ("[name='id_user']").val ();
	$.ajax ({
		type: "POST",
		url: "edit-users.php",
		data: parms,
		success: UserInterfaceAlertCallback
	});
}

function UpdateRoomInformation ()
{
	var oper = $ ("[name='submit']").attr ("value");
	var parms = {
		number: $ ("[name='number']").val (),
		description: $ ("[name='description']").val (),
		submit: oper
	};
	if (oper == "Atualizar")
		parms['id_room'] = $ ("[name='id_room']").val ();
	$.ajax ({
		type: "POST",
		url: "edit-rooms.php",
		data: parms,
		success: RoomInterfaceAlertCallback
	});
}

function DeleteUser ()
{
	$.ajax ({
		type: "POST",
		url: "edit-users.php",
		data: {
			id_user: $ ("[name='id_user']").val (),
			remove: "Remover",
			confirm: 0
		},
		success: UserInterfaceAlertCallback
	});	
}

function ConfirmDeleteUser ()
{
	$.ajax ({
		type: "POST",
		url: "edit-users.php",
		data: {
			id_user: $ ("[name='id_user']").val (),
			remove: "Remover",
			confirm: 1
		},
		success: UserInterfaceAlertCallback
	});	
}

function DeleteRoom ()
{
	$.ajax ({
		type: "POST",
		url: "edit-rooms.php",
		data: {
			id_room: $ ("[name='id_room']").val (),
			remove: "Remover",
			confirm: 0
		},
		success: RoomInterfaceAlertCallback
	});	
}

function ConfirmDeleteRoom ()
{
	$.ajax ({
		type: "POST",
		url: "edit-rooms.php",
		data: {
			id_room: $ ("[name='id_room']").val (),
			remove: "Remover",
			confirm: 1
		},
		success: RoomInterfaceAlertCallback
	});	
}

function UserInterfaceAlertCallback (result)
{
	$ ("#alerts").html (result);
	$ ("#alerts").fadeIn (500);
	if ($ ("#alert_message").attr ("class") == "alert_notok")
	{
		$ ("#alerts").delay (3000).fadeOut (1000);
	}
	else if ($ ("#alert_message").attr ("class") == "alert_ok")
	{
		LoadInterface ('list-users.php');
		$ ("#alerts").delay (2000).fadeOut (1000);
	}
}

function RoomInterfaceAlertCallback (result)
{
	$ ("#alerts").html (result);
	$ ("#alerts").fadeIn (500);
	if ($ ("#alert_message").attr ("class") == "alert_notok")
	{
		$ ("#alerts").delay (3000).fadeOut (1000);
	}
	else if ($ ("#alert_message").attr ("class") == "alert_ok")
	{
		LoadInterface ('list-rooms.php');
		$ ("#alerts").delay (2000).fadeOut (1000);
	}
}
