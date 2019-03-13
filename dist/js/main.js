var myface;
var myanalyze;
var tools;
// var getMonitor;
$(function () {

    tools = new Tools();
    myanalyze = new FaceAnalyze();
    myface = new FaceID();

    setTimeout(myface.getsub());

    // myface.getMonitor();
    // getMonitor = setInterval(myface.getMonitor, 10000);

});
