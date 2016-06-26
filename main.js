/*
 * This method is called when the body of the "index.php" interface is loaded.
 * It simply hides the div that displays alert messages by setting its
 * visibility CSS parameter.
 */
function OnMainLoad ()
{
	$ ("#alerts").hide ();
}

/*
 * This method is called to hide the div that displays alert messages. It employs
 * jQuery methods to present a fade-out animation when hiding the div.
 */
function HideAlert ()
{
	$ ("#alerts").fadeOut (1000);
}

/*
 * This method makes a generic XHR call to load an interface passed as parameter.
 * It is used by the main system interface in the "index.php" script to load the
 * secondary interfaces, that is, user, room, and reservation listings.
 */
function LoadInterface (interface)
{
	$.ajax ({
		url: interface,
		success: LoadInterfaceCallback
	});
}

/*
 * This method is the callback of the LoadInterface method. It simply takes the
 * output of the XHR call and displays it in the "main_interface" div that is
 * declared on the main interface in the "index.php".
 */
function LoadInterfaceCallback (result)
{
	$ ("#main_interface").html (result);
}

/*
 * This method specifically loads the interface to edit information of an existing
 * user. It is called when the user requests to edit user information via the
 * "list-users.php" interface.
 */
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

/*
 * This method specifically loads the interface to edit information of an existing
 * room. It is called when the user requests to edit room information via the
 * "list-rooms.php" interface.
 */
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

/*
 * This method is called by the "edit-users.php" interface when the user submits the
 * information to register or update an user in the system. It verifies the type of
 * operation and creates the according POST parameters for the XHR call to the
 * "edit-users.php" script.
 */
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

/*
 * This method is called by the "edit-rooms.php" interface when the user submits the
 * information to register or update an room in the system. It verifies the type of
 * operation and creates the according POST parameters for the XHR call to the
 * "edit-rooms.php" script.
 */
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

/*
 * This method is called when the user requests to remove an existing user
 * from the system. This method informs the "edit-users.php" script that
 * the user has not yet confirmed the removal, and thus the removal should
 * not yet be carried out.
 */
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

/*
 * This method is called when the user confirms that an user should be removed
 * from the system from within an alert shown in the user interface. It calls the
 * "edit-users.php" with the appropriate parameters to proceed with the removal.
 */
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

/*
 * This method is called when the user requests to remove an existing room
 * from the system. This method informs the "edit-rooms.php" script that
 * the user has not yet confirmed the removal, and thus the removal should
 * not yet be carried out.
 */
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

/*
 * This method is called when the user confirms that a room should be removed
 * from the system from within an alert shown in the user interface. It calls the
 * "edit-rooms.php" with the appropriate parameters to proceed with the removal.
 */
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

/*
 * This script is the callback to all XHR calls that involve the
 * interface to manage the system users. It checks the class of the
 * received HTML tags to specify if the message is a confirmation,
 * a positive or a negative confirmation. Depending on the type of
 * message, it will simply show a alert that will fade after a few
 * seconds, show a confirmation requiring user input, or show an alert
 * and then reload the user listing interface via XHR.
 */
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

/*
 * This script is the callback to all XHR calls that involve the
 * interface to manage rooms. It checks the class of the
 * received HTML tags to specify if the message is a confirmation,
 * a positive or a negative confirmation. Depending on the type of
 * message, it will simply show a alert that will fade after a few
 * seconds, show a confirmation requiring user input, or show an alert
 * and then reload the room listing interface via XHR.
 */
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
