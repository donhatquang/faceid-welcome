<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/25/2019
 * Time: 6:03 PM
 */

require  ("../model/FaceID.php");

$data = file_get_contents("person.json");
$data = json_decode($data, false);

//var_dump($data);
//echo count($data);
//var_dump($data[0]);

$photo_album = "3e47857f-7d30-318f-b297-82f24eb66924";

foreach ($data as $key=>$person) {

    $FaceID = new FaceID();
    $FaceID->setHost("http://192.168.51.8:8080/v4");
    $FaceID->setPhotoAlbum($photo_album);

    if ($key >= 1) {

//        var_dump($person);

        $result = $FaceID->addPerson($person);
        var_dump($result);
    }
}

