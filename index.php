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
    <link href="dist/css/style.css" rel="stylesheet">
</head>
<body>

    <img id="logo" src="dist/img/logo_white.png">
    <div id="main-area">
        <div id="board">
            <img id="board-img" src="dist/img/Blank-03.png">
            <div id="board-text"></div>
        </div>
        <div id="video">
            <img src="<?php echo $camera['outHTTPUrl'] ?>" alt="">
        </div>
    </div>
    <div id="info-area">
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

        function setContent(person){
            if(person['idPerson'] == -1) return;
            var name = person['name'];
            if(person['gender'] == 'Male' || person['gender'] == 'male'){
                name = 'Mr. ' + name;
            } else if(person['gender'] == 'Female' || person['gender'] == 'female'){
                name = 'Ms. ' + name;
            }

            var content = "<img id='avatar' src='data:image/jpeg;base64, ";
            content += person['b64Image'];
            content += "'>";
            content += "<div id='name'>" + name + "</div>";
            content += "<div id='description'>" + person['description'] + "</div>";

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
        }

        function getCurrentLog(){
            var dict = {
                type: 'POST',
                url: 'post_log.php',
                data: {'maxid': maxid, 'idCamera': idCamera},
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
                    setTimeout(getCurrentLog, 200);
                }
            };
            // console.log(dict);
            $.ajax(dict);
        };

        function countDown(){
            if (duration == 0){
                setEmpty();
            } else {
                duration -= 1;
            }
            setTimeout(countDown, 1000);
        }
        setTimeout(getCurrentLog, 200);
        setTimeout(countDown, 1000);
    });
</script>
</html>
