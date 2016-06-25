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
		echo "<p>Sala Cadastrada</p>";
		echo "<p><a href=\"javascript:LoadInterface('list-rooms.php')\">Retornar</a></p>";
	}
	elseif ($_POST["submit"] == "Atualizar")
	{
		$db_ops->update_room ($_POST["id_room"], $_POST["number"], $_POST["description"]);
		echo "<p>Sala Atualizada</p>";
		echo "<p><a href=\"javascript:LoadInterface('list-rooms.php')\">Retornar</a></p>";
	}
}

elseif (isset ($_POST["remove"]))
{
	$db_ops->remove_room ($_POST["id_room"]);
	echo "<p>Sala Removida</p>";
	echo "<p><a href=\"javascript:LoadInterface('list-rooms.php')\">Retornar</a></p>";
}

else {
	if (isset ($_POST['id_room']))
	{
		echo "<h3>Editar sala cadastrada no sistema:</h3>";
		$current_room_data = $db_ops->get_room_info ($_POST['id_room']);
	}
	else
	{
		echo "<h3>Adicionar sala ao sistema:</h3>";
		$current_room_data = array();
	}
?>

<form>
<p>Número da sala: <input type="text" name="number" value="<?php echo (empty ($current_room_data)) ? "" : $current_room_data["number"] ; ?>" /></p>
<p>Descrição: <input type="text" name="description" value="<?php echo (empty ($current_room_data)) ? "" : $current_room_data["description"]; ?>" /></p>
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
