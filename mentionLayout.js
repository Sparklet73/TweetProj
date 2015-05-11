/**
 * Created by CYa on 2015/5/11.
 */
$(document).ready(function () {
    var fa2setting = {
        barnesHutOptimize: false,
        scalingRatio: 10,
        adjustSizes: true,
        outboundAttractionDistribution: false
    };

    var g = {
        nodes: [],
        edges: []
    };

    s = new sigma({
        graph: g,
        container: 'mentionGraph',
        renderer: {
            container: document.getElementById('mentionGraph'),
            type: 'canvas'
        }
        /*settings: {
         minNodeSize: 10,
         maxNodeSize: 100
         }*/
    });
    sigma.parsers.json('rt10noun.json',
        s,
        function () {
            s.refresh();
        }
    );
    /*var listener = sigma.layouts.fruchtermanReingold.configure(s);
     // Bind all events:
     listener.bind('start stop interpolate', function(event) {
     console.log(event.type);
     });*/
    var isRunning = false;
    document.getElementById('toggle-layout').addEventListener('click', function () {
        if (isRunning) {
            isRunning = false;
            s.stopForceAtlas2();
            document.getElementById('toggle-layout').childNodes[0].nodeValue = 'Start Layout';
        } else {
            isRunning = true;
            s.startForceAtlas2();
            //sigma.layouts.fruchtermanReingold.start(s);
            document.getElementById('toggle-layout').childNodes[0].nodeValue = 'Stop Layout';
        }
    }, true);
});