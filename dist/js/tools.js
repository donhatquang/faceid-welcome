var Tools = function () {

    function formatDate(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;

        return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime;
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

    var radians_to_degrees = function (radians)
    {
        var pi = Math.PI;
        return radians * (180/pi);
    }

    return {

        radians_to_degrees: radians_to_degrees,
        formatDate: formatDate,
        removeDuplicates: removeDuplicates
    };


}