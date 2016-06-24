<?php

# This class is responsible for all database operations required by the system.
# TODO: Put configuration information in a separate "config.php" file.

class DatabaseOperations
{
	function __construct ()
	{
		$database_server = "mysql.rsantunes.notapipe.org";
		$database_user = "khost";
		$database_pass = "a4b3c2d1";
		$database_name = "rsantunes";		
		try
		{
			$this->database_conn = new PDO ("mysql:host=$database_server;dbname=$database_name",
				$database_user, $database_pass);
			$this->database_conn->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $dbexc)
    	{
    		echo "Falha na Conexão com o Banco de Dados: " . $dbexc->getMessage ();
    	}
	}

	function authenticate_user ($username, $password)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM users WHERE username=:username AND password=:password");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':password', $password);
		$query_stmt->execute ();
		return $query_stmt->fetch();
	}
}

?>