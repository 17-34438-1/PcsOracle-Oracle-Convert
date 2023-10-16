<?php

$dbFilePath = $_SERVER['DOCUMENT_ROOT']."/Database/ConnectionOracleN4.txt";
$dbFile = fopen($dbFilePath, "r") or die("Unable to open DB file!");
$myCurrDb = trim(fread($dbFile,filesize($dbFilePath)));
fclose($dbFile);
$con_sparcsn4_oracle=oci_connect('navisuser', 'admin123', $myCurrDb.'/sparcsn4');

?> 