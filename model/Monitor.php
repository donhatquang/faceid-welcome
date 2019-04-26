<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 3/14/2019
 * Time: 4:55 PM
 */

class Monitor
{

    private $con;

    public function __construct($con)
    {
        $this->con = $con;
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


    public function getPerson($photoID)
    {

        $sql = "SELECT * FROM `monitor` WHERE `photoID` LIKE '" . $photoID . "' ORDER BY quality DESC LIMIT 5";
        $query = $this->con->query($sql);

        if ($query->num_rows != 0) {

            return $query->fetch_object();
        } else
            return false;
    }


    public function addDB($photoID, $name, $capture, $face)
    {

        $quality = round($face->attributes->quality * 100, 2);
        $face_analyze = json_encode($face->attributes);

//

        $sql = "INSERT INTO `monitor`(`photoID`, `name`, `face_analyze`,`quality`, `capture`, `cameraq`)
          VALUES ('" . $photoID . "', '" . $name . "', '" . $face_analyze . "', '" . $quality . "', '" . $capture . "')";

        /*
                $getPerson = $this->getPerson($photoID);

                if ($getPerson == false) {



                } else {

                    $sql = "UPDATE `monitor` SET
        `quality` = '91.4',
        `face_analyze` = '" . $face_analyze . "',
        `quality` = '" . $quality . "',
        `capture` = '" . $capture . "'

         WHERE `monitor`.`id` LIKE '" . $getPerson->id . "'";

                }*/

        $this->con->query($sql);

        return $id = $this->con->insert_id;


    }

}