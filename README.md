# Simple Room Reservation Web System

This repository contains a simple Web system to manage room reservations for meetings. It was developed as a PHP development challenge.

### Deployment

To deploy the application, follow these steps:

1. Copy the contents of this repository the a directory in your Web server. Make sure it is configured to properly handle PHP code. The only library required by the application is the PHP PDO database connector for MySQL. It should be available in the standard PHP installation in most distributions.

2. Create a mysql database to hold the system data and import the contents of the `init.sql` file available in the `sql` directory.

3. Open the `config.php` file and set the configuration options according to the commented instructions for the system to access the created database.

And now you should be able to access the system using your favorite Web browser. The default administrative account is `admin` with the password `123456`. 
