var Tools = function () {

    function formatDate(date) {

        var hours = date.getHours();
        var minutes = date.getMinutes();

        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;

        return {
            fulltime: date.getMonth() + 1 + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime,
            time: strTime,
        }

    }

    function time_diff(start, end) {

        var end = end || new Date();


        start = typeof start == "object" ? start : new Date(start);
        end = typeof start == "object" ? end : new Date(end);

        var timeDiff = Math.abs(end.getTime() - start.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var diffHours = (timeDiff / (1000 * 3600)).toFixed(2);
        var minute = Math.round(timeDiff / (1000 * 60), 2);

        return {
            days: diffDays,
            hours: diffHours,
            minute: minute,
            second: Math.round(timeDiff / 1000)
        }

    }

    /*REMOVE DUPLICATE */
    var removeDuplicates = function (json_all) {

        var photoIDs = [],
            // arr_name = [],
            collection = [];

        for (index in json_all) {

            var person = json_all[index];
            var photoID = person.photoID;
            // var info = person.tags;

            /*CHECK DUPLICATE*/
            var index = $.inArray(photoID, photoIDs);

            if (index == -1) {

                photoIDs.push(photoID);

                collection.push(person);
            }

            /*COMPARE TIME IF EXIST IN TEMP COLLECTION*/
            else {

                var person_before = collection[index];

                var capturedTime_before = person_before.capturedTime;
                var capturedTime_after = person.capturedTime;

                /*TIME AFTER GREATER THAN BEFORE*/
                if (new Date(capturedTime_after) > new Date(capturedTime_before)) {

                    collection[index] = person;
                    // console.log("Index: " + index + " time: " + time_diff(capturedTime_before,capturedTime_after).second + "s");
                }
            }
        }



        return collection;
    };

    var radians_to_degrees = function (radians) {
        var pi = Math.PI;
        return radians * (180 / pi);
    }

    return {

        radians_to_degrees: radians_to_degrees,
        formatDate: formatDate,
        removeDuplicates: removeDuplicates,
        time_diff: time_diff
    };


}