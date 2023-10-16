<?php
	//$con_sparcsn4=mysqli_connect("10.1.1.21", "sparcsn4", "sparcsn4","sparcsn4");
	//$con_sparcsn4=mysql_connect("10.1.1.21", "sparcsn4", "sparcsn4");
	//mysql_select_db("sparcsn4");
	
	$dbFilePath = $_SERVER['DOCUMENT_ROOT']."/Database/ConnectionN4.txt";
	$dbFile = fopen($dbFilePath, "r") or die("Unable to open DB file!");
	$myCurrDb = trim(fread($dbFile,filesize($dbFilePath)));
	fclose($dbFile);
	$con_sparcsn4=mysqli_connect($myCurrDb, "sparcsn4", "sparcsn4","sparcsn4");
	//mysqli_select_db("sparcsn4");
?>
