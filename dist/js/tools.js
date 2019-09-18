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

        const map = new Map();
        let collection = [];

        for (let index in json_all) {

            let json = json_all[index];
            if (!map.has(json.photoID) && !map.has(json.tags.name)){
                map.set(json.photoID, true);
                map.set(json.tags.name, true);
                collection.push(json);
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