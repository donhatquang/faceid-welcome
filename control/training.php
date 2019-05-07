<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/25/2019
 * Time: 6:03 PM
 */

ini_set('max_execution_time', 3600); //300 seconds = 5 minutes

require("../model/FaceID.php");

$data = file_get_contents("cele.json");
$data = json_decode($data, false);

//var_dump($data);
//echo count($data);
//var_dump($data[0]);

$photo_album = "f3472d25-384f-1299-50df-22aa7cbfb5ef";
$error_count = 0;

foreach ($data as $key => $person) {

    $FaceID = new FaceID();
    $FaceID->setHost("http://192.168.51.8:8080/v4");
    $FaceID->setPhotoAlbum($photo_album);

    if ($key == 35) {

//        var_dump($person);


        $result = $FaceID->addPerson($person, "star");

        $image = $person->Image;

        if ($result["code"] == 400) {

//        var_dump($person);

            echo $person->Name . " - " . $image;
            echo "<br/> Import Error <br/>";

            var_dump($result);

            $error_count++;

        } else {

            echo $person->Name . " - " . $image;
            echo "<br/> Import OK <br/>";

        }

        echo "<br/> ------------------ <br/>";

//    return;
    }


}


/*TOTAL*/
echo "error total: ".$error_count;
echo "<br/> successful: ". (count($data)-$error_count);