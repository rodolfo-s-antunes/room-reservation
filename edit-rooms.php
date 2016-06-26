<?php
// First start the user session and verify if the user is authenticated.
// If not, redirect to the authentication interface on "login.php".
session_start();
if (!isset ($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header ('Location: login.php');
	exit ();
}

/*
 * This PHP script contains all the code related to edition of information
 * of rooms available for reservation. That is, the operations related to the
 * CRUD methods to manage available rooms. This script is always invoked thorugh
 * XHR calls from the "list-rooms.php" interface. All data sent to this script
 * is expected to be received through POST methods. It is also expected that
 * the interfaces of this script are accessed by user accounts registered
 * as administrators. However, such condition is left for verification in
 * code executed prior to the invocation of this script.
 */

// include and instantiate the class that handles database operations.
include "database.php";
$db_ops = new DatabaseOperations ();

/*
 * Check if the POST contains a "submit" parameter, indicating that the script
 * is invoked to proceed with the registration or update of a room in the system.
 */
if (isset ($_POST["submit"]))
{
	/*
	 * If the value of the "submit" parameter is "Cadastrar", proceed to register a
	 * new room in the system. In turn, if its value is "Atualizar", proceed to
	 * update the information of a room already registered in the system.
	 */
	if ($_POST["submit"] == "Cadastrar")
	{
		/*
		 * Before registering a new room, check if the informed room number does not
		 * already exists in the database. If it exists, do not proceed with registration
		 * and alert the user that the number already exists.
		 */
		if ($db_ops->check_room_number ($_POST["number"]))
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível cadastrar: já existe uma sala com esse número</h2>";
		}
		/*
		 * If the room number is not registered, proceed with the registration in the
		 * database and inform the user.
		 */
		else
		{
			$db_ops->add_new_room ($_POST["number"], $_POST["description"]);
			echo "<h2 class='alert_ok' id='alert_message'>Sala Cadastrada</h2>";
		}
	}
	elseif ($_POST["submit"] == "Atualizar")
	{
		$current_room_data = $db_ops->get_room_info ($_POST['id_room']);
		/*
		 * Before updating information about a room, check if a (possible) new room number is not
		 * already registered for another room. If it is, do not update the info and alert the user.
		 */
		if ( ($db_ops->check_room_number ($_POST["number"])) && ($current_room_data["number"] != $_POST["number"]) )
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível atualizar: já existe uma sala com esse número</h2>";
		}
		// If all information is ok, proceed with the update and alert the user.
		else
		{
			$db_ops->update_room ($_POST["id_room"], $_POST["number"], $_POST["description"]);
			echo "<h2 class='alert_ok' id='alert_message'>Sala Atualizada</h2>";
		}
	}
}

/*
 * If the script does not receive a "submit" parameter but instead the POST
 * contains a "remove" parameter, assume that the user is requesting to
 * remove a room registered in the system.
 */
elseif (isset ($_POST["remove"]))
{
	/*
	 * Before actualy removing the room, send an alert to the user requesting a
	 * confirmation that the room should realy be removed from the system, with
	 * an option to proceed with the removal.
	 */
	if (!$_POST["confirm"])
	{
		$id_room = $_POST["id_room"];
		echo "<h2 class='alert_question' id='alert_message'>Confirma remoção da sala?</h2>";
		echo "<p class='alert_question'><a href='javascript:ConfirmDeleteRoom($id_room)'>confirmar</a> - <a href='javascript:HideAlert()'>cancelar</a></p>";
	}
	/*
	 * If the user confirms the removal, proceed with the deletion operation in
	 * the database and alert the user.
	 */
	else
	{
		$db_ops->remove_room ($_POST["id_room"]);
		echo "<h2 class='alert_ok' id='alert_message'>Sala Removida</h2>";
	}
}

/*
 * If the POST data does not contain neither a "submit" or a "remove" parameter
 * assume that the script should return the interface to allow the user to input
 * the information to register or update a room in the system.
 */
else {
	/*
	 * If the script receives as parameter the primary key id of a room registered
	 * in the system, assume that the information about this room should be updated
	 * and recover the remaining data about the room from the database.
	 */
	if (isset ($_POST['id_room']))
	{
		echo "<h2>Editar sala cadastrada no sistema:</h2>";
		$current_room_data = $db_ops->get_room_info ($_POST['id_room']);
	}
	// If no primary key id is received, assume that a new room should be registered.
	else
	{
		echo "<h2>Adicionar sala ao sistema:</h2>";
		$current_room_data = array();
	}

/*
 * The HTML code below presents the interface for the user to input the new
 * data about the room being added or updated. If an update operation was requested
 * the field values will be preset to the already existing values in the database.
 * Additionaly, the form submisstion button will indicate that an existing room
 * is being updated and a button to allow the removal of the existing room is presented.
 * If an addition was requested, all fields are left blank and no removal option is
 * shown.
 */
?>

<form>
<table>
<tr>
<td class="form_description">Número da sala:</td>
<td><input type="text" name="number" size="50" value="<?php echo (empty ($current_room_data)) ? "" : $current_room_data["number"] ; ?>" /></td>
</tr><tr>
<td class="form_description">Descrição:</td>
<td><input type="text" name="description" size="50" value="<?php echo (empty ($current_room_data)) ? "" : $current_room_data["description"]; ?>" /></td>
</tr>
</table>
<input type="button" name="submit" onclick="UpdateRoomInformation()" value="<?php echo (empty ($current_room_data)) ? "Cadastrar" : "Atualizar"; ?>" />
<input type="button" name="cancel" onclick="LoadInterface('list-rooms.php')" value="Cancelar" />
<?php
	if (!empty ($current_room_data))
	{
		// If the form is used for updating a room, include a hidden value with the
		// primary key id of the room. Also, include the option to remove the room
		// from the system.
		echo "<input type='hidden' name='id_room' value='" . $current_room_data["id"] . "' />";
		echo "<input type='button' name='remove' value='Remover' onclick='DeleteRoom()' />";
	}
?>

</form>

<?php } ?>
