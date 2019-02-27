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

$photo_album = "4de9c961-9b3a-4b78-adcf-5b42fd2d9193";

foreach ($data as $key=>$person) {

    $FaceID = new FaceID();
    $FaceID->setPhotoAlbum($photo_album);

    if ($key >= 1) {

//        var_dump($person);

        $result = $FaceID->addPerson($person);
        var_dump($result);
    }
}

