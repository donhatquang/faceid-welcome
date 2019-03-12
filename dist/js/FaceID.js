var FaceID = function () {

    var config = {
        max_timeout: 3000 * 1,
        ack_max_timeout: 1000 * 1,
        host: "http://192.168.51.12:8080/v4",
        area: '.faceid-list',
        flag: true, /*true is init ready*/
        ack: true
    };
    var subscribe = subscribe_config;

    // var flag = false;

    /*INIT UI*/
    var subinit = function (data) {

        /*REMOVE*/
        $(config.area).html("");

        // console.log(data);
        for (i in data) {

            if (i >= 10) return;

            if (i < 5) {
                var area = config.area + "-1";
            }
            else if (i >= 5) {
                area = config.area + "-2";
            }

            //-------------
            var person = data[i];

            var info = person.tags;

            var photoID = person.photoID;
            var capture = person.capture;
            var score = Math.round(person.score);
            /*THRESH-HOLD*/

            var name = info.name;
            var date = tools.formatDate(new Date(person.capturedTime));
            // var idPerson = info.idPerson;

            /*IMAGE*/
            var image = $("<img class=\"\">").attr({

                "class": "people",
                "src": config.host + '/photos/' + capture + '/data'

            }).prop('outerHTML');


            /*CHECK EXIST*/
            // if ($("div[data-rel='"+photoID+"']").length == 0) {}

            var text = '  <div class="row text-card" data-rel="' + photoID + '">\n' +
                '                            <div class="col-xl-8">\n' +
                '                                <div class="title">' + name + '</div>\n' +
                '                                <div class="description">' + info.description + '</div>\n' +
                '                                <p>' + date + '</p>\n' + //+ ' (Score: ' + score
                '                                <div class="progress">\n' +
                '                                    <div class="progress-bar progress-bar-striped progress-bar-animated nice" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="col-xl-4">\n' + image +


                /*GENDER*/

                '                            </div>\n' +
                '                        </div>';

            $(area).append(text);

            /*ANALYZE*/
            analyze(person);

        }
        return;
    };


    var getsub = function () {

        var url = 'control/getsub.php';
        var flag = config.flag;

        $.getJSON(url, subscribe, function (data) {

            //--------------

            /*REMOVE DUPLICATE*/
            var new_data = tools.removeDuplicates(data);

            console.log("Flag: " + flag);

            //$(".people").length == 0

            if (flag == true || (flag == false && data.length != 0)) {

                /*INIT*/
                subinit(new_data);
            }

            /*CONFIRM MESSAGE*/
            if (new_data.length != 0) {

                setTimeout(confirm(data), config.ack_max_timeout);
            }

            /*REQUEST AGAIN*/
            setTimeout(getsub, config.max_timeout);
            // setTimeout(getMonitor, config.max_timeout);

        })

        return;
    };

    /*ANALYZE*/
    var analyze = function (person) {

        // return;

        // photoID, name, capture,

        var url = 'control/analyze.php';
        var info = person.tags;

        // var idPerson = info.idPerson;
        var name = info.name;

        var photoID = person.photoID;
        var capture = person.capture;

        var param = {

            photoID: photoID,
            name: name,
            capture: capture,

        }

        /**/
        var obj = $("div[data-rel='" + photoID + "']");

        $.getJSON(url, param, function (data) {


            var data = data.faces[0];
            var attr = data.attributes;
            var pose = attr.pose;

            /*EYES*/
            var eyeStatus = attr.eyeStatus;
            var left_eye = checkEye(eyeStatus.leftEye);
            var right_eye = checkEye(eyeStatus.rightEye);

            var age = Math.round(attr.age);
            var gender = attr.gender;
            var quality = Math.round(attr.quality * 100 * 0.8) + "%";

            var image_gender = $("<img class=\"\">").attr({

                "class": "gender-left",
                "src": 'dist/image/' + gender.toLocaleLowerCase() + ".png"

            }).prop('outerHTML');

            obj.find(".title").append(" (Age: " + age + ")");
            obj.find(".progress-bar").css({width: quality}).html(quality);

/*,' + left_eye.status +*/
            obj.find(".description").append('<p>' +
                'Mắt trái: ' + left_eye.glass +
                ' - Mắt phải: ' + right_eye.glass +


                '</p>');

            /*ADD GENDER*/ /*ADD CUBE*/
            obj.find(".people").after(image_gender).after(draw_cube(pose));




            /*ADD EYE STATUS*/
            // "NO_GLASSES_EYE_OPEN"

            console.log(name);
            console.log(info);
        })

        return;
    };

    var checkEye = function (eyeStatus) {

        var eye = {

            glass: "không kính",
            status: "mở mắt"
        };

        if (eyeStatus.indexOf("NORMAL_GLASSES") != -1) eye.glass = "đeo kính";
        if (eyeStatus.indexOf("EYE_OPEN") == -1) eye.status = "nhắm mắt";

        return eye;
    }

    /*DRAW CUBE*/
    var draw_cube = function (pose) {

        var yaw = Math.round(tools.radians_to_degrees(pose.yaw))*(-1), //2
            roll = Math.round(tools.radians_to_degrees(pose.roll))*(-1), //3
            pitch = Math.round(tools.radians_to_degrees(pose.pitch)); //1


        var text = '<div class="cube" style="transform: rotateX('+pitch+'deg) rotateY('+yaw+'deg) rotateZ('+roll+'deg);">\n' +
            '    <div class="cube-side cube-front"></div>\n' +
            '    <div class="cube-side cube-back"></div>\n' +
            '    <div class="cube-side cube-left"></div>\n' +
            '    <div class="cube-side cube-right"></div>\n' +
            '    <div class="cube-side cube-top"></div>\n' +
            '    <div class="cube-side cube-bottom"></div>\n' +
            '</div>';

        return text;
    }

    // var analyze_init



    /*ACK*/
    //messageType=="MESSAGE_TYPE_ALERT"
    var confirm = function (data) {


        if (config.ack == false) return;

        // return;
        console.log("ACK Message: " + config.ack);


        var ackid = new Array();

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

        /*POST ACK*/
        $.post(url, {"ackid": ack_str}, function (data) {

            console.log(data);
        });

        // console.log(ackid);

        return;
    };

    /*DEFINE*/
    /*PUBLIC METHOD*/
    this.subinit = subinit;
    this.getsub = getsub;
    this.analyze = analyze;

    /*PRIVATE VAR*/
    this.config = config;
}