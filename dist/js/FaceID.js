var FaceID = function () {

    var config = {
        max_timeout: 1000 * 1,
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
        $(config.area + "-1").html("");

        // console.log(data);
        for (i in data) {

            if (i >= 10) return;

            if (i < 5) {
                var area = config.area + "-1";
            }
            else if (i >= 5) {
                area = config.area + "-1";
            }

            //-------------
            var person = data[i];

            var info = person.tags;

            var photoID = person.photoID;
            var capture = person.capture;
            var score = Math.round(person.score);

            var name = info.name;
            var date = new Date(person.capturedTime);
            // var idPerson = info.idPerson;

            /*IMAGE*/
            var image = $("<img class=\"\">").attr({

                "class": "people",
                "src": config.host + '/photos/' + capture + '/data'

            }).prop('outerHTML');


            var datetime = date.getDate() + "/" + date.getMonth() + "-" + date.getHours() + ":" + date.getMinutes();

            /*CHECK EXIST*/
            // if ($("div[data-rel='"+photoID+"']").length == 0) {}

            var text = '  <div class="row text-card" data-rel="' + photoID + '">\n' +
                '                            <div class="col-xl-8">\n' +
                '                                <div class="title">' + name + '</div>\n' +
                '                                <div class="description">' + info.description + '</div>\n' +
                '                                <p>' + datetime  + '</p>\n' + //+ ' (Score: ' + score
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

    /*GET SUBSCRIBE*/
    var setFlag = function (status) {

        config.flag = status;
    };

    var getsub = function () {

        var url = 'control/getsub.php';
        var flag = config.flag;

        $.getJSON(url, subscribe, function (data) {

            //--------------

            /*REMOVE DUPLICATE*/
            var new_data = removeDuplicates(data);

            console.log("Flag: " + flag);
            if (flag == true) {

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

            var age = Math.round(attr.age);
            var gender = attr.gender;
            var quality = Math.round(attr.quality * 100 * 0.8) + "%";

            var image_gender = $("<img class=\"\">").attr({

                "class": "gender-left",
                "src": 'dist/image/'+gender.toLocaleLowerCase()+".png"

            }).prop('outerHTML');


            obj.find(".title").append(" (Age: " + age + ")");
            obj.find(".progress-bar").css({width: quality}).html(quality);

            obj.find(".people").after(image_gender);

            console.log(name);
            // console.log();
            // console.log("blur:"+ attr.blurriness);

            /*
           "attributes": {
"age": 27.864948,
"gender": "MALE",
"minority": "NO",
"blurriness": 0.0022596244,
"pose": {
"yaw": -0.073621795,
"pitch": 0.059973117,
"roll": 0.029823324
},
"mouthStatus": "MOUTH_OPEN",
"eyeStatus": {
"leftEye": "NO_GLASSES_EYE_OPEN",
"rightEye": "NO_GLASSES_EYE_OPEN"
},
"quality": 0.9417827
},
            * */

            console.log(info);
        })

        return;
    };

    // var analyze_init

    /*REMOVE DUPLICATE */
    var removeDuplicates = function (json_all) {

        var arr_id = [],
            arr_name = [],
            collection = [];

        for (index in json_all) {

            var person = json_all[index];
            var info = person.tags;

            /*CHECK DUPLICATE*/
            if ($.inArray(info.idPerson, arr_id) == -1 && $.inArray(info.name, arr_name) == -1) {

                arr_id.push(info.idPerson);
                arr_name.push(info.name);

                collection.push(person);

                // console.log(arr);
            }
        }
        ;
        return collection;
    };

    var getMonitor = function () {


        var url = 'control/monitor.php';

        var param = {

            action: "getHighQuality"
        }

        $.getJSON(url, param, function (data) {

            console.log("Top 5:");
            console.log(data);

            initMonitor(data);
        });

        return;
    }

    var initMonitor = function (data) {

        $(config.area + "-2").html("");

        var count = 0;

        for (i in data) {

            if (count == 5) return;

            var person = data[i];
            var photoID = person.photoID;
            var name = person.name;
            var quality = Math.round(person.quality*0.8);
            var datetime = person.datetime;
            // var capture = person.capture;

            /*GET analyze*/
            var analyze = person.face_analyze;
            var gender = analyze.gender;


            /*IMAGE*/
            var image = $("<img class=\"\">").attr({
                "src": config.host + '/photos/' + photoID + '/data',
                "class": "people"

            }).prop('outerHTML');


            if (gender == "FEMALE") {

                count++;

                var text = '  <div class="row text-card" data-rel="top_' + photoID + '">\n' +
                    '                            <div class="col-xl-8">\n' +
                    '                                <div class="title">' + name + '</div>\n' +
                    // '                                <div class="description">' + info.description + '</div>\n' +
                    '                                <p>' + datetime + '</p>\n' +
                    '                                <div class="progress">\n' +
                    '                                    <div class="progress-bar progress-bar-striped progress-bar-animated nice" role="progressbar" style="width: ' + quality + '%" aria-valuenow="' + quality + '" aria-valuemin="0" aria-valuemax="100">Nice ' + quality + '</div>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                            <div class="col-xl-4">\n' + image;
                /*FOR QUEEN*/
                if (count == 1)
                        text += '<img src="dist/image/queen.png" class="crown-right">';

                    text +='                            </div>\n' +
                    '                        </div>';

                $(config.area + "-2").append(text);
            }

        }

        return;
    }

    /*ACK*/
    //messageType=="MESSAGE_TYPE_ALERT"
    var confirm = function (data) {

        // return;

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
    this.setflag = setFlag;

    this.getMonitor = getMonitor;

    /*PRIVATE VAR*/
    this.config = config;
}