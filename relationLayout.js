/**
 * Created by CYa on 2015/5/11.
 */
$(document).ready(function () {
    var g = {
        nodes: [],
        edges: []
    };

    s = new sigma({
        graph: g,
        container: 'relationGraph',
        renderer: {
            container: document.getElementById('relationGraph'),
            type: 'canvas'
        }
    });

    sigma.parsers.json('rt10noun.json',
            s,
            function () {
                s.settings({
                    defaultLabelSize: 18,
                    font: "微軟正黑體",
                    labelHoverBGColor: 'node',
                    defaultLabelHoverColor: '#000',
                    labelHoverShadow: 'node',
                    labelThreshold: 3
                });
                s.refresh();
                /*
                 s.bind('clickNode', function (e) {
                 var nodeId = e.data.node.id,
                 toKeep = s.graph.neighbors(nodeId);
                 toKeep[nodeId] = e.data.node;
                 
                 s.graph.nodes().forEach(function (n) {
                 if (toKeep[n.id])
                 n.color = n.originalColor;
                 else
                 n.color = '#555';
                 });
                 
                 s.graph.edges().forEach(function (e) {
                 if (toKeep[e.source] && toKeep[e.target])
                 e.color = e.originalColor;
                 else
                 e.color = '#333';
                 });
                 
                 // Since the data has been modified, we need to
                 // call the refresh method to make the colors
                 // update effective.
                 s.refresh();
                 });
                 */
            }
    );


    db = new sigma.plugins.neighborhoods();

    db.load('rt10noun.json', function () {
        // Out function to initialize sigma on a new neighborhood:
        function refreshGraph(centerNodeId) {
            s.camera.goTo({
                x: 0,
                y: 0,
                angle: 0,
                ratio: 1
            });
            s.settings({
                labelThreshold: 1
            });
            s.graph.clear();

            s.graph.read(db.neighborhood(centerNodeId));

            s.refresh();

        }

        // Let's now bind this new function to the "clickNode" event:
        s.bind('clickNode', function (event) {
            var nodeId = event.data.node.id;
            refreshGraph(nodeId);
            //var nodeLabel = event.data.node.label;
        });
    });


    $("button[name='restart-camera']").click(function () {
        s.camera.goTo({
            x: 0,
            y: 0,
            angle: 0,
            ratio: 1
        });
    });

    $("button[name='reset-graph']").click(function () {
        sigma.parsers.json('rt10noun.json',
                s,
                function () {
                    s.settings({
                        defaultLabelSize: 18,
                        font: "微軟正黑體",
                        labelHoverBGColor: 'node',
                        defaultLabelHoverColor: '#000',
                        labelHoverShadow: 'node',
                        labelThreshold: 3
                    });
                    s.camera.goTo({
                        x: 0,
                        y: 0,
                        angle: 0,
                        ratio: 1
                    });
                    s.refresh();
                }
        );
    });


    /*var listener = sigma.layouts.fruchtermanReingold.configure(s);
     // Bind all events:
     listener.bind('start stop interpolate', function(event) {
     console.log(event.type);
     });
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
     }, true);*/
});