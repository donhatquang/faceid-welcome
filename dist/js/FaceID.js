var FaceID = function () {

    var config = {
        max_timeout: 2000 * 1,
        ack_max_timeout: 1000 * 1,
        host: "http://192.168.51.8:8080/v4",
        area: '.faceid-list',
        flag: true, /*true is init ready*/

        ack: true,
        realtime: true
    };

    var Hiface_param = {};
    // var subscribe = subscribe_config;

    /*FACEID PERSON*/
    var Hiface_collection = [];


    var request_param = request_param_global;

    // var flag = false;

    /*INIT UI*/
    var subinit = function (data) {

        /*REMOVE*/
        $(config.area).html("");

        // console.log(data);
        for (i in data) {

            /*DISPLAY AREA DEFINE*/
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
            var capturedTime = new Date(person.capturedTime);

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
                '                                <p>' + tools.formatDate(capturedTime).fulltime + '</p>\n' + //+ ' (Score: ' + score
                '                                <div class="progress">\n' +
                '                                    <div class="progress-bar progress-bar-striped progress-bar-animated nice" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="col-xl-4">\n' + image +


                /*GENDER*/

                '                            </div>\n' +
                '                        </div>';

            /*INIT*/

            var timediff = tools.time_diff(capturedTime);

            // console.log(timediff);

            /*REAL TIME DISPLAY*/
            if (config.realtime && timediff.second <= 30) {

                $(area).append(text);

                // console.log("History: " + name + " - " + tools.formatDate(capturedTime).fulltime + " - Minute: " + timediff.minute + " - Second: " + timediff.second);
                console.log("History: " + timediff.second + "s ago");


            }
            else {

                $(area).append(text);
            }


            /*ANALYZE*/
            myanalyze.analyze(person);

        }
        return;
    };


    /*COMPARE HISTORY COLLECTION*/
    var compareCollection = function (data) {

        for (i in data) {

            let after_person = data[i];

            /*FIND OBJECT PERSON IN COLLECTION BY PHOTOID*/
            let before_person = Hiface_collection.find(person => person.photoID === after_person.photoID);

            /*CHECK TIME*/
            if (before_person !== undefined) {

                /*TIME AFTER GREATER THAN BEFORE*/
                if (new Date(before_person.capturedTime) > new Date(after_person.capturedTime)) {

                    data[i] = before_person;

                    console.log(before_person);

                    // console.log("Index: " + i + " time: " + tools.time_diff(after_person.capturedTime, before_person.capturedTime).second + "s");
                }
            }

            console.log(data[i].tags.name + " - Thoi gian: " + data[i].capturedTimeFormat.time);
        }

        return data;
    }

    /*IMPORTANT - GET SUBCRIBE*/
    var getsub = function () {

        var url = 'control/getsub.php';
        var flag = config.flag;

        $.getJSON(url, request_param, function (data) {

            //--------------

            /*REMOVE DUPLICATE - GET RECENTY IN QUEUE*/
            var new_data = tools.removeDuplicates(data);

            /*COMPARE WITH COLLECTION*/
            new_data = compareCollection(new_data);

            //$(".people").length == 0
            //data.length != 0

            if (flag == true || (flag == false && $(".people").length == 0)) {

                /*INIT DATA*/
                subinit(new_data);

                /*ADD TO COLLECTION*/
                Hiface_collection = new_data;
                myface.collection = new_data;

                // console.log(Hiface_collection);
            }


            /*CONFIRM MESSAGE ACK*/
            if (new_data.length != 0) {

                setTimeout(confirm(data), config.ack_max_timeout);
            }

            // console.log("Flag: " + flag);
            console.log("---------------------------------\n");


            /*REQUEST AGAIN*/
            setTimeout(getsub, config.max_timeout);
            // setTimeout(getMonitor, config.max_timeout);

        })
            .fail(
                function (jqxhr, textStatus, error) {

                    var err = textStatus + ", " + error;
                    console.log("Request Failed: " + err);

                    setTimeout(getsub, config.max_timeout);

                    return;
                });

        /*RETURN GET SUB FUNC*/
        return;
    }

    /*ACK*/
    //messageType=="MESSAGE_TYPE_ALERT"
    var confirm = function (data) {

        if (config.ack == false) return;


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

        // console.log(ack_str);

        /*POST ACK*/
        $.post(url, {

            "ackid": ack_str,
            "subscribe": request_param.subscribe
        }, function (data) {

            // console.log("ACK successful! ");
            // console.log(data);
        });


        return;
    };

    /*GET VIDEO LIST*/
    var getList = function (act) {

        var url = "control/getlist.php";
        var param = {
            act: act
        }

        $.getJSON(url, param, function (data) {

            $.extend(true, Hiface_param, data);

            console.log(Hiface_param);

            /*START GET SUB*/
            myface.getsub();

        })

        return;
    }

    /*CONSTRUCST*/

    var __constructor = function () {

        getList("videos");
        console.log("Hello HiFace");
    }

    /*DEFINE*/
    /*PUBLIC METHOD*/
    this.getsub = getsub;

    this.constructor = __constructor();
    // this.

    /*PRIVATE VAR*/
    this.config = config;
    this.param = Hiface_param;
    this.collection = Hiface_collection;
}
