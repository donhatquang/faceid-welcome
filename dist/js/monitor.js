var Monitor = function () {

    var getMonitor = function () {


        var url = '/control/monitor.php';

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
            var quality = Math.round(person.quality * 0.8);
            var datetime = person.datetime;
            var capture = person.capture;

            /*GET analyze*/
            var analyze = person.face_analyze;
            var gender = analyze.gender;


            /*IMAGE*/
            var image = $("<img class=\"\">").attr({
                "src": config.host + '/photos/' + capture + '/data',
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

                text += '                            </div>\n' +
                    '                        </div>';

                $(config.area + "-2").append(text);
            }

        }

        return;
    }


    this.getMonitor = getMonitor;
    this.initMonitor = initMonitor;
}