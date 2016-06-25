function LoadInterface (interface)
{
	$.ajax ({
		url: interface,
		success: LoadInterfaceCallback
	});
}

function AddNewUserInterface ()
{
	$.ajax ({
		url: "edit-users.php",
		success: LoadInterfaceCallback
	});	
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

function LoadInterfaceCallback (result)
{
	$ ("#main_interface").html (result);
}