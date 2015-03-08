<!DOCTYPE HTML>
/**
 * User: IMkoba
 * Date: 2015/1/29
 * Time: 下午 2:01
 */
 
<html>
<head>
  <title>Timeline</title>

  <style type="text/css">
    body, html {
      font-family: sans-serif;
    }
  </style>

  <script src="http://visjs.org/dist/vis.js"></script>
  <link href="http://visjs.org/dist/vis.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
<div id="visualization"></div>

<script type="text/javascript">
  // DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');

  var showTimeline = function (user, content, startday) {
      $.ajaxSetup({
        cache: false
      });

      var jqxhr = $.getJSON('ajax_test.php'. {
          usr: user,
          ct: content,
          st: startday
      });

      jqxhr.done(function (data) {
          if(data.rsStatus) {

          }
      })
  }

  // Create a DataSet (allows two way data-binding)
  var items = new vis.DataSet([
    {id: 1, content: 'item 1', start: '2014-04-20'},
    {id: 2, content: 'item 2', start: '2014-04-14'},
    {id: 3, content: 'item 3', start: '2014-04-18'},
    {id: 4, content: 'item 4', start: '2014-04-16', end: '2014-04-19'},
    {id: 5, content: 'item 5', start: '2014-04-25'},
    {id: 6, content: 'item 6', start: '2014-04-27', type: 'point'}
  ]);

  // Configuration for the Timeline
  var options = {};

  // Create a Timeline
  var timeline = new vis.Timeline(container, items, options);
</script>
</body>
</html>