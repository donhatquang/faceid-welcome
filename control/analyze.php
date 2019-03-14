<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/26/2019
 * Time: 5:08 PM
 */
header("Content-type: application/json; charset:utf-8");

require("../model/FaceID.php");
require("../model/Monitor.php");
require("../control/config.php");

$FaceID = new FaceID();
$Monitor = new Monitor($con);

/*SET DB*/
//$FaceID->setCon($con);

if (isset($_GET["photoID"])) {

    $photoID = $_GET["photoID"];
    $name = $_GET["name"];
    $capture = $_GET["capture"];

} else {

    return;
}

$host = $FaceID->getHost();
$photo_data = base64_encode(file_get_contents($host . '/photos/' . $photoID . '/data'));

$data = $FaceID->analyze($photo_data);
$faces = json_decode($data);
//var_dump($photo_analyze);

$Monitor->addDB($photoID, $name, $capture, $faces->faces[0]);
//var_dump($data);

echo($data);