<!DOCTYPE html>
<html lang="zh">
<head>
    <title>thesisproj</title>
    <meta charset="utf-8">
    <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="jquery/jquery-2.1.3.min.js"></script>
    <script src="jquery/jquery-ui.min.js"></script>
    <script src="vis/dist/vis.js"></script>
    <link href="vis/dist/vis.css" rel="stylesheet" type="text/css" />
    <script src="sigma.js/build/sigma.min.js"></script>
    <script src="sigma.js/build/plugins/sigma.parsers.json.min.js"></script>
    <script src="dateslider/jQDateRangeSlider-min.js"></script>
    <script src="dateslider/jquery.mousewheel.min.js"></script>
    <link rel="stylesheet" href="css/iThing.css" type="text/css" />

    <style type="text/css">
        body, html {
            font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
        }
        .col-md-8 {
            width: 700px;
            height: 500px;
        }
        #keywordGraph {
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

            <h2>Tweets Network</h2>
            <div id="keywordGraph"></div>
        <script type="text/javascript">
            var sigmacontainer = document.getElementById('keywordGraph');
            var showKeywordGraph = function () {
                $.ajaxSetup({
                   cache: false
                });

                var jqxhr = $.getJSON('ajax_showkwgraph.php');

                jqxhr.done(function (data) {
                    if(data.rsStat) {
                        buildKeywordGraph(data.rsGraph);
                    } else {
                        showMessage('danger', data.rsGraph);
                    }
                });
            };

            var buildKeywordGraph = function(aryLists) {
                sigma.parsers.json(aryLists, {
                    container: 'keywordGraph'
                });
            };

            showKeywordGraph();

        </script>
    </div>
    <div class="row">
        <div id="rangeSlider"></div><br>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-5">
                <button type="submit" name="update" class="btn btn-default">Update</button>
            </div>
        </div>
        <script type="text/javascript">
            $("#rangeSlider").dateRangeSlider({
                defaultValues:{
                    min: new Date(2014, 8, 25),
                    max: new Date(2014, 9, 5)
                },
                range:{
                    min: {days: 1}
                },
                bounds: {
                    min: new Date(2014, 7, 25),
                    max: new Date(2014, 11, 16)
                }});
            $(":button").click(function () {
                var dateValues = $("#rangeSlider").dateRangeSlider("values");
                startmon = dateValues.min.getMonth()+1;
                startday = dateValues.min.getFullYear() + "-" + startmon + "-" + dateValues.min.getDate() ;
                endmon = dateValues.max.getMonth()+1;
                endday = dateValues.max.getFullYear() + "-" + endmon + "-" + dateValues.max.getDate() ;
                window.alert(startday + " to " + endday);
            });
        </script>
    </div>

    <div class="row" id="timeline">
        <h3>Timeline</h3>
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