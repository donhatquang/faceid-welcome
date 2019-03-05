<?php

$param = array(

    "wait" => 5,
    "limit" => 10
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
    <meta charset="UTF-8">
    <title>FaceID - TVLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="dist/assets/vendor/bootstrap/css/bootstrap.min.css">
    <script src="dist/assets/vendor/jquery/jquery.min.js"></script>
    <script src="dist/assets/vendor/popper/popper.min.js"></script>
    <script src="dist/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="dist/assets/css/style.css">

    <script language="JavaScript">


        var subscribe_config = <?php echo $param_str?>;
    </script>

    <script src="dist/js/jquery-3.2.1.min.js"></script>
    <!--<script src='dist/js/responsivevoice.js'></script>-->
    <script src='dist/js/FaceID.js?<?php echo rand(); ?>'></script>
    <script src='dist/js/main.js?<?php echo rand(); ?>'></script>

</head>
<body>

<div class="container-fluid">
    <div class="confetti">
    </div>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-xl-4"></div>
                <div class="col-xl-4">
                    <img src="dist/image/Materials-01-02.png" class="logo">
                </div>
                <div class="col-xl-4"></div>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-5 interact interact-left">
                    <div class="container-fluid faceid-list faceid-list-1 ">


                    </div>
                </div>
                <div class="col-xl-2 d-flex align-items-center">
                    <img class="layer" src="dist/image/Materials-01-04.png">
                </div>
                <div class="col-xl-5 interact interact-right">
                    <div class="container-fluid faceid-list faceid-list-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>