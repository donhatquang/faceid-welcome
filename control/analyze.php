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
$host = $FaceID->getHost();
$Monitor = new Monitor($con);

/*SET DB*/

if (isset($_GET["photoID"])) {

    $photoID = $_GET["photoID"];
    $name = $_GET["name"];
    $capture = $_GET["capture"];
    $video = isset($_GET["video"]) ? $_GET["video"] : "cam1";

} else {

    return;
}


$photo_data = base64_encode(file_get_contents($host . '/photos/' . $photoID . '/data'));

$data = $FaceID->analyze($photo_data);
$faces = json_decode($data);

//var_dump($data);

$param = (object)array(

    "name" => $name,
    "capture" => $capture,
    "analyze" => $faces->faces[0],
    "video" => $video
);

$id = $Monitor->addDB($photoID, $param);
//var_dump($id);

//var_dump($data);

echo($data);