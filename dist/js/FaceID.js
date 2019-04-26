var FaceID = function () {

    var config = {
        max_timeout: 3000 * 1,
        ack_max_timeout: 1000 * 1,
        host: "http://192.168.21.108:8080/v4",
        area: '.faceid-list',
        flag: true, /*true is init ready*/

        ack: true,
        realtime: true
    };
    // var subscribe = subscribe_config;

    var request_param = request_param_global;

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

            // console.log(diff);

            /*REAL TIME DISPLAY*/
            if (config.realtime) {

                // console.log(timediff);

                if (timediff.second <= 30) {

                    console.log("Duration: ");
                    console.log(timediff);

                    $(area).append(text);

                    console.log("History: " + name + " - " + tools.formatDate(capturedTime).fulltime +
                        " - Minute: " + timediff.minute + " - Second: " + timediff.second);
                }

                else {

                    // console.log("History: " + name + " - " + tools.formatDate(capturedTime).fulltime + " - Minute: " + timediff.minute + " Ago");

                }

            }
            else {

                $(area).append(text);
            }


            /*ANALYZE*/
            myanalyze.analyze(person);

        }
        return;
    };

    var getsub = function () {

        var url = 'control/getsub.php';
        var flag = config.flag;

        $.getJSON(url, request_param, function (data) {

            //--------------

            /*REMOVE DUPLICATE*/
            var new_data = tools.removeDuplicates(data);

            console.log("Flag: " + flag);

            //$(".people").length == 0
            //data.length != 0

            if (flag == true || (flag == false && $(".people").length == 0)) {

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


    /*ACK*/
    //messageType=="MESSAGE_TYPE_ALERT"
    var confirm = function (data) {


        if (config.ack == false) return;

        // return;
        // console.log("ACK Message: " + config.ack);


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

            console.log("ACK successful! ");
            console.log(data);
        });

        // console.log(ackid);

        return;
    };

    /*DEFINE*/
    /*PUBLIC METHOD*/
    this.getsub = getsub;

    /*PRIVATE VAR*/
    this.config = config;
}