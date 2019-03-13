var Tools = function () {

    function formatDate(date) {

        var hours = date.getHours();
        var minutes = date.getMinutes();

        var raw_time = {
            hour: hours,
            minute: minutes
        };

        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;

        return {
            fulltime: date.getMonth() + 1 + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime,
            time: strTime,
            raw_time: raw_time

        }

    }

    function time_diff(start, end) {

        var startDate = new Date(0, 0, 0, start.hour, start.minute, 0);
        var endDate = new Date(0, 0, 0, end.hour, end.minute, 0);
        var diff = endDate.getTime() - startDate.getTime();
        var hours = Math.floor(diff / 1000 / 60 / 60);
        diff -= hours * 1000 * 60 * 60;
        var minutes = Math.floor(diff / 1000 / 60);

        return {

            duration: (hours < 9 ? "0" : "") + hours + ":" + (minutes < 9 ? "0" : "") + minutes,
            hour: hours
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
            if ($.inArray(photoID, photoIDs) == -1) {

                photoIDs.push(photoID);

                collection.push(person);

                // console.log(arr);
            }
        }
        ;
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