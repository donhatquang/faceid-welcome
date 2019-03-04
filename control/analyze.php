<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/26/2019
 * Time: 5:08 PM
 */
header("Content-type: application/json; charset:utf-8");

require("../model/FaceID.php");

$FaceID = new FaceID();

if (isset($_GET["photoID"])) {

    $photoID = $_GET["photoID"];

} else {

  return;
}

$host = $FaceID->getHost();
$photo_data = base64_encode(file_get_contents($host.'/photos/'.$photoID.'/data'));

$data = $FaceID->analyze($photo_data);
//var_dump($data);

echo ($data);