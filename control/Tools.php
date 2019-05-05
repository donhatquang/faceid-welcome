<?php
/**
 * Created by PhpStorm.
 * User: TVH-MT171801
 * Date: 3/20/2019
 * Time: 4:58 PM
 */
class Tools
{

    public function groupDate($data, $param = "createTime")
    {

        $group = [];

        foreach ($data as $item) {

            $key = 0;

            if (isset($item->{$param})) {

                $key = $item->{$param}->date;


            } else if (isset($item->date)) {
                $key = $item->date;
                $item->{$param} = (object)array("date" => $item->date, "time" => $item->time);
            }

            $group[$key][] = $item;
//
//            if (is_array($group) && count($group[$key]) > 1) var_dump($key);
        }


        return $group;
    }

    public function findObject($array, $key, $target)
    {

        $item = null;
        foreach ($array as $struct) {
            if ($target == $struct->{$key}) {
                return $item = $struct;
//                break;
            }
        }

        return false;
    }

    public function UTC2Local($dateInUTC)
    {

//        $group = array();

        date_default_timezone_set('Asia/Ho_Chi_Minh');

//        foreach ($data as $key => $item) {

//            $dateInUTC = $item->{$param};

        $localTime = strtotime($dateInUTC . ' UTC');
//            $localTime->setTimeZone(new DateTimeZone('Asia/Ho_Chi_Minh'));
        $dateInLocal = date("Y-m-d H:i:s", $localTime);

//            $item->face->dateInLocal = $dateInLocal;

        list($date, $time) = explode(" ", $dateInLocal);

//            $data[$key] = $item;
        $group = (object)array(

            "date" => $date,
            "time" => $time,
            "localTime" => $dateInLocal
        );
//

        return $group;
    }
}