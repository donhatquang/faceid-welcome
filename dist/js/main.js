var myface;
var tools;
// var getMonitor;
$(function () {

    tools = new Tools();
    myface = new FaceID();

    setTimeout(myface.getsub());

    // myface.getMonitor();
    // getMonitor = setInterval(myface.getMonitor, 10000);

});
