<?php
//	$hostname = "localhost";
$hostname = "localhost";
	$user = "root";
	$password = "root";
	$database = "faceid";
	//ket noi csdl, ket qua tra ve bien $con
	$con = mysqli_connect($hostname,$user,$password,$database);
	//set charset
	mysqli_set_charset($con,"UTF8");
?>
