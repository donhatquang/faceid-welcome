<?php

$param = array(

    "wait" => 2,
    "limit" => 30
);

if (isset($_GET["wait"])) {

    $param["maxWaitTimeSeconds"] = $_GET["wait"];
}

if (isset($_GET["limit"])) {

    $param["limit"] = $_GET["limit"];
}

if (isset($_GET["subscribe"])) {

    $param["subscribe"] = $_GET["subscribe"];
} else {

    $param["subscribe"] = "cong chinh";
}
$param_str = json_encode($param);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="refresh" content="3600">
    <!--     <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png"> -->
    <title>FaceID Welcome</title>
    <link href="dist/css_event/style.css" rel="stylesheet">

    <script language="JavaScript">


        var config = <?php echo $param_str?>;
    </script>

</head>
<body>

<img id="logo" src="dist/img/logo_white.png">
<div id="main-area">
    <div id="board">
        <img id="board-img" src="dist/img/Blank-03.png">
        <div id="board-text"></div>
    </div>
    <div id="video">
<!--        <img src="--><?php //echo $camera['outHTTPUrl'] ?><!--" alt="">-->
    </div>
</div>
<div id="info-area">
</div>
<script src="dist/js/jquery-3.2.1.min.js"></script>
<!--<script src='dist/js/responsivevoice.js'></script>-->
<script src='dist/js/FaceID.js?<?php echo rand(); ?>'></script>
<script src='dist/js/main.js?<?php echo rand(); ?>'></script>
</body>

</html>
