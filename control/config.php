<?php
//	$hostname = "localhost";
$hostname = "192.168.51.12";
	$user = "root";
	$password = "root";
	$database = "faceid";
	//ket noi csdl, ket qua tra ve bien $con
	$con = mysqli_connect($hostname,$user,$password,$database);
	//set charset
	mysqli_set_charset($con,"UTF8");
?>
