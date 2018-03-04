<?php

//echo $_SERVER['HTTP_HOST'];

if ($_SERVER['HTTP_HOST'] == 'nanuyo.com' || $_SERVER['HTTP_HOST'] == 'www.nanuyo.com')
{
	$server = 'localhost';
	$username = 'junjun1971';
	$password = 'Junjun6363';
	$database = 'junjun1971';
}
else if ($_SERVER['HTTP_HOST'] == 'localhost')
{
	$server = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'nanuyo';
}
else
{
	$server = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'nanuyo';
}

try{
	$conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
	//$conn = new PDO("sqlite:/home/hjpark/sampledb.db");
	//echo "success";
} catch(PDOException $e){
	die( "Connection failed: " . $e->getMessage());
}