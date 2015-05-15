/* 暫時不用了，因為有些字與字的關聯太多(e.g.,戴耀廷,梁振英)，以時間線太費空間而且這個plugin不太彈性
 * vertical tweets line */
function returnNodeTweets(nodeLabel, arrNeighbors){
    $.ajaxSetup({
        cache: false
    });
    
    strNeighbors = "";
    for(i = 0; i < arrNeighbors.length; i++){
        strNeighbors += arrNeighbors[i].label + "//" ;
    }
    
    var jqxhr = $.getJSON('ajax_verticalTweetsLine.php', {
        nl: nodeLabel,
        sn: strNeighbors
    });

    jqxhr.done(function (data) {

        if (data.rsStat) {
            //do something
        } else {
            showMessage('danger', data.rsGraph);
        }
    });
};

$(document).ready(function () {
    $(function () {
        $("#verticalTweetsLine").timeline({
            data: [
                {date: new Date(2014, 9, 28), type: "someType", title: "戴耀廷", description: "梁振英：戴耀廷佔中言論對落實普選無幫..."},
                {date: new Date(2014, 11, 12), type: "someType", title: "李飛", description: "黃之鋒：鼓動我參與抗爭行動的人是李飛而不是戴耀廷。特首梁振英今日在港台節目「香港家書」中，表示佔領中環運動發起人們，不要將學生作為政治籌碼，一如既往地否定學生的獨立思考能力。"}
            ],
            height: 600
        });
    });


    $.widget('pi.timeline', {
        options: {
            data: [
                {date: new Date(), type: "Type1", title: "Title1", description: "Description1"},
                {date: new Date(), type: "Type2", title: "Title2", description: "Description2"}
            ],
            types: [
                {name: "Type1", color: "#00ff00"},
                {name: "Type2", color: "#0000ff"}
            ],
            display: "auto",
            height: 600
        },
        _create: function () {
            this._refresh();
        },
        _refresh: function () {
            var miliConstant = 86400000
            var firstDate = this.options.data[0].date;
            var lastDate = this.options.data[0].date;
            for (i = 0; i < this.options.data.length; i++) {
                if (this.options.data[i].date > lastDate) {
                    lastDate = this.options.data[i].date;
                }
                else if (this.options.data[i].date < firstDate) {
                    firstDate = this.options.data[i].date;
                }
            }
            var dayRange = (lastDate - firstDate) / miliConstant;
            var segSpace = Math.floor(this.options.height / (dayRange / 7));
            var segLength = 7;
            if (segSpace < 80) {
                var segSpace = Math.floor(this.options.height / (dayRange / 14));
                segLength = 14;
            }
            if (segSpace < 80) {
                var segSpace = Math.floor(this.options.height / (dayRange / 28));
                segLength = 28;
            }
            if (segSpace < 80) {
                var segSpace = Math.floor(this.options.height / (dayRange / 56));
                segLength = 56;
            }
            if (segSpace < 80) {
                var segSpace = Math.floor(this.options.height / (dayRange / 112));
                segLength = 112;
            }

            var majorCount = Math.floor(this.options.height / segSpace) + 1;

            //Empty Current Element
            this.element.empty();

            //Draw TimeLine
            this.element.append("<div class='tlLine' style='height: " + this.options.height + "px;'></div>")

            //Draw Major Markers
            var tempDate = new Date(firstDate.getTime());
            for (i = 0; i < majorCount; i++) {
                this.element.append("<div class='tlMajor' style='top: " + ((segSpace * i) - 7) + "px;'></div>");
                this.element.append("<span class='tlDateLabel' style='top: " + ((segSpace * i) - 7) + "px;'>" + $.datepicker.formatDate("yy-m-d", tempDate) + "</span>");
                tempDate.setDate(tempDate.getDate() + segLength);
            }

            //draw event markers
            for (i = 0; i < this.options.data.length; i++) {
                var dayPixels = ((this.options.data[i].date - firstDate) / (lastDate - firstDate)) * this.options.height;
                //alert((this.options.data[i].date - firstDate) + ", " + (lastDate - firstDate) + ", " +dayPixels);
                this.element.append("<div class='tlDateDot' style='top: " + (dayPixels - 11) + "px;'></div>");
                this.element.append("<div class='tlEventFlag' style='top: " + (dayPixels - 11) + "px;'>" + this.options.data[i].title + "</div>");
                this.element.append("<div class='tlEventExpand' style='top: " + (dayPixels - 11) + "px;'><p><b>" + this.options.data[i].date + "</b></p><p>" + this.options.data[i].description + "<p></div>");
            }

            $(".tlEventExpand").hide();

            $(".tlEventFlag").click(function () {
                var tempThis = $(this);
                $(".tlEventExpand").hide();
                $(".tlEventFlag").animate({width: '100px'}, 200);
                if (tempThis.hasClass('active')) {
                    tempThis.removeClass('active');
                } else {
                    $(".tlEventFlag").removeClass('active');
                    tempThis.addClass('active');
                    tempThis.animate({width: '120px'}, 200, function () {
                        tempThis.next('div').show();
                    });
                }
            });
        },
        _destroy: function () {
        },
        _setOptions: function () {
        }
    });
});