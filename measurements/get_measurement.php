<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");
//$obj = json_decode($_GET["x"], false);
$from = htmlspecialchars($_GET["from"]);
$to = htmlspecialchars($_GET["to"]);

$conn = new mysqli("mysql690.loopia.se", "varg@v188090", "nestor82050", "vscp_org");

if ( !$conn ){
	die("Connection to database failed: " . $conn->error);
}

//$result = $conn->query("SELECT * FROM ".$obj->table." LIMIT ".$obj->limit);
$result = $conn->query("SELECT date, value FROM `measurement` WHERE ( date BETWEEN '" . $from . "' AND '" . $to . "' )  AND guid='FF:FF:FF:FF:FF:FF:FF:FF:61:00:08:01:92:AF:A8:10'");

$outp = array();
$outp = $result->fetch_all( MYSQLI_ASSOC );

//free memory associated with result
$result->close();

//close connection
$conn->close();

echo json_encode( $outp );

?>