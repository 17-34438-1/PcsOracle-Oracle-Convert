<?php

$dbFilePath = $_SERVER['DOCUMENT_ROOT']."/Database/ConnectionOracleBilling.txt";
$dbFile = fopen($dbFilePath, "r") or die("Unable to open DB file!");
$myCurrDb = trim(fread($dbFile,filesize($dbFilePath)));
fclose($dbFile);
$con_billing_oracle=oci_connect('navisuser', 'admin123', $myCurrDb.'/n4billing');

 if (!$con_billing_oracle) {
	$e = oci_error();
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
						
?> 