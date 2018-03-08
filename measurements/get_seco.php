<?php

	function jsonRemoveUnicodeSequences($struct) {
		return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
 	};

	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/


	header("Content-Type: application/json; charset=UTF-8");

	$conn = new mysqli("mysql690.loopia.se", "test@v188090", "secret82050", "vscp_org");

	if ( !$conn ){
		die("Connection to database failed: " . $conn->error);
	}

	$result = $conn->query("SELECT seco.name, seco.description, guid.guid FROM seco, guid WHERE guid.idx = seco.link_to_guid;");
	if ($result->connect_errno) {
		echo "Failed to connect to MySQL: (" . $result->connect_errno . ") " . $resultmysqli->connect_error;
	}

	$outp = array();
	$outp = $result->fetch_all( MYSQLI_ASSOC ); // 

	foreach($outp as $x => $x_value) {
		foreach($x_value as $y => $y_value) {
			//$y_value = htmlentities( $y_value );
			//echo "XKey=" . $y . ", XValue=" . $y_value;
			//echo "<br>";
		}
	}

	// free memory associated with result
	$result->close();

	// close connection
	$conn->close();

	// TODO: Does not handle unicode characters
	//echo jsonRemoveUnicodeSequences( $outp );
	echo json_encode( $outp, JSON_UNESCAPED_UNICODE );

?>