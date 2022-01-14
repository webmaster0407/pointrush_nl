require("./bootstrap");
require("leaflet");
require("bootstrap-select");
require("lightbox2");
global.moment = require("moment");
const moment = require("moment");

require("tempusdominus-bootstrap-4");
var myloc = false;

var green = { color: "#00ff00", fillColor: "#00ff00", fillOpacity: 0.5 };
var blue = { color: "#3388ff", fillColor: "#3388ff", fillOpacity: 0.5 };

$.fn.datetimepicker.Constructor.Default = $.extend(
    {},
    $.fn.datetimepicker.Constructor.Default,
    {
        icons: {
            time: "fas fa-clock",
            date: "fas fa-calendar-alt",
            up: "fas fa-arrow-up",
            down: "fas fa-arrow-down",
            previous: "fas fa-chevron-left",
            next: "fas fa-chevron-right",
            today: "fas fa-calendar-alt-check-o",
            clear: "fas fa-trash",
            close: "fas fa-times"
        }
    }
);
$(document).ready(function () {
    $(".disc").click(function () {
        $("#disclaimer").toggle();
    });

    if ($("#map-claim").length) {
        let id = $("#map-claim").data("claim-id");
        let lat = $("#map-claim").data("claim-lat");
        let lon = $("#map-claim").data("claim-lon");
        let rad = $("#map-claim").data("claim-rad");
        if (lat && lon) {
            var map = L.map("map-claim", {
                center: [lat, lon],
                zoom: 10,
                zoomControl: false
            });
            console.log(id, lat, lon);
            L.control
                .zoom({
                    position: "bottomright"
                })
                .addTo(map);
            L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png", {
                attribution:
                    '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.panTo(new L.LatLng(lat, lon));
            var nrad = 20 - Math.log(rad);
            map.setView([lat, lon], nrad);

            var myIcon = L.divIcon({ className: "my-div-icon-" + 1 });
            L.marker([lat, lon], { icon: myIcon }).addTo(map);

            if (rad == 1) {
                circle = L.marker([lat, lon]).addTo(map);
            } else {
                circle = L.circle([lat, lon], {
                    radius: rad
                }).addTo(map);
            }
            circle.setStyle(green);

            map.locate({
                watch: true,
                enableHighAccuracy: true
            });
            map.on("locationfound", onLocationFound);
            map.on("locationerror", function (e) {
                console.log(e);
                alert(
                    "Location access denied or expired.Please refresh your page"
                );
                $(".claim-save").prop("disabled", true);
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
            const locationIcon = L.divIcon({
                html:
                    '<img src="/images/circle-regular.svg" style="width: 25px;high: 25px;" />',
                // <i class="fas fa-location-arrow" style="color:#ff4000;font-size:22px;text-shadow: 2px 2px rgba(204, 51, 0,.75);"></i>
                iconSize: [22, 22],
                className: "myLocIcon"
            });
            myloc = L.marker([gps.lat, gps.lng], { icon: locationIcon }).addTo(
                map
            );
        }

        let lat = $("#map-claim").data("claim-lat");
        let lon = $("#map-claim").data("claim-lon");
        let rad = $("#map-claim").data("claim-rad");

        // $.each(circle, function(i, v) {
        //     if (v && v._radius) {
        var clatlng = new L.LatLng(lat, lon);
        var mlatlng = new L.LatLng(e.latitude, e.longitude);
        var radius = rad;

        if (map.distance(clatlng, mlatlng) < radius) {
            $(".claim-save").prop("disabled", false);
            circle.setStyle(green);
        } else {
            $(".claim-save").prop("disabled", true);
            circle.setStyle(blue);
        }
        //     }
        // });
    }

    if ($("#logs-table-custom").length) {
        $("#date_time_value").val(moment().subtract('months', 1).format("DD-MM-YYYY HH:MM"));
        fetchlogs();
        fetchTracks();
    }
    if ($("#logs-table-custom-public").length) {
        $("#date_time_value").val(moment().subtract('months', 1).format("DD-MM-YYYY HH:MM"));
        fetchlogsPublic();
    }
    function fetchlogsPublic() {
        $("#logs_data .loader").show();
        let ids = $("#selecttrackpicker").val();
        let datetime = "";

        if ($("#date_time_value").length) {
            datetime = $("#date_time_value").val();
        }
        ////console.log("loading");
        var fetch = $.ajax({
            url: "/track/" + ids + "/log/data?ids=" + ids + "&datetime=" + datetime,
            type: "get",

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (data) {
                $("#logs_data .loader").hide();
                $("#logs-table-custom-public  tbody").html("");
                if (!data.length) {
                    let html =
                        "<tr><td colspan='5' class='text-center'>" +
                        trans("basic.nothing_to_show") +
                        "</td></tr>";
                    $("#logs-table-custom-public  tbody").append(html);
                } else {
                    for (let i = 0; i < data.length; i++) {
                        let element = data[i];
                        if (!element.track || !element.point) {
                            continue;
                        }
                        let html = getRowHtml(element);

                        $("#logs-table-custom-public  tbody").append(html);
                    }
                }
            }
        });
    }
    function fetchlogs() {
        $("#logs_data .loader").show();
        let ids = [];
        let datetime = "";
        if ($("#selecttrackpicker").length) {
            ids = $("#selecttrackpicker").val();
        }

        if (
            initialLogdata &&
            initialLogdata.single == "true" &&
            initialLogdata.tracks
        ) {
            ids = [initialLogdata.tracks.id];
        }

        if ($("#date_time_value").length) {
            datetime = $("#date_time_value").val();
        }
        ////console.log("loading");
        var fetch = $.ajax({
            url: "/admin/claims/?ids=" + ids + "&datetime=" + datetime,
            type: "get",

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (data) {
                $("#logs_data .loader").hide();
                $("#logs-table-custom  tbody").html("");
                if (!data.length) {
                    let html =
                        "<tr><td colspan='5' class='text-center'>" +
                        trans("basic.nothing_to_show") +
                        "</td></tr>";
                    $("#logs-table-custom  tbody").append(html);
                } else {
                    for (let i = 0; i < data.length; i++) {
                        let element = data[i];
                        if (!element.track || !element.point) {
                            continue;
                        }
                        let html = getRowHtml(element);

                        $("#logs-table-custom  tbody").append(html);
                    }
                }
            }
        });
    }

    function fetchTracks() {
        if (initialLogdata && initialLogdata.single == "true") {
            $("#selecttrackpicker").hide();
            return;
        }
        var fetch = $.ajax({
            url: "/admin/tracks/",
            type: "get",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function (data) {
                let tracksOptions = "";
                for (let i = 0; i < data.length; i++) {
                    console.log(data[i]);
                    tracksOptions +=
                        "<option value='" +
                        data[i].id +
                        "'>" +
                        data[i].title +
                        "</option>";
                }
                setTrackoptions(tracksOptions);
            }
        });
    }

    function setTrackoptions(tracksOptions) {
        $("#selecttrackpicker")
            .html(tracksOptions)
            .selectpicker();

        $("#selecttrackpicker").on("changed.bs.select", function (
            e,
            clickedIndex,
            isSelected,
            previousValue
        ) {
            let values = $("#selecttrackpicker").val();
            fetchlogs(values);
        });
    }

    function getRowHtml(d) {
        console.log("created_at", d.created_at);
        let date = moment(d.created_at).format("DD-MM-YYYY HH:MM");
        let html = "<tr>";
        html += "<td>" + d.created_at_formatted + "</td>";

        if (typeof d.visitor_ip !== 'undefined')
            html +=
                "<td> <a  href='" +
                baseUrl +
                "/admin/log/track/" +
                d.track.id +
                "'>" +
                d.track.title +
                "</a></td>";
        else
            html += "<td>" + d.track.title + "</td>";

        html += "<td>" + d.point.title + "</td>";
        html += "<td>" + d.remark + "</td>";

        if (d.photo == null)
            html += "<td></td>";
        else
            html += `<td>
                        <a href='/admin/photo/${d.photo}' data-lightbox="${d.track.id}" data-title="${d.photo}">
                        <img src='/admin/photo/${d.photo}' class='rounded' height='40' width='40' />
                        </a>
                    </td>`;
        // html += `<td>
        //             <a href='javascript:;' onclick="openPhoto('/admin/photo/${d.photo}')">
        //                 <img src='/admin/photo/${d.photo}' class='rounded' height='40' width='40' />
        //             </a>
        //         </td>`;

        if (typeof d.visitor_ip !== 'undefined')
            html += "<td>" + d.visitor_ip + "</td>";

        html += "</tr>";
        return html;
    }

    $("body").on("click", "#datetime_logs", function () {
        $("#datetimeModal").modal("show");

        $("#datetimepicker_logs").on("change.datetimepicker", function (e) {
            $("#date_time_value").val(e.date.format("DD-MM-YYYY HH:mm"));
            fetchlogs();
        });
    });
    $("body").on("click", "#refresh", function () {
        $("#logs_data .loader").show();
        fetchTracks();
        fetchlogs();
    });

    $("#datetimepicker_logs").datetimepicker({
        inline: true,
        sideBySide: true,
        format: "DD-MM-YYYY HH:mm",
        defaultDate: moment().subtract('months', 1)
    });

    $("#logs-table-custom th").click(function () {
        if (
            $(this)
                .find(".fas")
                .hasClass("fa-arrow-down")
        ) {
            $(this)
                .find(".fas")
                .removeClass("fa-arrow-down")
                .addClass("fa-arrow-up");
        } else {
            $(this)
                .find(".fas")
                .removeClass("fa-arrow-up")
                .addClass("fa-arrow-down");
        }
        var table = $(this)
            .parents("table")
            .eq(0);
        var rows = table
            .find("tr:gt(0)")
            .toArray()
            .sort(comparer($(this).index()));
        this.asc = !this.asc;
        if (!this.asc) {
            rows = rows.reverse();
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i]);
        }
    });
    function comparer(index) {
        return function (a, b) {
            var valA = getCellValue(a, index),
                valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB)
                ? valA - valB
                : valA.toString().localeCompare(valB);
        };
    }
    function getCellValue(row, index) {
        return $(row)
            .children("td")
            .eq(index)
            .text();
    }
});
