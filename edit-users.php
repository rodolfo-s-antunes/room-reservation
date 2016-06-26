<?php
session_start();
if (!isset ($_SESSION['auth']) || $_SESSION['auth'] != 1) {
	header ('Location: login.php');
	exit ();
}

include "database.php";
$db_ops = new DatabaseOperations ();

if (isset ($_POST["submit"]))
{
	if ($_POST["submit"] == "Cadastrar")
	{
		if ($db_ops->check_user_name ($_POST["username"]))
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível cadastrar: nome de usuário já existe.</h2>";
		}
		else
		{
			$db_ops->add_new_user ($_POST["username"], $_POST["fullname"], hash ('sha256', $_POST["password"]), $_POST["admin"]);
			echo "<h2 class='alert_ok' id='alert_message'>Usuário Cadastrado</h2>";
		}
	}
	elseif ($_POST["submit"] == "Atualizar")
	{
		$current_user_data = $db_ops->get_user_info ($_POST['id_user']);
		if ( ($db_ops->check_user_name ($_POST["username"])) && ($current_user_data["username"] != $_POST["username"]) )
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível atualizar: nome de usuário já existe.</h2>";
		}
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

elseif (isset ($_POST["remove"]))
{
	$db_ops->remove_user ($_POST["id_user"]);
	echo "<h2 class='alert_ok' id='alert_message'>Usuário Removido</h2>";
}

else {
	if (isset ($_POST['id_user']))
	{
		echo "<h2>Editar usuário cadastrado no sistema:</h2>";
		$current_user_data = $db_ops->get_user_info ($_POST['id_user']);
	}
	else
	{
		echo "<h2>Adicionar usuário ao sistema:</h2>";
		$current_user_data = array();
	}
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
		echo "<input type='hidden' name='id_user' value='" . $current_user_data["id"] . "' />";
		echo "<input type='button' name='remove' value='Remover' onclick='DeleteUser()' />";
	}
?>

</form>

<?php } ?>
