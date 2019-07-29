var FaceID = function () {

    var config = {
        max_timeout: 1000 * 1,
        ack_max_timeout: 1000 * 1,

        host: hiface_host,
        area: '.faceid-list',
        flag: true, /*true is init ready*/

        waiting_time: 3 * 60,

        ack: false,
        realtime: false,
        analyze: false
    };

    var Hiface_param = {};
    // var subscribe = subscribe_config;

    /*FACEID PERSON*/
    var Hiface_collection = [];


    /*COMPARE HISTORY COLLECTION*/
    this.compareCollection = function (data) {

        for (let i in data) {

            let after_person = data[i];
            /*FIND OBJECT PERSON IN COLLECTION BY PHOTOID*/
            let before_person = Hiface_collection.find(
                person => (
                    person.photoID === after_person.photoID
                    //    && person.tags.name === after_person.tags.name

                )
            );

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
    };

    /*ACK*/
    //messageType=="MESSAGE_TYPE_ALERT"


    /*GET VIDEO LIST*/
    var getList = function (act) {

        var url = "control/getlist.php";
        var param = {
            act: act
        };

        $.getJSON(url, param, function (data) {

            $.extend(true, Hiface_param, data);

            console.log(Hiface_param);

            /*START GET SUB*/
            pubsub.getsub();

        });

        return;
    };

    /*CONSTRUCST*/

    var __constructor = function () {

        getList("videos");
        console.log("Hello HiFace");
    };

    /*DEFINE*/
    /*PUBLIC METHOD*/

    this.constructor = __constructor();
    // this.

    /*PRIVATE VAR*/
    this.config = config;
    this.param = Hiface_param;
    this.collection = Hiface_collection;
};
