<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 2/26/2019
 * Time: 2:17 PM
 */
session_start();

require("../vendor/autoload.php");

$dotenv = Dotenv\Dotenv::create(__DIR__ . "/..");
$dotenv->load();

require("AipHttpClient.php");


class FaceID
{
    private $host;
    private $analyze_host;
    private $time_server;

    private $con;
    private $photo_album;
    private $http;


    public function __construct()
    {

        $this->http = new AipHttpClient();
        $this->checkSetting();
    }

    public function checkSetting()
    {




        if (isset($_SESSION["param"])) {

            $param = $_SESSION["param"];
        }
        else {

            $param = array(

                "host" => getenv("HOST"),
                "analyze_host" => getenv("ANALYZE")

            );

            /*SET VAR*/
            $_SESSION["param"] = $param;
        }

//        var_dump($param);
//        exit();

        $this->host = $param["host"];
        $this->analyze_host = $param["analyze_host"];

        return;
    }

    public function getTimeServer()
    {

        try {

            $result = array(

                "time" => gmdate("Y-m-d\TH:i:s\Z")
            );


            return json_encode($result);
        } catch (Exception $exception) {

            echo($exception);


        }

    }

    /**
     * @param mixed $con
     */
    public function setCon($con)
    {
        $this->con = $con;
    }

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

    /**
     * @param mixed $photo_album
     */
    public function setPhotoAlbum($photo_album)
    {
        $this->photo_album = $photo_album;
    }

    public function addPerson($person, $type = "person")
    {
//        $this->photo_album = $photo_album;


        //API URL
        $url = $this->host . '/photos';


        if ($type == "star") {

            $description = $person->Cat . " - " . $person->Country;

            $image = $person->Image;

            $b64_image = base64_encode(file_get_contents("D:\\7000_cele\\image\\" . $image));

            $data = array(
                "photoAlbumId" => $this->photo_album,
                "name" => $person->Name,
                "description" => $description,
                "format" => "image/jpeg",
                "id" => "",

//            IMAGE DATA
                "data" => $b64_image,

                "tags" => array(
                    "name" => $person->Name,
                    "description" => $description,
                ));


        } else {
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
                    "birthday" => $person->birthday


                )
            );
        }

//setup request to send json via POST


        $result = $this->http->post($url, json_encode($data));

        return $result;
    }

    public function emotion($photoData)
    {

        $url = $this->analyze_host . "/api/agender/";

        $result = $this->http->post($url, array(
            "photoData" => $photoData
        ));

        if ($result["code"] == 200) {

            return $result["content"];
        }

        return false;

    }

    public function getsub($subscribe, $param)
    {

        //API URL
        $subscribe = rawurlencode($subscribe);

        $url = $this->host . '/subscriptions/' . $subscribe . "/messages";

//        var_dump($param);

        $result = $this->http->get($url, $param);

        if ($result["code"] == 200) {

            $result = json_decode($result["content"], true);
            $result = $result["receivedMessages"];

            /*UPDATE TIME*/
            if (count($result) != 0) {

                $_SESSION["alertTime"] = $result[0]["message"]["publishTime"];
            }

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
            "photoData": "' . $photoData . '"
            }';

        $result = $this->http->post($url, $data);

        $result = ($result["content"]);

//        var_dump($result);

        return $result;

    }


    /**
     * @return mixed
     */


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