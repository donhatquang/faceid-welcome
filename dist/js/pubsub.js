var Pubsub = function (myconfig) {

    // var config = myface.config;
    var config;
    var request_param = request_param_global;


    /*INIT UI*/
    this.subinit = function (data) {
        console.log(data);

        /*REMOVE*/
        $(config.area).html("");
        var area = config.area;

        // console.log(data);

        /*DISPLAY AREA DEFINE*/
        /*if (i >= 10) return;

        if (i < 5) {
            var area = config.area + "-1";
        }
        else if (i >= 5) {
            area = config.area + "-2";
        }*/

        //-------------
        var person = data[0];

        var info = person.tags;

        var photoID = person.photoID;
        var capture = person.capture;
        var score = Math.round(person.score);
        /*THRESH-HOLD*/

        var name = info.name;
        var capturedTime = new Date(person.capturedTime);

        // var idPerson = info.idPerson;

        /*IMAGE*/
        var image = $("<img />").attr({

            "class": "image-cropper",
            "src": config.host + '/photos/' + capture + '/data'

        }).prop('outerHTML');

        /*CHECK EXIST*/
        // if ($("div[data-rel='"+photoID+"']").length == 0) {}

        var emotion = `
            <div class="col-xl-2 emotion">
                <img class="" src="dist/img/emoticon/unknown.png" alt="">
            </div>`;

        /*EMOTICON*/
        let url = "http://192.168.51.12:8123/api/agender/";
        let param = {
            photoData: "http://14.160.67.114:8080/v4/photos/" + capture + "/data"
        };
        $.ajax({
            type: "POST",
            url: url,
            data: param,
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.status === "ok") {
                    emotion = `
                        <div class="col-xl-2 emotion">
                            <img class="" src="dist/img/emoticon/${data.emotion}.png" alt="">
                        </div>`;
                }
            }
        });

        let detail =
            '                            <div class="col-xl-8 info-card">\n' +
            '                                <div class="title">' + name + '</div>\n' +
            '                                <div class="description">' + info.description + '</div>\n' +
            '                                <p>' + tools.formatDate(capturedTime).fulltime + '</p>\n' + //+ ' (Score: ' + score
            /*'                                <div class="progress">\n' +
            '                                    <div class="progress-bar progress-bar-striped progress-bar-animated nice" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">20%</div>\n' +
            '                                </div>\n' +
            */'                            ' +
            '</div>\n';

        var text = ` <div class="row text-card" data-rel="${photoID}">
                <div class="col-xl-2">
                    ${image}
                </div>
                ${detail}
                ${emotion}
 
</div>                
`;

        /*INIT*/

        var timediff = tools.time_diff(capturedTime);

        console.log(timediff);

        /*REAL TIME DISPLAY*/
        if (config.realtime) {

            if (timediff.second <= config.waiting_time) {

                $(area).append(text);
            }


            // console.log("History: " + name + " - " + tools.formatDate(capturedTime).fulltime + " - Minute: " + timediff.minute + " - Second: " + timediff.second);
            console.log("History: " + timediff.second + "s ago");

        } else {

            $(area).append(text);
        }


        /*ANALYZE*/
        try {

            if (config.analyze)
                myanalyze.analyze(person);

        } catch (e) {
            console.log(e);
        }

        return;
    };

    /*IMPORTANT - GET SUBCRIBE*/
    var getsub = function () {

        var url = 'control/getsub.php';
        var flag = config.flag;

        $.getJSON(url, request_param, function (data) {

            //--------------

            /*REMOVE DUPLICATE - GET RECENTY IN QUEUE*/
            var new_data = tools.removeDuplicates(data);

            /*COMPARE WITH COLLECTION*/
            new_data = myface.compareCollection(new_data);

            //$(".people").length == 0
            //data.length != 0

            if (flag == true || (flag == false && $(".people").length == 0)) {

                /*INIT DATA*/
                pubsub.subinit(new_data);

                /*ADD TO COLLECTION*/
                // Hiface_collection = new_data;
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
            // setTimeout(getsub, config.max_timeout);
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

        };

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

    var __constructor = function (myconfig) {

        console.log("PUBSUB SERVICE");
        config = myconfig;
    }

    this.getsub = getsub;

    this.constructor = __constructor(myconfig);

}