<!DOCTYPE html>
<html>
    <head>
        <title>thesisproj</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-2.1.3.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <script src="dateslider/jquery.mousewheel.min.js"></script>
        <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="bootstrap-3.3.1-dist/vertical-tabs/bootstrap.vertical-tabs.min.css">
        <script src="bootstrap-3.3.1-dist/dist/js/bootstrap.min.js"></script>
        <script src="vis/dist/vis.js"></script>
        <link href="vis/dist/vis.css" rel="stylesheet" type="text/css" />
        <script src="sigma_js/build/sigma.min.js"></script>
        <script src="sigma_js/build/plugins/sigma.parsers.json.min.js"></script>
        <script src="sigma_js/build/plugins/sigma.layout.forceAtlas2.min.js"></script>
        <script src="sigma_js/build/plugins/sigma.layout.fruchtermanReingold.js"></script>
        <script src="sigma_js/build/plugins/sigma.plugins.animate.min.js"></script>
        <script src="sigma_js/build/plugins/sigma.plugins.neighborhoods.js"></script>
        <script src="dateslider/jQDateRangeSlider-min.js"></script>
        <script src="relationLayout.js"></script>
        <script src="mentionLayout.js"></script>
        <script src="rangeSlider.js"></script>
        <link rel="stylesheet" href="css/iThing.css" type="text/css" />

        <style type="text/css">
            body, html {
                font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
            }
            #relationGraph {
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                width: 1000px;
                height: 600px;
            }
            #mentionGraph {
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                width: 1000px;
                height: 600px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-3">
                    <ul class="nav nav-tabs tabs-left">
                        <li class="active"><a href="#peoplerelation" data-toggle="tab">人物關係圖</a></li>
                        <li><a href="#mention" data-toggle="tab">Mention graph</a></li>
                        <li><a href="#messages" data-toggle="tab">Messages</a></li>
                        <li><a href="#settings" data-toggle="tab">Settings</a></li>
                    </ul>
                </div>
                <div class="col-xs-9">
                    <div class="tab-content">
                        <div class="tab-pane active" id="peoplerelation">
                            <h4>人物關係圖</h4>
                            <div class="col-md-11">
                                <div id="relationGraph"></div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" name="restart-camera" id="restart-camera" class="btn btn-default">Reset Camera</button><br>
                                <button type="button" name="reset-graph" id="reset-graph" class="btn btn-default">Reset Graph</button><br>
<!--                                <button type="button" name="toggle-layout" id="toggle-layout" class="btn btn-default">Start Layout</button>-->
                            </div>
                        </div>
                        <div class="tab-pane" id="mention">
                            <h4>Social mention graph</h4>
                            <div class="col-md-11">
                                <div id="mentionGraph"></div>
                            </div>
                            <div class="col-md-1">
                                
                            </div>
                        </div>
                        <div class="tab-pane" id="messages">Messages Tab.</div>
                        <div class="tab-pane" id="settings">Settings Tab.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="rangeSlider"></div><br>
                <div class="col-md-1">
                    <button type="button" name="update" class="btn btn-default">Update</button>
                </div>
                <div class="col-md-11"></div>
            </div>

            <div class="row">
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
                            if (data.rsStatus) {
                                buildTweetsTimeline(data.rsAns);
                            } else {
                                showMessage('danger', data.rsAns);
                            }
                        });
                    };

                    var buildTweetsTimeline = function (aryLists) {
                        var options = {
                            height: '500px',
                            min: new Date(2014, 8, 25), // lower limit of visible range
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