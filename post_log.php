<?php
	session_start();
    include "control/config.php";
    include "control/model.php";

    $model = new model();
    if(isset($_POST['maxid']) && isset($_POST['idCamera'])){
    	$maxid = $_POST['maxid'];
        $idCamera = $_POST['idCamera'];
    	$rows = $model->fetch("SELECT image.idImage, image.time, person.* FROM `image` JOIN person WHERE person.idPerson = image.idPerson AND image.idCamera = $idCamera AND image.idImage > $maxid ORDER BY image.idImage DESC LIMIT 1");
        foreach($rows as $row){
            $res = json_encode(array(
                'status' => 200,
                'data' => array(
                    'idImage' => $row['idImage'],
                    'time' => $row['time'],
                    'idPerson' => $row['idPerson'],
                    'name' => $row['name'],
                    'unicodeName' => $row['unicodeName'],
                    'birthday' => $row['birthday'],
                    'gender' => $row['gender'],
                    'idCode' => $row['idCode'],
                    'country' => $row['country'],
                    'description' => $row['description'],
                    'b64Face' => $row['b64Face'],
                    'b64Image' => $row['b64Image']
                )
            ));
            echo $res;
            return;
        }
    }
?>