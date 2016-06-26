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
		$db_ops->add_new_room ($_POST["number"], $_POST["description"]);
		echo "<h2>Sala Cadastrada</h2>";
		echo "<p><a href=\"javascript:LoadInterface('list-rooms.php')\">Retornar</a></p>";
	}
	elseif ($_POST["submit"] == "Atualizar")
	{
		$db_ops->update_room ($_POST["id_room"], $_POST["number"], $_POST["description"]);
		echo "<h2>Sala Atualizada</h2>";
		echo "<p><a href=\"javascript:LoadInterface('list-rooms.php')\">Retornar</a></p>";
	}
}

elseif (isset ($_POST["remove"]))
{
	$db_ops->remove_room ($_POST["id_room"]);
	echo "<h2>Sala Removida</h2>";
	echo "<p><a href=\"javascript:LoadInterface('list-rooms.php')\">Retornar</a></p>";
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
