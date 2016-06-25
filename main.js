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
		success: LoadInterfaceCallback
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
		success: LoadInterfaceCallback
	});
}

function DeleteUser ()
{
	$.ajax ({
		type: "POST",
		url: "edit-users.php",
		data: {
			id_user: $ ("[name='id_user']").val (),
			remove: "Remover"
		},
		success: LoadInterfaceCallback
	});	
}

function DeleteRoom ()
{
	$.ajax ({
		type: "POST",
		url: "edit-rooms.php",
		data: {
			id_room: $ ("[name='id_room']").val (),
			remove: "Remover"
		},
		success: LoadInterfaceCallback
	});	
}
