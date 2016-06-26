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
		if ($db_ops->check_room_number ($_POST["number"]))
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível cadastrar: já existe uma sala com esse número</h2>";
		}
		else
		{
			$db_ops->add_new_room ($_POST["number"], $_POST["description"]);
			echo "<h2 class='alert_ok' id='alert_message'>Sala Cadastrada</h2>";
		}
	}
	elseif ($_POST["submit"] == "Atualizar")
	{
		$current_room_data = $db_ops->get_room_info ($_POST['id_room']);
		if ( ($db_ops->check_room_number ($_POST["number"])) && ($current_room_data["number"] != $_POST["number"]) )
		{
			echo "<h2 class='alert_notok' id='alert_message'>Não é possível atualizar: já existe uma sala com esse número</h2>";
		}
		else
		{
			$db_ops->update_room ($_POST["id_room"], $_POST["number"], $_POST["description"]);
			echo "<h2 class='alert_ok' id='alert_message'>Sala Atualizada</h2>";
		}
	}
}

elseif (isset ($_POST["remove"]))
{
	if (!$_POST["confirm"])
	{
		$id_room = $_POST["id_room"];
		echo "<h2 class='alert_question' id='alert_message'>Confirma remoção da sala?</h2>";
		echo "<p class='alert_question'><a href='javascript:ConfirmDeleteRoom($id_room)'>confirmar</a> - <a href='javascript:HideAlert()'>cancelar</a></p>";
	}
	else
	{
		$db_ops->remove_room ($_POST["id_room"]);
		echo "<h2 class='alert_ok' id='alert_message'>Sala Removida</h2>";
	}
}

else {
	if (isset ($_POST['id_room']))
	{
		echo "<h2>Editar sala cadastrada no sistema:</h2>";
		$current_room_data = $db_ops->get_room_info ($_POST['id_room']);
	}
	else
	{
		echo "<h2>Adicionar sala ao sistema:</h2>";
		$current_room_data = array();
	}
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
		echo "<input type='hidden' name='id_room' value='" . $current_room_data["id"] . "' />";
		echo "<input type='button' name='remove' value='Remover' onclick='DeleteRoom()' />";
	}
?>

</form>

<?php } ?>
