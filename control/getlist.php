<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 3/14/2019
 * Time: 4:51 PM
 */

header("Content-type: application/json; charset:utf-8");

$list = (isset($_GET["act"])) ? $_GET["act"] : "photoAlbums";


require("../model/FaceQuery.php");

$FaceQuery = new FaceQuery();

$result = $FaceQuery->getRangeList($list);

echo $result;
//echo json_encode($result);
