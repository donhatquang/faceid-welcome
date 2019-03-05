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

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

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
                "name" => $person->unicodeName,
                "description" => $person->description,
                "country" => $person->country,
                "gender" => $person->gender,
                "birthday" => $person->birthday,
                "idPerson" => $person->idPerson

            )
        );

        $result = $this->http->post($url, json_encode($data));

        return $result;
    }

    public function getsub($subscribe, $param)
    {

        //API URL
        $subscribe = rawurlencode($subscribe);

        $url = $this->host . '/subscriptions/' . $subscribe . "/messages";

        $result = $this->http->get($url, $param);

        if ($result["code"] == 200) {

            $result = json_decode($result["content"], true);
            $result = $result["receivedMessages"];


        }

        return $result;
    }


    public function analyze($photoData)
    {

        $url = $this->host . '/query/analyze';

//        $data = array("photoData" => $photoData,


        $data = '{
            "analyzeOptions" : {
                "attributeTypes" : {
                "age" : true,
                "blurriness" : true,
                "eyeStatus" : true,
                "gender" : true,
                "minority" : true,
                "mouthStatus" : true,
                "pose" : true,
                "quality" : true
                },
                "qualityCheckOptions" : {
                    "scenario" : "CAPTURE"
                },
                "extractFeature" : true,
                "extractLandmark" : true
            },
            "photoData": "'.$photoData.'"
            }';

        $result = $this->http->post($url, $data);

        $result = ($result["content"]);

        return $result;

    }

    public function confirm($subscribe, $ackids)
    {

        //API URL
        $subscribe = rawurlencode($subscribe);

        $url = $this->host . '/subscriptions/' . $subscribe . "/messages/acknowledge";
//        $data = json_encode($ackids);


        $result = $this->http->post($url, $ackids);

        //        {"ackIds":["f43b2058-ab42-714a-ad41-dcf15bc61597"]}:

        return $result;
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