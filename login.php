<?php
include "database.php";

if (isset ($_POST['login_username']) && isset ($_POST['login_password']))
{
	$username = $_POST['login_username'];
	$password = $_POST['login_password'];

	$db_ops = new DatabaseOperations ();
	$user_auth_info = $db_ops->authenticate_user ($username, $password);

	if (empty ($user_auth_info))
	{
		echo "Falha de autenticação: nome de usuário ou senha incorretos.";
	}
	else
	{
		session_start ();
		$_SESSION['auth'] = 1;
		$_SESSION['admin'] = $user_auth_info['admin'];
		setcookie ("login_username", $user_auth_info['username'], time()+(84600*30));
		setcookie ("login_userid", $user_auth_info['id'], time()+(84600*30));
		setcookie ("login_fullname", $user_auth_info['fullname'], time()+(84600*30));
		header ('Location: index.php');
		exit ();
	}
}

else {
?>
	<html>
	<head><title>Reservas de Salas</title></head>
	<body>
	<form method="post" action="login.php">
	<p>Usuário: <input type="text" name="login_username" value="<?php echo $_COOKIE['login_username']; ?>" /></p>
	<p>Senha: <input type="password" name="login_password" /></p>
	<input type="submit" name="submit" value="Entrar" />
	</form>
	</body>
	</html>
<?php
}
?>
