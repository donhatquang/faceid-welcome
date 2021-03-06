<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 3/14/2019
 * Time: 5:30 PM
 */

//require("AipHttpClient.php");
require("../model/FaceID.php");

class FaceQuery
{
    private $http;
    private $host;

    public function __construct()
    {
        $this->http = new AipHttpClient();
        $FaceID = new FaceID();

        $this->host = $FaceID->getHost();

//        exit();

        return;
    }

    public function query_one_many($data)
    {

        $url = $this->host . '/query/search';


        $result = $this->http->post($url, $data);

        return $result["content"];
    }

    public function getRangeList($act = "photoAlbums")
    {

        $url = $this->host . "/" . $act;

        $result = $this->http->get($url);

        return $result["content"];
    }
}