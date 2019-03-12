<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/26/2019
 * Time: 5:08 PM
 */
header("Content-type: application/json; charset:utf-8");

require("../model/FaceID.php");

$url = $_SERVER['REQUEST_URI'];
//header("Refresh: 5; URL=$url");

$FaceID = new FaceID();

$threshold = 75;

if (isset($_GET["subscribe"])) {

    $subscribe = $_GET["subscribe"];
} else {

    return '{
        error: "no subscribe" 
    }';
}

$param = array(

    "maxWaitTimeSeconds" => 2,
    "maxMessages" => 5
);

if (isset($_GET["wait"])) {

    $param["maxWaitTimeSeconds"] = $_GET["wait"];
}

if (isset($_GET["limit"])) {

    $param["maxMessages"] = $_GET["limit"];
}

$threshold = isset($_GET["threshold"]) ?  $_GET["threshold"] : $threshold;



$sub = $FaceID->getsub($subscribe, $param);
$data = array();

foreach ($sub as $key => $item) {

    $ackID = $item["ackId"];
    $person = base64_decode($item["message"]["data"]);

    $person = json_decode($person, false);
//    var_dump($person);

    if ($person->messageType == "MESSAGE_TYPE_ALERT") {

        $person = array(
            "ackID" => $ackID,
            "tags" => $person->originalPhotoTags,
            "score" => $person->score,
            "capture" => $person->capturedPhotos->face,
            "photoID" => $person->extraTopk[0]->photoId,
            "capturedTime" => $person->capturedTime,
            "messageType" => $person->messageType

        );

        /*CHECK THRESH HOLD*/
        $data[] = $person;

//        echo '<h1>' . $person["score"] . '</h1>';
//        echo '<img src="http://192.168.51.12:8080/v4/photos/' . $person["capture"] . '/data"/>';

//        var_dump($person);

    }
}
//var_dump($data);
echo json_encode($data);