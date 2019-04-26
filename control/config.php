<?php


/*$hostname = "localhost";
$user = "root";
$password = "root";*/

/*$hostname = "192.168.51.4";
$user = "tvlab";
$password = "TinhvanTVLAB20!*";*/

$hostname = "localhost";
$user = "hiface";
$password = "Tinhvan@123";

$database = "faceid";

//ket noi csdl, ket qua tra ve bien $con
function connectdb($servername, $username, $password, $dbname)
{

    $conn = new mysqli($servername, $username, $password, $dbname);

    $conn->set_charset('utf8');

//$conn->query('SET NAMES UTF8');

    if ($conn->connect_error) {

        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

$con = connectdb($hostname, $user, $password, $database);

//var_dump($con);


//$con = mysqli_connect($hostname, $user, $password, $database);
//set charset
//mysqli_set_charset($con, "UTF8");
