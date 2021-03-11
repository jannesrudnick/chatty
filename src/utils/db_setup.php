<?php

$_ENV["db_host"] = "localhost";
$_ENV["db_user"] = "rudnickjan";
$_ENV["db_password"] = "freital";
$_ENV["db_name"] = "rudnickjan";

@$con = new mysqli($_ENV["db_host"], $_ENV["db_user"], $_ENV["db_password"], $_ENV["db_name"]);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 

$GLOBALS['db'] = $con;
?>
