<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 3/14/2019
 * Time: 4:51 PM
 */
require("../model/FaceQuery.php");

header("Content-type: application/json; charset:utf-8");

if (isset($_POST["data"])) {

    $data = $_POST["data"];
} else {

    return "{}";
}


$FaceQuery = new FaceQuery();

$result = $FaceQuery->query_one_many($data);

echo $result;
