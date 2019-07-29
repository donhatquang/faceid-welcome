var myface;
var myanalyze;
var pubsub;
var tools;

// var getMonitor;
$(function () {

    tools = new Tools();
    //myanalyze = new FaceAnalyze();

    myface = new FaceID();
    let config = myface.config;
    pubsub = new Pubsub(config);





    // myface.getMonitor();
    // getMonitor = setInterval(myface.getMonitor, 10000);

});
