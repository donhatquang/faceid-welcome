<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 3/7/2019
 * Time: 4:45 PM
 */

require("../model/FaceID.php");
require("config.php");

header("Content-type: application/json; charset:utf-8");

$FaceID = new FaceID();

/*SET DB*/
//var_dump($con);

$FaceID->setCon($con);

$data = $FaceID->getHighQuality();
//$data = $FaceID->getHighest();

echo json_encode($data);
//var_dump($data);