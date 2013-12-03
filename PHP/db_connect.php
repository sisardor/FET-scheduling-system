<?php
define("HOST", "localhost"); // The host you want to connect to.
define("USER", "root"); // The database username.
define("PASSWORD", "root"); // The database password. 
define("DATABASE", "fet"); // The database name.

$dbname = "mctom03_2261db";
$users = "users"; 
 
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
// If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
