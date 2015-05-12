/**
 * Created by CYa on 2015/5/12.
 */

$(document).ready(function () {
    var g2 = {
        nodes: [],
        edges: []
    };

    s2 = new sigma({
        graph: g2,
        container: 'mentionGraph',
        renderer: {
            container: document.getElementById('mentionGraph'),
            type: 'canvas'
        }
    });
    sigma.parsers.json('mention250_filter_50n.json',
            s2,
            function () {
                s2.settings({
                    defaultLabelSize: 18,
                    font: "微軟正黑體",
                    labelHoverBGColor: 'node',
                    defaultLabelHoverColor: '#000',
                    labelHoverShadow: 'node',
                    labelThreshold: 1
                });
                s2.refresh();
            }
    );
/*
    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        s.settings({
           autoRescale: true 
        });
        s.refresh();
    });
*/
});