<?php

/*
 * This file contains the class that manages ALL interactions of the system with
 * the underlying system database. The code is designed to employ the PDO database
 * to access a MySQL database system. More information about the expected database
 * layout can be found on the system documentation on the GitHub README.
 */

/* Import database configuration variables from the global system configuration file */
include "config.php";

class DatabaseOperations
{
	/*
	 * Constructor class. As soon as a class instance is created, a connection with
	 * the database is established and is ready for use by the remaining methods.
	 */
	function __construct ()
	{
		/* Try connecting. If it fails, print the message from the database driver and exit */
		try
		{
			$dbs = database_server;
			$dbn = database_name;
			/* establish database connection using the constant parameters from the config file */
			$this->database_conn = new PDO ("mysql:host=$dbs;dbname=$dbn",
				database_user, database_pass);
			/* set some default behavior parameters for the connection */
			$this->database_conn->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $dbexc)
		{
			echo "Falha na Conexão com o Banco de Dados: " . $dbexc->getMessage ();
			exit();
		}
	}

	/*
	 * This method is used to verify if the authentication information entered by
	 * an user is correct. If the username and password (hashed) exist in the
	 * database, it will return the remaining user information for use in the system.
	 * Otherwise, it will return an empty response.
	 */
	function authenticate_user ($username, $password)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM users WHERE username=:username AND password=:password");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':password', $password);
		$query_stmt->execute ();
		return $query_stmt->fetch ();
	}

	/*
	 * This method returns a list with all records from user information stored in the database.
	 * It is used to list all users in the user management interface of administrative users.
	 */
	function get_all_users_info ()
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM users ORDER BY fullname");
		$query_stmt->execute ();
		return $query_stmt->fetchAll ();
	}

	/*
	 * This method return the information of a single user, selected by primary key
	 * id in the database. It is used mainly to populate the interface to update user
	 * information.
	 */
	function get_user_info ($id_user)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM users WHERE id=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->execute ();
		return $query_stmt->fetch ();
	}

	/*
	 * This method checks if an username, given as parameter, is already registered
	 * in the database. This method is used during user registration to guarantee that
	 * duplicated usernames will not exist in the system.
	 */
	function check_user_name ($username)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT count(id) FROM users WHERE username=:username");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->execute ();
		return $query_stmt->fetch ()[0];
	}

	/*
	 * This method registers a new user in the database, based on the information
	 * given as parameters. IMPORTANT: it assumes that the username given as parameter
	 * does not exist in the database already. This should be checked prior to use
	 * this method, preferably using the "check_user_name" method above.
	 */
	function add_new_user ($username, $fullname, $password, $admin)
	{
		$query_stmt = $this->database_conn->prepare ("INSERT INTO users VALUES (null, :username, :fullname, :password, :admin)");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':fullname', $fullname);
		$query_stmt->bindParam (':password', $password);
		$query_stmt->bindParam (':admin', $admin);
		$query_stmt->execute ();
	}

	/*
	 * This method is used to update the information of an user that is already
	 * registered in the system. It does not update the password of an user; such
	 * operation must be carried out with the "update_user_password" method.
	 * IMPORTANT: this method assumes that the username is not duplicated in the
	 * database. To avoid inconsistencies, this should be verified with the
	 * "check_user_name" method.
	 */
	function update_user_info ($id_user, $username, $fullname, $admin)
	{
		$query_stmt = $this->database_conn->prepare ("UPDATE users SET username=:username, fullname=:fullname, admin=:admin WHERE id=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':fullname', $fullname);
		$query_stmt->bindParam (':admin', $admin);
		$query_stmt->execute ();
	}

	/*
	 * This method updates the password of an user. The password is expected to be
	 * already hashed when passed as parameter.
	 */
	function update_user_password ($id_user, $password)
	{
		$query_stmt = $this->database_conn->prepare ("UPDATE users SET password=:password WHERE id=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->bindParam (':password', $password);
		$query_stmt->execute ();
	}

	/*
	 * This method removes an user from the system, which is identified by primary key
	 * in the database. It also removes from the database all room reservations held
	 * by this user. IMPORTANT: this method does not check if the removed user is the
	 * main administrator. Such check should be done before calling it.
	 */
	function remove_user ($id_user)
	{
		$query_stmt = $this->database_conn->prepare ("DELETE FROM users WHERE id=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->execute ();
		$query_stmt = $this->database_conn->prepare ("DELETE FROM reservations WHERE id_user=:id_user");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->execute ();
	}

	/*
	 * This method returns the information from all rooms registered in the system.
	 * It is used to list all rooms in the room management interface available to
	 * system administrators.
	 */
	function get_all_rooms_info ()
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM rooms ORDER BY number");
		$query_stmt->execute ();
		return $query_stmt->fetchAll ();
	}

	/*
	 * This method returns the information of one of the rooms registered in the
	 * system, which is selected according to its primary key in the database.
	 * It is mainly used to populate the interface to update room information,
	 * which is available for system administrators.
	 */
	function get_room_info ($id_room)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT * FROM rooms WHERE id=:id_room");
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->execute ();
		return $query_stmt->fetch ();
	}

	/*
	 * This method verifies if a room number is not already registered in the system.
	 * It is used to check if no duplicate room numbers are created during the
	 * registration of a new room. 
	 */
	function check_room_number ($number)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT count(id) FROM rooms WHERE number=:number");
		$query_stmt->bindParam (':number', $number);
		$query_stmt->execute ();
		return $query_stmt->fetch ()[0];
	}

	/*
	 * This method adds a new room to the system according to the information given
	 * by the parameters. IMPORTANT: this method does not check if the room number
	 * is already registered in the system. To avoid inconsistencies, prior to
	 * calling it, the new room number should be checked for duplicates using the
	 * "check_room_number" method above.
	 */
	function add_new_room ($number, $description)
	{
		$query_stmt = $this->database_conn->prepare ("INSERT INTO rooms VALUES (null, :number, :description)");
		$query_stmt->bindParam (':number', $number);
		$query_stmt->bindParam (':description', $description);
		$query_stmt->execute ();
	}

	/*
	 * This method updates the information of a room already registered in the system.
	 * IMPORTANT: this method also does not verify if the updated room number is
	 * already registered for another room. To avoid inconsistencies, the new number
	 * should be checked for duplicates using the "check_room_number" method.
	 */
	function update_room ($id_room, $number, $description)
	{
		$query_stmt = $this->database_conn->prepare ("UPDATE rooms SET number=:number, description=:description WHERE id=:id_room");
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->bindParam (':number', $number);
		$query_stmt->bindParam (':description', $description);
		$query_stmt->execute ();
	}

	/*
	 * This method removes a room from the system, which is given by its database
	 * primary key. It also removes all reservations that exist for the room that
	 * can be found in the database.
	 */
	function remove_room ($id_room)
	{
		$query_stmt = $this->database_conn->prepare ("DELETE FROM rooms WHERE id=:id_room");
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->execute ();
		$query_stmt = $this->database_conn->prepare ("DELETE FROM reservations WHERE id_room=:id_room");
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->execute ();
	}

	/*
	 * This method returns the reservations of all rooms for a date given as parameter.
	 * It returns a bidimension matrix. The first dimension lists all rooms available
	 * in the system that contain at least one reservation in the specified date. The
	 * second dimension contains all hourly slots for which a specific room has
	 * reservations registered in the date. Finally, each element of the matrix
	 * contains an array that stores the database primary key of the reservation and
	 * the full name of the user that requested it.
	 */
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

	/*
	 * This method returns the list of all rooms available in the system in the form of
	 * an array indexed by the room numbers. It is used specifically by the room reservation
	 * interface to list all rooms available for reservation. Each element in the array
	 * points to an internal array that contains the room primary key and description.
	 */
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

	/*
	 * This method checks if an user with a specified username does not hold a room
	 * reservation for a specific time slot at a given date. It is used to guarantee
	 * that users will not hold reservations for more than one room at a given time
	 * and date.
	 */
	function check_user_reservations ($username, $date, $hour)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT count(reservations.id) FROM reservations JOIN users ON reservations.id_user=users.id JOIN rooms ON reservations.id_room=rooms.id WHERE users.username=:username AND reservations.date=:date AND reservations.hour=:hour");
		$query_stmt->bindParam (':username', $username);
		$query_stmt->bindParam (':date', $date);
		$query_stmt->bindParam (':hour', $hour);
		$query_stmt->execute ();
		return $query_stmt->fetch()[0];
	}

	/*
	 * This method adds a new room reservation to the system. It assumes that all
	 * the required consistency checks are already done. Mainly, this involves verifying
	 * if the reserving user does not have another room reserved for the same time slot
	 * and date. This check can be done with the "check_user_reservations" method above.
	 */
	function add_reservation ($id_user, $id_room, $date, $hour)
	{
		$query_stmt = $this->database_conn->prepare ("INSERT INTO reservations VALUES (null, :id_user, :id_room, :date, :hour)");
		$query_stmt->bindParam (':id_user', $id_user);
		$query_stmt->bindParam (':id_room', $id_room);
		$query_stmt->bindParam (':date', $date);
		$query_stmt->bindParam (':hour', $hour);
		$query_stmt->execute ();
	}

	/*
	 * This method gets all information about a reservation according to the
	 * database primary key passed as parameter.
	 */
	function get_reservation ($id_reservation)
	{
		$query_stmt = $this->database_conn->prepare ("SELECT reservations.id, rooms.number, users.username, reservations.hour, reservations.date FROM reservations JOIN users ON reservations.id_user=users.id JOIN rooms ON reservations.id_room=rooms.id WHERE reservations.id=:id_reservation");
		$query_stmt->bindParam (':id_reservation', $id_reservation);
		$query_stmt->execute ();
		return $query_stmt->fetch();
	}

	/*
	 * This method deletes one reservation from the system according to its primary
	 * key in the database. IMPORTANT: it does not verify if the user removing the
	 * reservation is the same that registered it. This verification should be made
	 * prior to calling this method.
	 */
	function delete_reservation ($id_reservation)
	{
		$query_stmt = $this->database_conn->prepare ("DELETE FROM reservations WHERE id=:id_reservation");
		$query_stmt->bindParam (':id_reservation', $id_reservation);
		$query_stmt->execute ();
	}
}

?>
