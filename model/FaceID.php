<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/26/2019
 * Time: 2:17 PM
 */
require("AipHttpClient.php");

class FaceID
{
    private $host = "http://192.168.51.12:8080/v4";
    private $photo_album;
    private $http;

    public function __construct()
    {
//        $this->host = $host;

        $this->http = new AipHttpClient();
    }

    /**
     * @param mixed $photo_album
     */
    public function setPhotoAlbum($photo_album)
    {
        $this->photo_album = $photo_album;
    }

    public function addPerson($person)
    {
//        $this->photo_album = $photo_album;

        //API URL
        $url = $this->host . '/photos';


//setup request to send json via POST
        $data = array(
            "photoAlbumId" => $this->photo_album,
            "name" => $person->unicodeName,
            "description" => $person->description,
            "format" => "image/jpeg",
            "id" => "",

//            IMAGE DATA
            "data" => $person->b64Face,

            "tags" => array(

                "country" => $person->country,
                "gender" => $person->gender,
                "birthday" => $person->birthday,
                "idPerson" => $person->idPerson

            )
        );

        $result = $this->postdata($data, $url);

        return $result;
    }

    public function getsub($subscribe, $param) {

        //API URL
        $subscribe = rawurlencode ($subscribe);

        $url = $this->host . '/subscriptions/'.$subscribe."/messages";

        $result = $this->http->get($url, $param);

        if ($result["code"] == 200) {

            $result = json_decode($result["content"], true);
            $result = $result["receivedMessages"];


        }

        return $result;
    }

    public function confirm($ackid) {


//        {"ackIds":["f43b2058-ab42-714a-ad41-dcf15bc61597"]}:

        return;
    }

    public function postdata($data, $url)
    {

        $payload = json_encode($data);


//create a new cURL resource
        $ch = curl_init($url);

//attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

//set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
        $result = curl_exec($ch);

//close cURL resource
        curl_close($ch);

        return $result;
    }
}