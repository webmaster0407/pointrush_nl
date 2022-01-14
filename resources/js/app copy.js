require('./bootstrap');
require('leaflet');
require('moment');
var moment = require('moment-timezone');
require('jquery-countdown');
$(document).ready(function () {
    var config = {
        "useloc": true
    };
    var del = ',';
    var gps = {
        lat: 0,
        lng: 0
    };
    var myloc = false;
    var circle;
var green={color: "#00ff00",fillColor:"#00ff00",fillOpacity: 0.5};
var blue={color: "#3388ff",fillColor: "#3388ff",fillOpacity: 0.5};
    var map = L.map('map', {
        center: [52.28, 4.8],
        zoom: 10,
        zoomControl: false
    });
    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);
    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // L.marker([52.3667, 4.8945]).addTo(map);


    if (config['useloc']) {
        map.locate({
            watch: true,
            enableHighAccuracy: true
        });
        map.on('locationfound', onLocationFound);
        map.on('locationerror', function (e) {
            console.log(e);
            alert("Location access denied.");
        });
    }
    getpoint();












    $('.disc').click(function () {

        $('#disclaimer').toggle();
    });

    function getpoint() {
     
        $.get({
            url: '/getpoint/'+location.href.substring(location.href.lastIndexOf('/') + 1),
            cache: 'false'
        }).then(function (data) {

            var d = convertData(data);
           // var d = data.data;
            switchCircle(d);


        });
    }

    function convertData(data) {


        var res = [];

        //var row = data.split('\n');
var row=data.data;
console.log(data);
        $.each(row, function () {
            if (this.length != 0) {
                var obj = new Object;
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
                obj.radius = this.radius;

                res.push(obj);
            }
        });


        return res;
    }

    function switchCircle(d) {
console.log(d);
        var pos = -1;
        var datetime;
        $.each(d, function (ind, val) {
            console.log(ind, val);
            var now = moment();
            //var end = moment(this.stop + " +0100", "DD-MM-YYYY hh:mm:ss Z");
            var end = moment(this.stop + " +0100", "YYYY-MM-DD hh:mm:ss Z");
            console.log("aa",now,end);
            if (now < end) {
                pos = ind;
                datetime = end.toDate();
                return false;
            }
        });
        console.log("pos",pos);
        if (pos == -1) {
            $('#clock').html('00:00').parent().addClass('disabled');
        } else {
            if (circle) {
                map.removeLayer(circle);
            }
            if (d[pos].loc) {


                map.panTo(new L.LatLng(d[pos].lat, d[pos].lon));
                var nrad = 20 - Math.log(d[pos].radius);
                map.setView([d[pos].lat, d[pos].lon], nrad);



                circle = L.circle([d[pos].lat, d[pos].lon], {radius: d[pos].radius}).addTo(map);
                if (config['useloc']) {
                    var clatlng = new L.LatLng(d[pos].lat, d[pos].lon);
                    var mlatlng = new L.LatLng(gps.lat, gps.lng);
                    if (map.distance(clatlng, mlatlng) < d[pos].radius) {
                        circle.setStyle(green);

                    } 
                } 

               

            }

            $('#clock').countdown(datetime)
                .on('update.countdown', function (event) {
                    var totalHours = event.offset.totalDays * 24 + event.offset.hours;

                    $(this).html(event.strftime(totalHours + ':%M:%S'));
                })
                .on('finish.countdown', function (event) {

                    getpoint();

                });






        }
    }





    function onLocationFound(e) {

        gps = {
            lat: e.latitude,
            lng: e.longitude
        };
        var latlng = new L.LatLng(e.latitude, e.longitude);
        if (myloc) {
        
            myloc.setLatLng(latlng);

        } else {
         
           myloc = L.marker([gps.lat, gps.lng]).addTo(map);
        }
        if (circle) {

            var clatlng = circle.getLatLng();
            var mlatlng = new L.LatLng(e.latitude, e.longitude);
            var radius = circle.getRadius();

            if (map.distance(clatlng, mlatlng) < radius) {
                circle.setStyle(green);

            } else{
                circle.setStyle(blue);
            }
            
        }






    }
});