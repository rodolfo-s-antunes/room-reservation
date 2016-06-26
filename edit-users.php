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
 * of system users That is, the operations related to the CRUD methods to manage
 * system users. This script is always invoked thorugh XHR calls from the
 * "list-users.php" interface. All data sent to this script is expected to
 * be received through POST methods. It is also expected that the interfaces
 * of this script are accessed by user accounts registered as administrators.
 * However, such condition is left for verification in code executed prior
 * to the invocation of this script.
 */

// include and instantiate the class that handles database operations.
include "database.php";
$db_ops = new DatabaseOperations ();

/*
 * Check if the POST contains a "submit" parameter, indicating that the script
 * is invoked to proceed with the registration or update of an user.
 */
if (isset ($_POST["submit"]))
{
	/*
	 * If the value of the "submit" parameter is "Cadastrar", proceed to register a
	 * new user in the system. In turn, if its value is "Atualizar", proceed to
	 * update the information about an user already registered.
	 */
	if ($_POST["submit"] == "Cadastrar")
	{
		/*
		 * Before registering a new user, check if the informed username does not
		 * already exists in the database. If it exists, do not proceed with registration
		 * and alert the user that the username already exists.
		 */
		if ($db_ops->check_user_name ($_POST["username"]))
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível cadastrar: nome de usuário já existe.</h2>";
		}
		/*
		 * If the username is not registered, proceed with the new user registration
		 * in the database and inform the user. Before regitering the password in the database,
		 * hash it with the SHA256 algorithm.
		 */
		else
		{
			$db_ops->add_new_user ($_POST["username"], $_POST["fullname"], hash ('sha256', $_POST["password"]), $_POST["admin"]);
			echo "<h2 class='alert_ok' id='alert_message'>Usuário Cadastrado</h2>";
		}
	}
	elseif ($_POST["submit"] == "Atualizar")
	{
		/*
		 * Before updating information about an user, check if a (possible) new username is not
		 * already registered for another user. If it is, do not update the info and alert the user.
		 */
		$current_user_data = $db_ops->get_user_info ($_POST['id_user']);
		if ( ($db_ops->check_user_name ($_POST["username"])) && ($current_user_data["username"] != $_POST["username"]) )
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível atualizar: nome de usuário já existe.</h2>";
		}
		/* 
		 * If all information is ok, proceed with the update and alert the user. When updating
		 * user information, check if the password field is left blank. If it is, do not update
		 * it (to avoid accidental pasword resets). Only update the password separatelly if a new
		 * password is entered in the field.
		 */
		else
		{
			$db_ops->update_user_info ($_POST["id_user"], $_POST["username"], $_POST["fullname"], $_POST["admin"]);
			if (!empty ($_POST["password"]))
			{
				$db_ops->update_user_password ($_POST["id_user"], hash ('sha256', $_POST["password"]));
			}
			echo "<h2 class='alert_ok' id='alert_message'>Usuário Atualizado</h2>";
		}
	}
}

/*
 * If the script does not receive a "submit" parameter but instead the POST
 * contains a "remove" parameter, assume that the user is requesting to
 * remove an user registered in the system.
 */
elseif (isset ($_POST["remove"]))
{
	/*
	 * Before removing the user, check if it is not the main system administrator,
	 * identified by the primary key of value "1". If this is the case, do not
	 * proceed with the removal and alert the user.
	 */
	if ($_POST["id_user"] == 1)
	{
		echo "<h2 class='alert_notok' id='alert_message'>Impossível remover o Administrador!</h2>";
	}
	else
	{
		/*
		 * Before actualy removing the user, send an alert requesting a
		 * confirmation that the user should realy be removed from the
		 * system, with an option to proceed with the removal.
		 */
		if (!$_POST["confirm"])
		{
			$id_user = $_POST["id_user"];
			echo "<h2 class='alert_question' id='alert_message'>Confirma remoção do usuário?</h2>";
			echo "<p class='alert_question'><a href='javascript:ConfirmDeleteUser($id_user)'>confirmar</a> - <a href='javascript:HideAlert()'>cancelar</a></p>";
		}
		/*
		 * If the removal is confirmed, proceed with the deletion operation in
		 * the database and send an alert to inform the removal.
		 */
		else
		{
			$db_ops->remove_user ($_POST["id_user"]);
			echo "<h2 class='alert_ok' id='alert_message'>Usuário Removido</h2>";
		}
	}
}

/*
 * If the POST data does not contain neither a "submit" or a "remove" parameter
 * assume that the script should return the interface to allow the user to input
 * the information to register or update a new user in the system.
 */
else {
	if (isset ($_POST['id_user']))
	{
		/*
		 * If the script receives as parameter the primary key id of an user registered
		 * in the system, assume that the information about this user should be updated
		 * and recover the remaining data about the user from the database.
		 */
		echo "<h2>Editar usuário cadastrado no sistema:</h2>";
		$current_user_data = $db_ops->get_user_info ($_POST['id_user']);
	}
	// If no primary key id is received, assume that a new user should be registered.
	else
	{
		echo "<h2>Adicionar usuário ao sistema:</h2>";
		$current_user_data = array();
	}

/*
 * The HTML code below presents the interface for the user to input the new
 * data about the user being added or updated. If an update operation was requested
 * the field values will be preset to the already existing values in the database.
 * Additionaly, the form submisstion button will indicate that an existing user
 * is being updated and a button to allow the removal of the existing user is presented.
 * The only exception is the password field, which is always left blank for
 * security reasons. If an addition was requested, all fields are left blank and
 * no removal option is shown.
 */
?>

<form>
<table>
<tr>
<td class="form_description">Nome de usuário:</td>
<td><input type="text" name="username" size="50" value="<?php echo (empty ($current_user_data)) ? "" : $current_user_data["username"] ; ?>" /></td>
</tr><tr>
<td class="form_description">Nome completo:</td>
<td><input type="text" name="fullname" size="50" value="<?php echo (empty ($current_user_data)) ? "" : $current_user_data["fullname"]; ?>" /></td>
</tr><tr>
<td class="form_description">Senha:</td>
<td><input type="text" name="password" size="50" /></td>
</tr><tr>
<td class="form_description">Usuário administrador?</td>
<td><input type="checkbox" name="admin" <?php echo (empty ($current_user_data)) ? "" : ($current_user_data["admin"]) ? "checked='checked'" : ""; ?> /></td>
</tr>
</table>
<input type="button" name="submit" onclick="UpdateUserInformation()" value="<?php echo (empty ($current_user_data)) ? "Cadastrar" : "Atualizar"; ?>" />
<input type="button" name="cancel" onclick="LoadInterface('list-users.php')" value="Cancelar" />

<?php
	if (!empty ($current_user_data))
	{
		// If the form is used for updating an user, include a hidden value with the
		// primary key id of the room. Also, include the option to remove the user
		// from the system.
		echo "<input type='hidden' name='id_user' value='" . $current_user_data["id"] . "' />";
		echo "<input type='button' name='remove' value='Remover' onclick='DeleteUser()' />";
	}
?>

</form>

<?php } ?>
