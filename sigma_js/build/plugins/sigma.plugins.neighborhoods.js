(function () {
    "use strict";
    if ("undefined" == typeof sigma)
        throw"sigma is not declared";
    sigma.classes.graph.addMethod("neighborhood", function (a) {
        var b, c, d, e, f, g = {}, h = {}, i = {nodes: [], edges: []};
        if (!this.nodes(a))
            return i;
        e = this.nodes(a), f = {}, f.center = !0;
        for (b in e)
            f[b] = e[b];
        g[a] = !0, i.nodes.push(f);
        for (b in this.allNeighborsIndex[a]) {
            g[b] || (g[b] = !0, i.nodes.push(this.nodesIndex[b]));
            for (c in this.allNeighborsIndex[a][b])
                h[c] || (h[c] = !0, i.edges.push(this.edgesIndex[c]))
        }
        for (b in g)
            if (b !== a)
                for (c in g)
                    if (c !== a && b !== c && this.allNeighborsIndex[b][c])
                        for (d in this.allNeighborsIndex[b][c])
                            h[d] || (h[d] = !0, i.edges.push(this.edgesIndex[d]));
        return i
    }), sigma.classes.graph.addMethod('neighbors', function (nodeId) {
        var k,
                neighbors = {},
                index = this.allNeighborsIndex[nodeId] || {};

        for (k in index)
            neighbors[k] = this.nodesIndex[k];

        return neighbors;
    }), sigma.utils.pkg("sigma.plugins"), sigma.plugins.neighborhoods = function () {
        var a = new sigma.classes.graph;
        this.neighborhood = function (b) {
            return a.neighborhood(b)
        }, this.load = function (b, c) {
            var d = function () {
                if (window.XMLHttpRequest)
                    return new XMLHttpRequest;
                var a, b;
                if (window.ActiveXObject) {
                    a = ["Msxml2.XMLHTTP.6.0", "Msxml2.XMLHTTP.3.0", "Msxml2.XMLHTTP", "Microsoft.XMLHTTP"];
                    for (b in a)
                        try {
                            return new ActiveXObject(a[b])
                        } catch (c) {
                        }
                }
                return null
            }();
            if (!d)
                throw"XMLHttpRequest not supported, cannot load the data.";
            return d.open("GET", b, !0), d.onreadystatechange = function () {
                4 === d.readyState && (a.clear().read(JSON.parse(d.responseText)), c && c())
            }, d.send(), this
        }, this.read = function (b) {
            a.clear().read(b)
        }
    }
}).call(window);