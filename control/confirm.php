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

if (isset($_POST["subscribe"])) {

    $subscribe = $_POST["subscribe"];
} else {

    $subscribe = "main";
}

$ackID;
if (isset($_POST["ackid"])) {

    $ackID = $_POST["ackid"];

} else return;

$data = $FaceID->confirm($subscribe, $ackID);

echo json_encode($data);