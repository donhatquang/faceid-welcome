$(function () {

    var oldPerson = undefined;
    var duration = 0;
    var MAX_DURATION = 10;

    var max_timeout = 1000*10;
    var host = "http://192.168.51.12:8080/v4";

   /* function setEmpty() {
        $('#info-area').html("");
        $('#board-img').attr('src', 'dist/img/Blank-03.png');
        $('#board-text').html('');
        oldPerson = undefined;
        duration = 0;
    }*/

    function speak(person) {
        var isJapan = false;
        var isMorning = false;
        var isAfternoon = false;
        var isEvening = false;
        var isMale = false;
        var isFemale = false;
        if (person['country'] == 'japan') isJapan = true;
        var hour = person['time'].split(/[- :]/)[3];
        if (0 <= hour && hour < 12) isMorning = true;
        else if (12 <= hour && hour < 19) isAfternoon = true;
        else if (19 <= hour) isEvening = true;
        if (person['gender'] == 'Male' || person['gender'] == 'male') isMale = true;
        else if (person['gender'] == 'Female' || person['gender'] == 'female') isFemale = true;

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
                if (c == 1) text = person['unicodeName'] + 'さんおはようございます';
                else if (c == 2) text = person['unicodeName'] + 'さんおはようございます';
            } else if (isMorning && isFemale) {
            } else if (isAfternoon && isMale) {
                if (c == 1) text = person['unicodeName'] + 'さんこんにちは';
                else if (c == 2) text = person['unicodeName'] + 'さんこんにちは';
                ;
            } else if (isAfternoon && isFemale) {
            } else if (isEvening && isMale) {
                if (c == 1) text = person['unicodeName'] + 'さはようございます';
                else if (c == 2) text = person['unicodeName'] + 'さはようございます';
            } else if (isEvening && isFemale) {
            }
            if (responsiveVoice.voiceSupport() && isMale) {
                responsiveVoice.speak(text, "Japanese Female");
            }
            else if (responsiveVoice.voiceSupport() && isFemale) {
                responsiveVoice.speak(text, "Japanese Male");
            }
        } else {
            if (isMorning && isMale) {
                if (c == 1) text = 'Chào buổi sáng anh ' + person['unicodeName'];
                else if (c == 2) text = 'Chúc anh ' + person['unicodeName'] + ' buổi sáng vui vẻ';
            } else if (isMorning && isFemale) {
                if (c == 1) text = 'Chào buổi sáng chị ' + person['unicodeName'];
                else if (c == 2) text = 'Chúc chị ' + person['unicodeName'] + ' buổi sáng vui vẻ';
            } else if (isAfternoon && isMale) {
                if (c == 1) text = 'Chào buổi chiều anh ' + person['unicodeName'];
                else if (c == 2) text = 'Chúc anh ' + person['unicodeName'] + ' buổi chiều vui vẻ';
            } else if (isAfternoon && isFemale) {
                if (c == 1) text = 'Chào buổi chiều chị ' + person['unicodeName'];
                else if (c == 2) text = 'Chúc chị ' + person['unicodeName'] + ' buổi chiều vui vẻ';
            } else if (isEvening && isMale) {
                if (c == 1) text = 'Chào buổi tối anh ' + person['unicodeName'];
                else if (c == 2) text = 'Chúc anh ' + person['unicodeName'] + ' buổi tối vui vẻ';
            } else if (isEvening && isFemale) {
                if (c == 1) text = 'Chào buổi tối chị ' + person['unicodeName'];
                else if (c == 2) text = 'Chúc chị ' + person['unicodeName'] + ' buổi tối vui vẻ';
            }
            if (responsiveVoice.voiceSupport() && isMale) {
                responsiveVoice.speak(text, "Vietnamese Female");
            }
            else if (responsiveVoice.voiceSupport() && isFemale) {
                responsiveVoice.speak(text, "Vietnamese Male");
            }
        }
    }


    function subinit(data) {

        $("#info-area").html("");

        // console.log(data);
        for (i in data) {

            var person = data[i];

            var info = person.tags;

            var photoID = person.photoID;
            var capture = person.capture;

            var name = info.name;
            var date = new Date(person.capturedTime);

            // console.log(name);

            $("<img>").attr({
                "src": host + '/photos/' + capture + '/data',
                "class": "avatar"

            }).appendTo("#info-area");


            var text = "<div class='name'>" + name + "</div>";
            text += "<div class='description'>" + info.description + "(" + date.getDate() + "/" + date.getMonth() + "-" + date.getHours() + ":" + date.getMinutes() + ")</div>";
            text += "<div class='datetime'>" + "</div>";

            $("#info-area").append(text);

        }
        return;
    }

    function setContent(person) {
        if (person['score'] == -1) return;
        var name = person['name'];
        if (person['gender'] == 'Male' || person['gender'] == 'male') {
            name = 'Mr. ' + name;
        } else if (person['gender'] == 'Female' || person['gender'] == 'female') {
            name = 'Ms. ' + name;
        }

        var content = "<img id='avatar' src='data:image/jpeg;base64, ";
        content += person['b64Image'];
        content += "'>";
        content += "<div id='name'>" + name + "</div>";
        content += "<div id='description'>" + person['description'] + "</div>";

        $('#info-area').html(content);
        var hour = person['time'].split(/[- :]/)[3];
        if (0 <= hour && hour < 12) {
            $('#board-img').attr('src', 'dist/img/Morning-03.png');
        } else if (12 <= hour && hour < 19) {
            $('#board-img').attr('src', 'dist/img/Afternoon-03.png');
        } else if (19 <= hour) {
            $('#board-img').attr('src', 'dist/img/Evening-03.png');
        }
        $('#board-text').html(name);
        if (person['idPerson'] != oldPerson) speak(person);
        maxid = person['idImage'];
        oldPerson = person['idPerson'];
        duration = MAX_DURATION;
    }

    var ackid;

    function confirm(data) {

        ackid = new Array();

        for (i in data) {

            ackid.push(data[i].ackID);

        }

        /*post*/

        var url = 'control/confirm.php';
        var ack_obj = {

            "ackIds": ackid
        }

        var ack_str = JSON.stringify(ack_obj);

        console.log(ack_str);

        $.post(url, {"ackid": ack_str}, function (data) {

            console.log(data);
        });

        // console.log(ackid);

        return;
    }

    function removeDuplicates(json_all) {
        var arr = [],
            collection = [];

        // console.log(json_all);

        for (index in json_all) {


            var value = json_all[index];


            if ($.inArray(value.tags.idPerson, arr) == -1) {
                arr.push(value.tags.idPerson);
                collection.push(value);

                // console.log(arr);
            }
        }
        ;
        return collection;
    }

    function getsub() {

        var url = 'control/getsub.php';

        $.getJSON(url, config, function (data) {

            var new_data = removeDuplicates(data);

            console.log(new_data);

            /*INIT*/
            subinit(new_data);

            /*CONFIRM MESSAGE*/
            // setTimeout(confirm(data), 1000);
        })

        /*REQUEST AGAIN*/
        setTimeout(getsub, max_timeout);

        return;
    }

    function getCurrentLog() {
        var dict = {
            type: 'GET',
            url: 'control/getsub.php',

            dataType: 'json',
            success: function (data) {
                // console.log('success');
                // $('#hidden').val(data);// first set the value
            },
            complete: function (data) {
                /*if(data['responseJSON'] != undefined){
                    var row = data['responseJSON']['data'];

                }*/
                console.log(data);
                subinit(data);

                setTimeout(getCurrentLog, max_timeout);
            }
        };
        // console.log(dict);
        $.ajax(dict);
    };

/*    function countDown() {
        if (duration == 0) {
            setEmpty();
        } else {
            duration -= 1;
        }
        setTimeout(countDown, 1000);
    }*/


    setTimeout(getsub);
    // setTimeout(countDown, 1000);
});
