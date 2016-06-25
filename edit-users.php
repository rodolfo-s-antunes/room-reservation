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
		$db_ops->add_new_user ($_POST["username"], $_POST["fullname"], $_POST["password"], $_POST["admin"]);
		echo "<p>Usuário Cadastrado</p>";
		echo "<p><a href=\"javascript:LoadInterface('list-users.php')\">Retornar</a></p>";
	}
	elseif ($_POST["submit"] == "Atualizar")
	{
		$db_ops->update_user ($_POST["id_user"], $_POST["username"], $_POST["fullname"], $_POST["password"], $_POST["admin"]);
		echo "<p>Usuário Atualizado</p>";
		echo "<p><a href=\"javascript:LoadInterface('list-users.php')\">Retornar</a></p>";
	}
}

elseif (isset ($_POST["remove"]))
{
		$db_ops->remove_user ($_POST["id_user"]);
		echo "<p>Usuário Removido</p>";
		echo "<p><a href=\"javascript:LoadInterface('list-users.php')\">Retornar</a></p>";
}

else {
	if (isset ($_POST['id_user']))
	{
		echo "<h3>Editar usuário cadastrado no sistema:</h3>";
		$current_user_data = $db_ops->get_user_info ($_POST['id_user']);
	}
	else
	{
		echo "<h3>Adicionar usuário ao sistema:</h3>";
		$current_user_data = array();
	}
?>

<form method="post" action="edit-users.php">
<p>Nome de usuário: <input type="text" name="username" value="<?php echo (empty ($current_user_data)) ? "" : $current_user_data["username"] ; ?>" /></p>
<p>Nome completo: <input type="text" name="fullname" value="<?php echo (empty ($current_user_data)) ? "" : $current_user_data["fullname"]; ?>" /></p>
<p>Senha: <input type="text" name="password" value="<?php echo (empty ($current_user_data)) ? "" : $current_user_data["password"]; ?>" /></p>
<p>Usuário administrador? <input type="checkbox" name="admin" <?php echo (empty ($current_user_data)) ? "" : ($current_user_data["admin"]) ? "checked='checked'" : ""; ?> /></p>
<input type="button" name="submit" onclick="UpdateUserInformation()" value="<?php echo (empty ($current_user_data)) ? "Cadastrar" : "Atualizar"; ?>" />
<input type="button" name="submit" onclick="LoadInterface('list-users.php')" value="Cancelar" />

<?php
	if (!empty ($current_user_data))
	{
		echo "<input type='hidden' name='id_user' value='" . $current_user_data["id"] . "' />";
		echo "<input type='button' name='remove' value='Remover' onclick='DeleteUser()' />";
	}
?>

</form>

<?php } ?>
