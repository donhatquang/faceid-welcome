<?php

require("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$host = getenv("EXTERNAL_HOST");


$param = array(

    "wait" => 5,
    "limit" => 50
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

    $param["subscribe"] = getenv("SUBSCRIBE");
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
    <link rel="stylesheet" href="dist/assets/css/style.css?<?php echo rand(); ?>">

    <script language="JavaScript">


        var request_param_global = <?php echo $param_str?>;
        var hiface_host = "<?php echo getenv("HOST"); ?>";
    </script>

    <script src="dist/js/jquery-3.2.1.min.js"></script>
    <!--<script src='dist/js/responsivevoice.js'></script>-->
    <script src='dist/js/tools.js?<?php echo rand(); ?>'></script>
    <script src='dist/js/analyze.js?<?php echo rand(); ?>'></script>


    <script src='dist/js/FaceID.js?<?php echo rand(); ?>'></script>
    <script src='dist/js/pubsub.js?<?php echo rand(); ?>'></script>


    <script src='dist/js/main.js?<?php echo rand(); ?>'></script>

</head>
<body>

<div class="container-fluid">
    <div class="confetti">
    </div>
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-11 interact interact-left">
                    <div class="container-fluid faceid-list">


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>