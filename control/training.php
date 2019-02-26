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

$photo_album = "0b18c1df-a5f5-9c8b-7a3f-524c7f088bfd";

foreach ($data as $key=>$person) {

    $FaceID = new FaceID();
    $FaceID->setPhotoAlbum($photo_album);

//    if ($key < 1) {

        var_dump($person);

        echo $Face->addPerson($person);
//    }
}

