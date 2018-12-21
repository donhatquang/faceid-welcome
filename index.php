<?php
    session_start();
    include "control/config.php";
    include "control/model.php";
    $model = new model();

    $idCamera = 1;
    if(isset($_GET['idCamera'])){
        $idCamera = $_GET['idCamera'];
    }

    $camera = $model->fetch_one("SELECT * FROM camera JOIN location WHERE idCamera = $idCamera");
    if(!isset($camera)){
        echo "<h1>ERROR! Camera not found!</h1>";
        exit(1);
    }
    $maxid = $model->fetch_one("SELECT max(idImage) as maxid FROM image WHERE image.idCamera=".$camera['idCamera'])['maxid'];



    $maxid = $maxid == null?0:$maxid;
/*var_dump($maxid);

    exit();*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="refresh" content="3600" >
<!--     <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png"> -->
    <title>FaceID Welcome</title>
    <link href="dist/css/style.css?<?php echo rand(); ?>" rel="stylesheet">
    <script src="dist/js/snow.js" language="JavaScript"></script>
</head>
<body>

<!--<div >
<iframe id="youtube-iframe" width="177" height="100" src="https://www.youtube.com/embed/2ES1N39XjlI?enablejsapi=1&controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>-->

<!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
<div id="player" class="video"></div>

<script>
    // 2. This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '100',
            width: '177',
            videoId: '2ES1N39XjlI',
            startSeconds: 10,
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {

        event.target.setVolume(30);
        event.target.setLoop(true);

        event.target.playVideo();

        console.log("youtube ok");
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var done = false;
    function onPlayerStateChange(event) {
        /*if (event.data == YT.PlayerState.PLAYING && !done) {
            setTimeout(stopVideo, 6000);
            done = true;
        }*/
    }
    function stopVideo() {
        player.stopVideo();
    }
</script>
<!--<img id="logo" src="dist/img/logo_white.png">-->

<!--SNOW-->
<canvas id="canvas"></canvas>

    <div id="main-area">
        <!--<div id="board">
            <img id="board-img" src="dist/img/Blank-03.png">
            <div id="board-text"></div>
        </div>-->

        <div id="video">
            <img src="<?php echo $camera['outHTTPUrl'] ?>" alt="">
        </div>
    </div>
    <div id="right-area">
        <div id="info-area">
        </div>
        <div id="log-area">

        </div>
    </div>
