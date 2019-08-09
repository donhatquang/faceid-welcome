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

if (isset($_POST["photoData"])) {

    $photoData = $_POST["photoData"];

    echo $emotion = $FaceID->emotion($photoData);
}
