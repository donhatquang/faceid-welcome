var FaceAnalyze = function () {

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


        $.getJSON(url, param, function (data) {

            analyze_init(data, photoID);
        })

        return;
    };

    var analyze_init = function (data, photoID) {

        var data = data.faces[0];
        var attr = data.attributes;
        var pose = attr.pose;

        var age = Math.round(attr.age);
        var gender = attr.gender;
        var quality = Math.round(attr.quality * 100 * 0.8) + "%";

        var image_gender = $("<img class=\"\">").attr({

            "class": "gender-left",
            "src": 'dist/image/' + gender.toLocaleLowerCase() + ".png"

        }).prop('outerHTML');

        /*INIT*/
        var obj = $("div[data-rel='" + photoID + "']");

        obj.find(".title").append(" (Age: " + age + ")");
        obj.find(".progress-bar").css({width: quality}).html(quality);

        /*CHECK EYE*/
        /*

        var eyeStatus = attr.eyeStatus;

        var left_eye = checkEye(eyeStatus.leftEye);
        var right_eye = checkEye(eyeStatus.rightEye);

        obj.find(".description").append('<p>' +
            'Mắt trái: ' + left_eye.glass +
            ' - Mắt phải: ' + right_eye.glass +
            '</p>');
*/
        /*ADD GENDER*/
        /*ADD CUBE*/
        obj.find(".people").after(image_gender).after(draw_cube(pose));

        return;
    }

    /*CHECK EYE & GLASSES*/
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

        var pitch = Math.round(tools.radians_to_degrees(pose.pitch)) * (1), //1 X
            yaw = Math.round(tools.radians_to_degrees(pose.yaw)) * (-1), //2 Y
            roll = Math.round(tools.radians_to_degrees(pose.roll)) * (1); //3 Z


        var text = '<div class="cube" style="transform: rotateX(' + pitch + 'deg) rotateY(' + yaw + 'deg) rotateZ(' + roll + 'deg);">\n' +
            '    <div class="cube-side cube-front"></div>\n' +
            '    <div class="cube-side cube-back"></div>\n' +
            '    <div class="cube-side cube-left"></div>\n' +
            '    <div class="cube-side cube-right"></div>\n' +
            '    <div class="cube-side cube-top"></div>\n' +
            '    <div class="cube-side cube-bottom"></div>\n' +
            '</div>';

        return text;
    }

    /*public*/
    return {

        analyze: analyze
    }
}