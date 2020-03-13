<?php
$servername = "localhost";
$username = "rudnickjan";
$password = "freital";
$dbname = "rudnickjan";

// Create connection
@$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
/*if ($con->connect_errno) {
    die("Fehlercode: " . $con->connect_errno);
}*/
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 

$GLOBALS['db'] = $con;
?>
