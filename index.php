<!DOCTYPE html>
<html lang="zh">
<head>
    <title>thesisproj</title>
    <meta charset="utf-8">
    <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="jquery/jquery-2.1.3.min.js"></script>
    <script src="vis/dist/vis.js"></script>
    <link href="vis/dist/vis.css" rel="stylesheet" type="text/css" />
    <script src="sigma.js/build/sigma.min.js"></script>
    <script src="sigma.js/build/plugins/sigma.parsers.gexf.min.js"></script>
    <style type="text/css">
        body, html {
            font-family: sans-serif;
        }
        .col-md-8 {
            width: 700px;
            height: 500px;
        }
        #graph-container {
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            position: absolute;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8" id="nn">
            <h4>Tweets Network</h4>
            <div id="graph-container"></div>
        </div>
        <script type="text/javascript">
            sigma.parsers.gexf('arctic.gexf', {
                container: 'graph-container'
            });
        </script>
        <div class="col-md-4">
            <h4>Content 2</h4>
        </div>
    </div>
    <div class="row" id="timeline">
        <h4>Timeline</h4>
        <div id="visualization"></div>
        <script type="text/javascript">
            var container = document.getElementById('visualization');
            var showTimeline = function () {
                $.ajaxSetup({
                    cache: false
                });

                var jqxhr = $.getJSON('ajax_rt100tweets.php');

                jqxhr.done(function (data) {
                    if(data.rsStatus) {
                        buildTweetsTimeline(data.rsAns);
                    } else {
                        showMessage('danger', data.rsAns);
                    }
                });
            };

            var buildTweetsTimeline = function(aryLists) {
                var options = {
                    height: '400px',
                    min: new Date(2014, 8, 25),               // lower limit of visible range
                    max: new Date(2014, 12, 17)                // upper limit of visible range
                };
                var items = new vis.DataSet(aryLists);
                var timeline = new vis.Timeline(container, items, options);
                //timeline.setWindow('2014-09-20','2014-09-30');
            };

            showTimeline();

        </script>
    </div>
</div>
</body>
</html>