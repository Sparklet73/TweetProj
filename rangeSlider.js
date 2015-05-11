/**
 * Created by CYa on 2015/5/11.
 */
$(document).ready(function () {
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

    startday = "2014-08-25";
    endday = "2014-09-05";
    $("button[name='update']").click(function () {
        var dateValues = $("#rangeSlider").dateRangeSlider("values");
        startmon = dateValues.min.getMonth()+1;
        startday = dateValues.min.getFullYear() + "-" + startmon + "-" + dateValues.min.getDate() ;
        endmon = dateValues.max.getMonth()+1;
        endday = dateValues.max.getFullYear() + "-" + endmon + "-" + dateValues.max.getDate() ;
    });
});