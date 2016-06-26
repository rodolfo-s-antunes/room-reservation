<?php

/*
 * This script controls the authentication of users in the system. Differently
 * from the remaining of the system interface, this script is NOT accessed
 * through XHR calls, but directly. Also, if an unauthenticated user tries
 * to access any of the system interfaces, he/she will be redirected to this
 * script.
 */

include "database.php";

/*
 * Check if we received the login information via POST parameters. If yes,
 then proceed to verify the authentication information.
 */
if (isset ($_POST['login_username']) && isset ($_POST['login_password']))
{
	$username = $_POST['login_username'];
	$password = $_POST['login_password'];

	// Instantiate the database class to check authentication information
	$db_ops = new DatabaseOperations ();
	// Verify if the informed username and (hashed) password exist in the system
	// database. If yes, then return an array with the full user information
	$user_auth_info = $db_ops->authenticate_user ($username, hash ('sha256', $password));

	// If the inputed authentication information does not exist in the database,
	// exit with an error.
	if (empty ($user_auth_info))
	{
		echo "Falha de autenticação: nome de usuário ou senha incorretos.";
	}
	// If the authentication is successful, start a session for the system and set
	// some session variables. Also, create a cookie with some (non-sensitive) user
	// information. Finally, redirect the user to the main system interface in the
	// index.php interface.
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

/*
 * If no authentication information is received through POST parameters, then
 * present an HTML form to allow the user to input the authentication information.
 */
else {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Sistema de Reserva de Salas</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" />
</head>
<body>

<div id='wrapper'>

<h1 class='header'>Sistema de Reserva de Salas</h1>
<p>
Olá! Por favor informe seu usuário e senha para começar.<br/>
Caso não possua credenciais, entre em contato com um administrador.
</p>

<form method="post" action="login.php">
<table><tr>
<td class="form_description">Usuário:</td>
<td><input type="text" name="login_username" /></td>
</tr><tr>
<td class="form_description">Senha:</td>
<td><input type="password" name="login_password" /></td>
</tr></table>
<input type="submit" name="submit" value="Entrar" />
</form>

</div>

</body>
</html>

<?php } ?>
