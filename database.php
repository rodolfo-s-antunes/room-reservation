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
		return $query_stmt->fetch ();
	}

	function get_all_users_info ()
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM users ORDER BY fullname");
		$query_stmt->execute ();
		return $query_stmt->fetchAll ();
	}

	function get_user_info ($id_user)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM users WHERE id=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->execute ();
		return $query_stmt->fetch ();
	}

	function check_user_name ($username)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT count(id) FROM users WHERE username=:username");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->execute ();
		return $query_stmt->fetch ()[0];
	}

	function add_new_user ($username, $fullname, $password, $admin)
	{
		$query_stmt = $this->database_conn->prepare ("INSERT INTO users VALUES (null, :username, :fullname, :password, :admin)");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':fullname', $fullname);
		$query_stmt->bindParam (':password', $password);
		$query_stmt->bindParam (':admin', $admin);
		$query_stmt->execute ();
	}

	function update_user ($id_user, $username, $fullname, $password, $admin)
	{
		$query_stmt = $this->database_conn->prepare ("UPDATE users SET username=:username, fullname=:fullname, password=:password, admin=:admin WHERE id=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':fullname', $fullname);
		$query_stmt->bindParam (':password', $password);
		$query_stmt->bindParam (':admin', $admin);
		$query_stmt->execute ();
	}

	function remove_user ($id_user)
	{
		$query_stmt = $this->database_conn->prepare ("DELETE FROM users WHERE id=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->execute ();
	}

	function get_all_rooms_info ()
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM rooms ORDER BY number");
		$query_stmt->execute ();
		return $query_stmt->fetchAll ();
	}

	function get_room_info ($id_room)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM rooms WHERE id=:id_room");
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->execute ();
		return $query_stmt->fetch ();
	}

	function check_room_number ($number)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT count(id) FROM rooms WHERE number=:number");
		$query_stmt->bindParam (':number', $number);
		$query_stmt->execute ();
		return $query_stmt->fetch ()[0];
	}

	function add_new_room ($number, $description)
	{
		$query_stmt = $this->database_conn->prepare ("INSERT INTO rooms VALUES (null, :number, :description)");
		$query_stmt->bindParam (':number', $number);
		$query_stmt->bindParam (':description', $description);
		$query_stmt->execute ();
	}

	function update_room ($id_room, $number, $description)
	{
		$query_stmt = $this->database_conn->prepare ("UPDATE rooms SET number=:number, description=:description WHERE id=:id_room");
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->bindParam (':number', $number);
		$query_stmt->bindParam (':description', $description);
		$query_stmt->execute ();
	}

	function remove_room ($id_room)
	{
		$query_stmt = $this->database_conn->prepare ("DELETE FROM rooms WHERE id=:id_room");
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->execute ();
	}

	function get_reservations_by_date ($date)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT reservations.id, users.fullname, rooms.number, reservations.hour FROM reservations JOIN users ON reservations.id_user=users.id JOIN rooms ON reservations.id_room=rooms.id WHERE reservations.date=:reservation_date");
		$query_stmt->bindParam (':reservation_date', $date);
		$query_stmt->execute ();
		$result = array();
		foreach ($query_stmt->fetchAll () as $row)
		{
			if (!array_key_exists ($row['number'], $result))
				$result[ $row['number'] ] = array();
			$result[ $row['number'] ][ $row['hour'] ] = array( 'id'=>$row['id'], 'fullname'=>$row['fullname'] );
		}
		return $result;
	}

	function get_room_descriptions ()
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM rooms");
		$query_stmt->execute ();
		$result = array();
		foreach ($query_stmt->fetchAll () as $row)
		{
			$result[ $row['number'] ] = array( "id"=>$row["id"], "description"=>$row["description"] );
		}
		return $result;
	}

	function check_user_reservations ($username, $date, $hour)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT count(reservations.id) FROM reservations JOIN users ON reservations.id_user=users.id JOIN rooms ON reservations.id_room=rooms.id WHERE users.username=:username AND reservations.date=:date AND reservations.hour=:hour");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':date', $date);
		$query_stmt->bindParam (':hour', $hour);
		$query_stmt->execute ();
		return $query_stmt->fetch()[0];
	}

	function add_reservation ($id_user, $id_room, $date, $hour)
	{
		$query_stmt = $this->database_conn->prepare ("INSERT INTO reservations VALUES (null, :id_user, :id_room, :date, :hour)");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->bindParam (':date', $date);
		$query_stmt->bindParam (':hour', $hour);
		$query_stmt->execute ();
	}

	function get_reservation ($id_reservation)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT reservations.id, rooms.number, users.username, reservations.hour, reservations.date FROM reservations JOIN users ON reservations.id_user=users.id JOIN rooms ON reservations.id_room=rooms.id WHERE reservations.id=:id_reservation");
		$query_stmt->bindParam (':id_reservation', $id_reservation);
		$query_stmt->execute ();
		return $query_stmt->fetch();
	}

	function delete_reservation ($id_reservation)
	{
		$query_stmt = $this->database_conn->prepare ("DELETE FROM reservations WHERE id=:id_reservation");
		$query_stmt->bindParam (':id_reservation', $id_reservation);
		$query_stmt->execute ();
	}
}

?>