</body>
<script src="dist/js/jquery-3.2.1.min.js"></script>
<script src='dist/js/responsivevoice.js'></script>
<script>
    $(function () {
        var maxid = <?php echo $maxid ?>;
        var idCamera = <?php echo $camera['idCamera'] ?>;
        var oldPerson = undefined;
        var duration = 0;
        var log_person = new Array();
        var log_duration = new Array();
        var MAX_DURATION = 10;

        function setEmpty(){
            $('#info-area').html("");
            $('#board-img').attr('src', 'dist/img/Blank-03.png');
            $('#board-text').html('');
            oldPerson = undefined;
            duration = 0;
        }

        function speak(person){
            var isJapan = false;
            var isMorning = false;
            var isAfternoon = false;
            var isEvening = false;
            var isMale = false;
            var isFemale = false;
            if(person['country'] == 'japan') isJapan = true;
            var hour = person['time'].split(/[- :]/)[3];
            if(0 <= hour && hour < 12)  isMorning = true;
            else if(12 <= hour && hour < 19) isAfternoon = true;
            else if(19 <= hour) isEvening = true;
            if(person['gender'] == 'Male' || person['gender'] == 'male') isMale = true;
            else if(person['gender'] == 'Female' || person['gender'] == 'female') isFemale = true;
            
            var text = '';
            var c = Math.floor((Math.random() * 2) + 1);
//             if (person['idPerson'] == 99){
//                 text = "Chào ngài chủ tịch Hoàng Tô, chúc ngài ";
//                 if(isMorning) text += ' buổi sáng vui vẻ';
//                 if(isAfternoon) text += ' buổi chiều vui vẻ';
//                 if(isEvening) text += ' buổi tối vui vẻ';
//                 if(responsiveVoice.voiceSupport()) {
//                     responsiveVoice.speak(text, "Vietnamese Female");
//                 }
//                 return;
//             }
            if (isJapan) {
                if (isMorning && isMale) {
                    if(c == 1) text = person['unicodeName'] + 'さんおはようございます';
                    else if(c == 2) text = person['unicodeName'] + 'さんおはようございます';
                } else if (isMorning && isFemale) {
                } else if (isAfternoon && isMale) {
                    if(c == 1) text = person['unicodeName'] + 'さんこんにちは';
                    else if(c == 2) text = person['unicodeName'] + 'さんこんにちは';;
                } else if (isAfternoon && isFemale) {
                } else if (isEvening && isMale) {
                    if(c == 1) text = person['unicodeName'] + 'さはようございます';
                    else if(c == 2) text = person['unicodeName'] + 'さはようございます';
                } else if (isEvening && isFemale) {
                }
                if(responsiveVoice.voiceSupport() && isMale) {
                    responsiveVoice.speak(text, "Japanese Female");
                }
                else if(responsiveVoice.voiceSupport() && isFemale) {
                    responsiveVoice.speak(text, "Japanese Male");
                }
            } else {
                if (isMorning && isMale) {
                    if(c == 1) text = 'Chào buổi sáng anh ' + person['unicodeName'];
                    else if(c == 2) text = 'Chúc anh ' + person['unicodeName'] + ' buổi sáng vui vẻ';
                } else if (isMorning && isFemale) {
                    if(c == 1) text = 'Chào buổi sáng chị ' + person['unicodeName'];
                    else if(c == 2) text = 'Chúc chị ' + person['unicodeName'] + ' buổi sáng vui vẻ';
                } else if (isAfternoon && isMale) {
                    if(c == 1) text = 'Chào buổi chiều anh ' + person['unicodeName'];
                    else if(c == 2) text = 'Chúc anh ' + person['unicodeName'] + ' buổi chiều vui vẻ';
                } else if (isAfternoon && isFemale) {
                    if(c == 1) text = 'Chào buổi chiều chị ' + person['unicodeName'];
                    else if(c == 2) text = 'Chúc chị ' + person['unicodeName'] + ' buổi chiều vui vẻ';
                } else if (isEvening && isMale) {
                    if(c == 1) text = 'Chào buổi tối anh ' + person['unicodeName'];
                    else if(c == 2) text = 'Chúc anh ' + person['unicodeName'] + ' buổi tối vui vẻ';
                } else if (isEvening && isFemale) {
                    if(c == 1) text = 'Chào buổi tối chị ' + person['unicodeName'];
                    else if(c == 2) text = 'Chúc chị ' + person['unicodeName'] + ' buổi tối vui vẻ';
                }
                if(responsiveVoice.voiceSupport() && isMale) {
                    responsiveVoice.speak(text, "Vietnamese Female");
                }
                else if(responsiveVoice.voiceSupport() && isFemale) {
                    responsiveVoice.speak(text, "Vietnamese Male");
                }
            }
        }

        function setLogContent(){
            var log_content = "<ul>";
            for(var i = 0; i < log_person.length; i++){
                log_content += "<li>";
                log_content += "<img class='avatar' src='data:image/jpeg;base64, ";
                log_content += log_person[i]['b64Face'];
                log_content += "'>";
                log_content += "<div class='name'>" + log_person[i]['name'] + "</div>";
                log_content += "<div class='description'>" + log_person[i]['time'] + "</div>";
                log_content += "</li>"
            }
            $('#log-area').html(log_content);
        }

        function setContent(person){
            if(person['idPerson'] == -1) return;
            var name = person['name'];
            if(person['gender'] == 'Male' || person['gender'] == 'male'){
                name = 'Mr. ' + name;
            } else if(person['gender'] == 'Female' || person['gender'] == 'female'){
                name = 'Ms. ' + name;
            }
            person['name'] = name;

            var content = "<img class='avatar' src='data:image/jpeg;base64, ";
            content += person['b64Image'];
            content += "'>";
            content += "<div class='name'>" + name + "</div>";
            content += "<div class='description'>" + person['description'] + "</div>";

            $('#info-area').html(content);
            var hour = person['time'].split(/[- :]/)[3];
            if(0 <= hour && hour < 12){
                $('#board-img').attr('src', 'dist/img/Morning-03.png');
            } else if(12 <= hour && hour < 19){
                $('#board-img').attr('src', 'dist/img/Afternoon-03.png');
            } else if(19 <= hour){
                $('#board-img').attr('src', 'dist/img/Evening-03.png');
            }
            $('#board-text').html(name);
            if (person['idPerson'] != oldPerson) speak(person);
            maxid = person['idImage'];
            oldPerson = person['idPerson'];
            duration = MAX_DURATION;



            for(var i = 0; i < log_person.length; i++){
                if(log_person[i]['idPerson'] == person['idPerson']){
                    log_person.splice(i, 1);
                    log_duration.splice(i, 1);
                    break;
                }
            }
            log_person.unshift(person);
            log_duration.unshift(MAX_DURATION);
            log_person = log_person.slice(0, 5);
            log_duration = log_duration.slice(0, 5);
        }

        function getCurrentLog(){
            var dict = {
                type: 'POST',
                url: 'post_log.php',
                data: {'maxid': maxid-1, 'idCamera': idCamera},
                dataType: 'json',
                success: function (data) {
                    // console.log('success');
                    // $('#hidden').val(data);// first set the value     
                },
                complete: function (data) {
                    if(data['responseJSON'] != undefined){
                        var row = data['responseJSON']['data'];
                        console.log(row);
                        setContent(row);
                    }
                    setTimeout(getCurrentLog, 500);
                }
            };
            // console.log(dict);
            $.ajax(dict);
        };

        function countDown(){
            duration -= 1;
            if (duration <= 0) setEmpty();
            for (var i = 0; i < log_duration.length; i++){
                log_duration[i] -= 1;
                if(log_duration[i] <= 0){
                    if(log_duration[i] == 0){
                        log_person.splice(i, 1);
                        log_duration.splice(i, 1);
                        i -= 1;
                    }
                }
            }
            setLogContent();
            setTimeout(countDown, 1000);
        }
        setTimeout(getCurrentLog, 200);
        setTimeout(countDown, 1000);
    });
</script>
</html>
