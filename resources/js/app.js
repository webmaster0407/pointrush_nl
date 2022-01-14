require("./bootstrap");
require("leaflet");
require("moment");
var moment = require("moment-timezone");
require("jquery-countdown");
var isloaded = false;
var circle = [];
$(document).ready(function () {
    if ($(".toast").length) {
        $(".toast").toast("show");
    }

    $(".set-tooltip").tooltip({
        trigger: 'hover'
    });

    var config = {
        useloc: true
    };
    var del = ",";
    var gps = {
        lat: 0,
        lng: 0
    };
    var myloc = false;

    var green = { color: "#00ff00", fillColor: "#00ff00", fillOpacity: 0.5 };
    var blue = { color: "#3388ff", fillColor: "#3388ff", fillOpacity: 0.5 };
    var map = L.map("map", {
        center: [52.28, 4.8],
        zoom: 10,
        zoomControl: false
    }).locate({ setView: true, maxZoom: 16 });
    L.control
        .zoom({
            position: "bottomright"
        })
        .addTo(map);
    L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // L.marker([52.3667, 4.8945]).addTo(map);

    if (config["useloc"]) {
        map.locate({
            watch: true,
            enableHighAccuracy: true
        });
        map.on("locationfound", onLocationFound);
        map.on("locationerror", function (e) {
            console.log(e);
            getpoint();
            alert("Location access denied or expired.");

            $("#img-offline").show();
        });
    }
    console.log("calling 0");

    // getpoint();

    $(".disc").click(function () {
        $("#disclaimer").toggle();
    });

    function getpoint(isetview = true) {
        $.get({
            url:
                "/getpoint/" +
                location.href.substring(location.href.lastIndexOf("/") + 1),
            cache: "false"
        }).then(function (data) {
            var d = convertData(data);
            // var d = data.data;
            switchCircle(d, isetview);
        });
    }

    function convertData(data) {
        var res = [];

        //var row = data.split('\n');
        var row = data.data;
        //console.log(data);
        $.each(row, function (ind, val) {
            if (this.length != 0) {
                var obj = new Object();
                // var cols = this.split(del);
                obj.start = this.start;
                obj.stop = this.stop;
                obj.time = this.time;
                //var coord = cols[1].split('/');
                if (this.lat.length && this.lon.length) {
                    obj.lat = this.lat;
                    obj.lon = this.lon;
                    obj.loc = true;
                } else {
                    obj.loc = false;
                }
                obj.color = this.color;
                obj.transparant = this.transparant;
                obj.radius = this.radius;
                obj.showtitle = this.showtitle;
                obj.title = this.title;
                obj.pid = this.id;
                obj.showclaim = this.showclaim;
                obj.showrequest = this.showrequest;
                obj.id = ind + 1;
                res.push(obj);
            }
        });

        return res;
    }

    function styleCircle(clr, trp) {
        let mainColor = clr.substr(0, 7);
        let opacityColor = "";

        if (trp) {
            opacityColor = "00";
            clr = mainColor + opacityColor;
        }

        return {
            original: clr,
            main: mainColor,
            color: clr,
            fillColor: clr,
            fillOpacity: 0.5,
            transparant: trp == 1,
            text: clr
        };
    }

    function switchCircle(d, issetview = true) {
        //console.log("d",d);
        var pos = -1;
        var datetime;
        $.each(circle, function (ind, val) {
            map.removeLayer(circle[ind]);
            $(".my-div-icon-" + ind).remove();
        });
        $.each(d, function (ind, val) {
            console.log(ind, val);
            var lon = val.lon;
            if (lon < -180) {
                lon += 360;
            }
            if (lon > 180) {
                lon -= 360;
            }
            // console.log(lon);

            var now = moment();
            //var end = moment(this.stop + " +0100", "DD-MM-YYYY hh:mm:ss Z");
            var end = moment(this.stop + " +0100", "YYYY-MM-DD hh:mm:ss Z");
            //console.log("aa",now,end);
            // if (now < end) {
            //     pos = ind;
            //     datetime = end.toDate();
            //     return false;
            // }

            // if (d.loc) {

            //console.log("val",val);

            if (issetview) {
                map.panTo(new L.LatLng(val.lat, val.lon));
                var nrad = 20 - Math.log(val.radius);
                console.log("issetview", issetview, nrad);
                map.setView([val.lat, val.lon], nrad);
            }

            var myIcon = L.divIcon({ className: "my-div-icon-" + ind });
            // you can set .my-div-icon styles in CSS
            L.marker([val.lat, val.lon], { icon: myIcon }).addTo(map);
            if (val.radius == 1) {
                circle[ind] = L.marker([val.lat, val.lon]).addTo(map);
            } else {
                circle[ind] = L.circle([val.lat, val.lon], {
                    radius: val.radius
                }).addTo(map);
            }
            circle[ind].setStyle(styleCircle(val.color, val.transparant));
            console.log("==============================");
            console.log(circle[ind]);
            console.log("------------------------------");
            console.log(val);

            var pstop = moment(val.stop).isAfter();

            if (val.time || val.showtitle) {
                $(".my-div-icon-" + ind).addClass("my-div-icon");
                $(".my-div-icon-" + ind)
                    .countdown(val.stop)
                    .on("update.countdown", function (event) {
                        let html = "";
                        let title = val.showtitle ? val.title : "";
                        if (val.time) {
                            var totalHours =
                                event.offset.totalDays * 24 +
                                event.offset.hours;
                            html += event.strftime(totalHours + ":%M:%S");
                        }
                        if (val.time && title) {
                            html += "<br>";
                        }

                        if (title) {
                            html += title;
                        }
                        let resultClr = styleCircle(val.color, val.transparant);
                        $(this).html(html);
                        $(".my-div-icon-" + ind).css("color", resultClr.text);
                        $(".my-div-icon-" + ind).css("width", "unset");
                        $(".my-div-icon-" + ind).css(
                            "margin-left",
                            -($(".my-div-icon-" + ind).width() / 2)
                        );
                        $(".my-div-icon-" + ind).css("height", "unset");
                        $(".my-div-icon-" + ind).css("text-align", "center");
                        $(".my-div-icon-" + ind).css(
                            "margin-top",
                            -($(".my-div-icon-" + ind).height() / 2)
                        );
                    })
                    .on("finish.countdown", function (event) {
                        console.log("calling 3");

                        getpoint();
                    });
            } else if (val.showtitle) {
                let resultClr = styleCircle(val.color, val.transparant);
                // L.marker([val.lat, val.lon], { icon: myIcon }).addTo(map);
                $(".my-div-icon-" + ind).addClass("my-div-icon");
                $(".my-div-icon-" + ind).html(
                    "<div class='my-div-icon-title'>" + val.title + "</div>"
                );
                $(".my-div-icon-" + ind).css("color", resultClr.text);
                $(".my-div-icon-" + ind).css("width", "unset");
                $(".my-div-icon-" + ind).css("text-align", "center");

                $(".my-div-icon-" + ind).css(
                    "margin-left",
                    -($(".my-div-icon-" + ind).width() / 2)
                );
                $(".my-div-icon-" + ind).css("height", "unset");
                $(".my-div-icon-" + ind).css(
                    "margin-top",
                    -($(".my-div-icon-" + ind).height() / 2)
                );
            }

            if (config["useloc"]) {
                console.log(gps);
                var clatlng = new L.LatLng(val.lat, val.lon);
                var mlatlng = new L.LatLng(gps.lat, gps.lng);
                let trackId = location.href.substring(
                    location.href.lastIndexOf("/") + 1
                );
                if (map.distance(clatlng, mlatlng) < val.radius) {
                    circle[ind].setStyle(green);
                    // console.log(val.showclaim);
                    if (val.showclaim == 1) {
                        circle[ind].on("click", function () {
                            //open your edit page here
                            // window.open(
                            //     baseUrl +
                            //         "/track/" +
                            //         trackId +
                            //         "/waypoint/" +
                            //         val.pid +
                            //         "/claim"
                            // );
                            location.href =
                                baseUrl +
                                "/track/" +
                                trackId +
                                "/waypoint/" +
                                val.pid +
                                "/claim";
                        });

                        if ($(".my-div-icon-" + ind).length) {
                            $(".my-div-icon-" + ind).on("click", function () {
                                //open your edit page here
                                // window.open(
                                //     baseUrl +
                                //         "/track/" +
                                //         trackId +
                                //         "/waypoint/" +
                                //         val.pid +
                                //         "/claim"
                                // );

                                location.href =
                                    baseUrl +
                                    "/track/" +
                                    trackId +
                                    "/waypoint/" +
                                    val.pid +
                                    "/claim";
                            });
                        }
                    }
                } else {
                    circle[ind].setStyle(styleCircle(val.color, val.transparant));
                    circle[ind].off("click");
                    $(".my-div-icon-" + ind).off("click");

                    // map.removeLayer(circle[ind]);
                    // $(".my-div-icon-" + ind).remove();
                }
            }

            // }
        });

        // //console.log("pos",pos);
        if (pos == -1) {
            // $('#clock').html('00:00').parent().addClass('disabled');
        } else {
            // if (d[pos].loc) {
            //     map.panTo(new L.LatLng(d[pos].lat, d[pos].lon));
            //     var nrad = 20 - Math.log(d[pos].radius);
            //     map.setView([d[pos].lat, d[pos].lon], nrad);
            //     circle = L.circle([d[pos].lat, d[pos].lon], {radius: d[pos].radius}).addTo(map);
            //     if (config['useloc']) {
            //         var clatlng = new L.LatLng(d[pos].lat, d[pos].lon);
            //         var mlatlng = new L.LatLng(gps.lat, gps.lng);
            //         if (map.distance(clatlng, mlatlng) < d[pos].radius) {
            //             circle.setStyle(green);
            //         }
            //     }
            // }
            // $('#clock').countdown(datetime)
            //     .on('update.countdown', function (event) {
            //         var totalHours = event.offset.totalDays * 24 + event.offset.hours;
            //         $(this).html(event.strftime(totalHours + ':%M:%S'));
            //     })
            //     .on('finish.countdown', function (event) {
            //         getpoint();
            //     });
        }
    }

    function onLocationFound(e) {
        console.log(e);

        $("#img-offline").hide();

        gps = {
            lat: e.latitude,
            lng: e.longitude
        };
        var latlng = new L.LatLng(e.latitude, e.longitude);
        if (myloc) {
            myloc.setLatLng(latlng);
        } else {
            const locationIcon = L.divIcon({
                html:
                    '<img src="/images/circle-regular.svg" style="width: 25px;high: 25px;" />',
                // '<i class="fas fa-location-arrow" style="color:#ff4000;font-size:22px;text-shadow: 2px 2px rgba(204, 51, 0,.75);"></i>',
                iconSize: [22, 22],
                className: "myLocIcon"
            });
            myloc = L.marker([gps.lat, gps.lng], { icon: locationIcon }).addTo(
                map
            );
        }

        if (!isloaded) {
            map.panTo(latlng);
            map.setView([e.latitude, e.longitude], 17);
            isloaded = true
        }
        getpoint(false);
        $.each(circle, function (i, v) {
            if (v && v._radius) {
                var clatlng = v.getLatLng();
                var mlatlng = new L.LatLng(e.latitude, e.longitude);
                var radius = v.getRadius();

                if (map.distance(clatlng, mlatlng) < radius) {
                    // v.setStyle(green);
                    console.log("calling 1");
                } else {
                    // v.setStyle(blue);
                }
            }
        });
    }
});
