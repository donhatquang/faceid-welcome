<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 3/14/2019
 * Time: 4:51 PM
 */

//header("Content-type: application/json; charset:utf-8");

require("../model/FaceID.php");

$FaceID = new FaceID();
$result = $FaceID->getTimeServer();

echo $result;
//echo json_encode($result);
