<?php	
	//$con_cchaportdb=mysqli_connect("192.168.16.42", "user1", "user1test","cchaportdb");	
	//$con_cchaportdb=mysql_connect("192.168.16.42", "user1", "user1test");	
	//mysql_select_db("cchaportdb");
	
	
	$dbFilePathIGM = $_SERVER['DOCUMENT_ROOT']."/Database/ConnectionIGM.txt";
	$dbFileIGM = fopen($dbFilePathIGM, "r") or die("Unable to open DB file!");
	$myCurrDb = trim(fread($dbFileIGM,filesize($dbFilePathIGM)));
	fclose($dbFileIGM);
	//$con_sparcsn4=mysqli_connect($myCurrDb, "sparcsn4", "sparcsn4","sparcsn4");	
	$con_cchaportdb=mysqli_connect($myCurrDb, "user1", "user1test","cchaportdb");	
	//$con_cchaportdb = mysqli_connect("192.168.16.42", "user1", "user1test","cchaportdb");	
	$con_cchaportdb->set_charset("utf8")
?>
