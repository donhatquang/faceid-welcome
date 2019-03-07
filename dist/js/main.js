var myface;
var getMonitor;
$(function () {

    myface = new FaceID();

    setTimeout(myface.getsub());

    myface.getMonitor();
    getMonitor = setInterval(myface.getMonitor, 10000);

});
