var FaceID = function () {

    var config = {
        max_timeout: 1000 * 10,
        host: "http://192.168.51.12:8080/v4",
        area: '.faceid-list'
    };
    var subscribe = subscribe_config;

    var flag = false;

    /*INIT UI*/
    var subinit = function (data) {

        $(config.area).html("");

        // console.log(data);
        for (i in data) {

            if (i>=10) return;

            if (i<5) {
                var area = config.area+"-1";
            }
            else if (i>=5) {
                area = config.area+"-2";
            }

            //-------------
            var person = data[i];

            var info = person.tags;

            var photoID = person.photoID;
            var capture = person.capture;

            var name = info.name;
            var date = new Date(person.capturedTime);
            var idPerson = info.idPerson;



            var image = $("<img class=\"\">").attr({
                "src": config.host + '/photos/' + capture + '/data',
                "class": "people"

            }).prop('outerHTML');

            var datetime = date.getDate() + "/" + date.getMonth() + "-" + date.getHours() + ":" + date.getMinutes();

            var text = '  <div class="row text-card" id="' + idPerson + '">\n' +
                '                            <div class="col-xl-8">\n' +
                '                                <div class="title">' + name + '</div>\n' +
                '                                <p>' + info.description + '</p>\n' +
                '                                <p>' + datetime + '</p>\n' +
                '                                <div class="progress">\n' +
                '                                    <div class="progress-bar progress-bar-striped progress-bar-animated nice" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="col-xl-4">\n' + image +

                '                            </div>\n' +
                '                        </div>';


            /* var text = "<div id='" + idPerson + "'>";
             text += "<div class='name'>" + name + "</div>";
             text += "<div class='description'>" + info.description + "(" + date.getDate() + "/" + date.getMonth() + "-" + date.getHours() + ":" + date.getMinutes() + ")</div>";
             // text += "<div class='datetime'>" + "</div>";
             text += "</div>";
 */
            $(area).append(text);

            /*ANALYZE*/
            analyze(photoID, info);

        }
        return;
    };

    /*GET SUBSCRIBE*/
    var setFlag = function (status) {

        flag = status;
    };

    var getsub = function () {

        var url = 'control/getsub.php';

        $.getJSON(url, subscribe, function (data) {

            console.log("Flag: "+flag);

            if (flag == true) return;

            //--------------

            /*REMOVE DUPLICATE*/
            var new_data = removeDuplicates(data);

            // console.log(new_data);

            /*INIT*/
            subinit(new_data);

            /*CONFIRM MESSAGE*/
            setTimeout(confirm(data), 1000);
        })

        /*REQUEST AGAIN*/
        setTimeout(getsub, config.max_timeout);

        return;
    };

    /*ANALYZE*/
    var analyze = function (photoID, info) {

        // return;

        var url = 'control/analyze.php';
        var idPerson = info.idPerson, name = info.name;
        var param = {

            photoID: photoID
        }

        /**/
        if (idPerson == "" || idPerson == 0) return;

        $.getJSON(url, param, function (data) {


            var data = data.faces[0];
            var attr = data.attributes;

            var age = Math.round(attr.age);
            var quality = Math.round(attr.quality*100*0.7)+"%";

            $("#"+idPerson).find(".title").append(" (Age: "+age+")");
            $("#"+idPerson).find(".progress-bar").css({width: quality}).html(quality);

            console.log(idPerson + " " +name);
            // console.log();
            console.log("blur:"+ attr.blurriness);

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

    /*ACK*/
    var confirm = function (data) {

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
    this.subinit = subinit;
    this.getsub = getsub;
    this.analyze = analyze;
    this.setflag = setFlag;

    this.flag = flag;
    this.config = config;
}