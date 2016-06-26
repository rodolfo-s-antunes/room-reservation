<?php

/*
 * This file stores a set of configuration variables used throughout the system.
 * They are maintained here to ease the initial configuration of the system.
 */

/* The hostname of the server that contains the system database */
define ('database_server', "mysql.rsantunes.notapipe.org");

/* The username of the system database */
define ('database_user', "khost");

/* The password of the system database */
define ('database_pass', "a4b3c2d1");

/* The name of the system database */
define ('database_name', "rsantunes");

/* The first hourly slot available for user to request room reservations in a day */
define ('reservation_hour_min', 6);

/* The last hourly slot available for user to request room reservations in a day */
define ('reservation_hour_max', 22);

?>