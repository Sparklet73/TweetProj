<!DOCTYPE html>
<html lang="zh">
<head>
    <title>thesisproj</title>
    <meta charset="utf-8">
    <script src="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css"></script>
    <script src="jquery/jquery-2.1.3.min.js"></script>
    <style type="text/css">
        body, html {
            font-family: sans-serif;
        }
        #cy {
            height: 100%;
            width: 100%;
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>

</head>
<body>
<script src="sigma.js/build/sigma.min.js"></script>
<script src="sigma.js/build/plugins/sigma.parsers.gexf.min.js"></script>
<div id="container">
    <style>
        #graph-container {
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            position: absolute;
        }
    </style>
    <div id="graph-container"></div>
</div>
<script>
    /**
     * Here is just a basic example on how to properly display a graph
     * exported from Gephi in the GEXF format.
     *
     * The plugin sigma.parsers.gexf can load and parse the GEXF graph file,
     * and instantiate sigma when the graph is received.
     *
     * The object given as the second parameter is the base of the instance
     * configuration object. The plugin will just add the "graph" key to it
     * before the instanciation.
     */
    sigma.parsers.gexf('arctic.gexf', {
        container: 'graph-container'
    });
</script>
</body>
</html>