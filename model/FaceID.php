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
    private $con;

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
            "photoData": "' . $photoData . '"
            }';

        $result = $this->http->post($url, $data);

        $result = ($result["content"]);

//        var_dump($result);

        return $result;

    }

    public function getPerson($photoID)
    {

        $sql = "SELECT * FROM `monitor` WHERE `photoID` LIKE '" . $photoID . "' ORDER BY quality DESC LIMIT 5";
        $query = $this->con->query($sql);

        if ($query->num_rows != 0) {

            return $query->fetch_object();
        }
        else
            return false;
    }

    public function addDB($photoID, $name, $capture, $face)
    {

        $quality = round($face->attributes->quality * 100, 2);
        $face_analyze = json_encode($face->attributes);

        $getPerson = $this->getPerson($photoID);

        if ($getPerson == false) {

            $sql = "INSERT INTO `monitor`(`photoID`, `name`, `face_analyze`,`quality`, `capture`)
          VALUES ('" . $photoID . "', '" . $name . "', '" . $face_analyze . "', '" . $quality . "', '" . $capture . "')";

        } else {

             $sql = "UPDATE `monitor` SET 
`quality` = '91.4',
`face_analyze` = '" . $face_analyze . "',
`quality` = '" . $quality . "',
`capture` = '" . $capture . "'
 
 WHERE `monitor`.`id` LIKE '" . $getPerson->id . "'";

        }

        $query = $this->con->query($sql);

//        echo $this->con->error;
//        echo $this->con->affected_rows;

        //        echo mysqli_error($this->con);
//        var_dump($query);

//        echo "<hr/>";
//        echo $id = $this->con->insert_id;

        return;

    }

    /**
     * @return mixed
     */

    public function getHighest()  {

        $top = array("Lê Thảo Nguyên", "Cao Bình Dương",  "Dương Thị Nhung",  "Dương Thanh Phương", "Nguyễn Hải Yến", "Trịnh Thị Yến Nhi", "Nguyễn Trang Nhung", "Phí Thị Thu Hoài");
//        $top = array();

        $sql = "SELECT idPerson,unicodeName,description,position FROM `person`";
        $query = $this->con->query($sql);

        $result = array();

        while ($row = $query->fetch_object()) {

            if (array_search($row->unicodeName, $top)) {

                $result[] = $row;
            }


        }

        return $result;

    }

    public function getHighQuality()
    {



        $sql = "SELECT * FROM `monitor` WHERE `face_analyze` LIKE '%FEMALE%'  ORDER BY quality DESC ";
        $query = $this->con->query($sql);

        $result = array();

        if ($query->num_rows > 0) {

            while ($row = $query->fetch_object()) {
                $row->face_analyze = json_decode($row->face_analyze);
                $result[] = $row;

            }

        }
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