<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/26/2019
 * Time: 5:08 PM
 */
//header("Content-type: application/json; charset:utf-8");

require("../model/FaceID.php");
require("Tools.php");

session_start();


$FaceID = new FaceID();
$url = $_SERVER['REQUEST_URI'];

if (isset($_GET["subscribe"])) {

    $subscribe = $_GET["subscribe"];
} else {

    return '{
        error: "no subscribe" 
    }';
}

$latestTime = isset($_SESSION["alertTime"]) ? $_SESSION["alertTime"] : "";

$param = array(

    "maxWaitTimeSeconds" => 2,
    "maxMessages" => 50,
    "latestTime" => $latestTime
);

if (isset($_GET["wait"])) {

    $param["maxWaitTimeSeconds"] = $_GET["wait"];
}

if (isset($_GET["limit"])) {

    $param["maxMessages"] = $_GET["limit"];
}

$threshold = getenv("THRESHOLD");
$threshold = isset($_GET["threshold"]) ? $_GET["threshold"] : $threshold;

/*GET SUBSCRIBE*/

$sub = $FaceID->getsub($subscribe, $param);
//exit();
//var_dump($sub);

$data = array();
$Tool = new Tools();



foreach ($sub as $key => $item) {

    $ackID = $item["ackId"];

//    var_dump($item);

    $person = base64_decode($item["message"]["data"]);
    $person = json_decode($person, false);

    if ($person->messageType == "MESSAGE_TYPE_ALERT") {

        $result = array(
            "ackID" => $ackID,
            "tags" => $person->originalPhotoTags,
            "score" => $person->score,
            "capture" => $person->capturedPhotos->face,
            "photoID" => $person->extraTopk[0]->photoId,
            "capturedTime" => $person->capturedTime,
            "capturedTimeFormat" => $Tool->UTC2Local($person->capturedTime),
            "messageType" => $person->messageType,
            "videoId" => $person->videoId,
            "alertTime" => $item["alertTime"]

        );


        /*INSERT DB*/
        $param = (object)array(

            "name" => $name,
            "capture" => $capture,
            "analyze" => $faces->faces[0],
            "video" => $video
        );

        /*CHECK THRESH HOLD*/
        $data[] = $result;

        if (isset($person->clipId) && $person->clipId != "") {

            $id = $Monitor->addPerson($person);
        }


//        var_dump($person);
    }
}
//var_dump($data);
echo json_encode($data);