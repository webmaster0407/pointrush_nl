require("./bootstrap");
// require('./tracktable');
require("leaflet");

require("leaflet");
var handler = require("leaflet-path-drag");
const moment = require("moment");
require("jquery-countdown");
global.moment = require("moment");

require("tempusdominus-bootstrap-4");
require("@claviska/jquery-minicolors");
require("wcolpick");

import NProgress from "nprogress";

const customDataTableId = "data-table-custom";
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
require("moment-timezone");
var table;
// var z="+0300";
var Tabulator = require("tabulator-tables");

var map = null;
var redIcon = new L.Icon({
    iconUrl: "/images/img/marker-icon-2x-red.png",
    shadowUrl: "/images/marker-shadow.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});
var marker = [];
var infoMarker = [];
var newColor = "";
var transparant = false;

function getCheckedToggle(id, className) {
    return (
        ' <form><div class="custom-control custom-switch">' +
        '<input type="checkbox" class="custom-control-input ' +
        className +
        ' " id="' +
        id +
        '" checked>' +
        '<label class="custom-control-label" for="' +
        id +
        '"></label>' +
        "</div></form>"
    );
}

function getunCheckedToggle(id, className) {
    return (
        ' <form><div class="custom-control custom-switch">' +
        '<input type="checkbox" class="custom-control-input ' +
        className +
        '" id="' +
        id +
        '" >' +
        '<label class="custom-control-label" for="' +
        id +
        '"></label>' +
        "</div></form>"
    );
}

$(document).ready(function () {
    var del = ",";
    var circle = [];
    var green = {
        color: "#00ff00",
        fillColor: "#00ff00",
        fillOpacity: 0.5
    };
    var blue = {
        color: "#3388ff",
        fillColor: "#3388ff",
        fillOpacity: 0.5
    };
    var yellow = {
        color: "#ffff33",
        fillColor: "#ffff33",
        fillOpacity: 0.5
    };

    var selected;
    var befselected = 0;
    //////console.log('showing map');
    if ($("#map").length) {
        map = L.map("map", {
            // center: [52.144681, 6.394280],
            zoom: 10,
            zoomControl: false
        }).locate({ setView: true, maxZoom: 16 });
        // map = L.map('map', {
        //     center: [52.144681, 6.394280],
        //     zoom: 10,
        //     zoomControl: false
        // });
        L.control
            .zoom({
                position: "bottomright"
            })
            .addTo(map);
        L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png", {
            attribution:
                '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        //////console.log('tiles loaded');
    }
    var cGroup = null;

    $(".set-tooltip").tooltip({
        trigger: 'hover'
    });

    // $.get('config.csv', function (data) {
    getpoint();

    $("#add").click(function () {
        var curdata = getTableData();
        // var curdata = table.getData();
        ////console.log("ll",curdata);
        if (curdata.length) {
            var nid = curdata[curdata.length - 1].id + 1;
        } else {
            var nid = 1;
        }

        // get center of current view
        // let latlng = map.getBounds().getCenter();
        // marker[nid] = L.marker([latlng.lat, latlng.lng], {
        //     icon: redIcon,
        //     draggable: "true",
        //     autoPan: true,
        //     myid: nid
        // }).addTo(map);

        // marker[nid].on("dragend", function(event) {
        //     var markerNew = event.target;
        //     var position = markerNew.getLatLng();
        //     updateMovableMarker(position, nid, table);
        // });
        let latlng = map.getBounds().getCenter();

        let lat = latlng.lat;
        let lng = latlng.lng;

        while (lng < -180) {
            lng = parseFloat(lng) + 360;
        }

        while (lng > 180) {
            lng = parseFloat(lng) - 360;
        }

        let data = {
            id: nid,
            loc: false,
            lat: lat,
            lon: lng,
            // lat: "52.144681",
            // lon: "6.394280",
            radius: 10,
            title: "",
            start: moment(new Date()).format("DD-MM-YYYY HH:mm"),
            stop: moment(new Date()).format("DD-MM-YYYY HH:mm"),
            time: false,
            showtitle: false
        };
        $("#AddFormModal #track-lat").val(lat);
        $("#AddFormModal #track-lon").val(lng);
        $("#AddFormModal #track-radius").val(10);
        $("#AddFormModal #track-id").val(nid);
        $("#AddFormModal").modal("show");
        // let html = getRowHtml(nid, data);
        // $("#" + customDataTableId + "  tbody").append(html);

        // console.log("curdata", curdata);
        // console.log("nid", nid);
        // table.addData(
        //     [
        //         {
        //             id: nid,
        //             loc: false,
        //             lat: latlng.lat,
        //             lon: latlng.lng,
        //             // lat: "52.144681",
        //             // lon: "6.394280",
        //             radius: 10,
        //             title: "",
        //             start: "",
        //             stop: "",
        //             time: false,
        //             showtitle: true
        //         }
        //     ],
        //     false
        // );
    });
    $("#save").click(function () {
        $("#form-errors").html("");
        let tabledata = getTableData();
        // tabledata = table.getData();
        $.ajax({
            type: "POST",
            url:
                "/admin/savepoints/" +
                location.href.substring(location.href.lastIndexOf("/") + 1),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                data: tabledata
            }
        })
            .done(function (response) {
                // switchCircle(getTableData(), true);
                if (response.status === 0 && response.data.length) {
                    let html = "<ul>";
                    response.data.forEach(element => {
                        html += "<li>" + element + "</li>";
                    });
                    html += "</ul>";
                    let block = createErrorBlock(html);
                    $("#form-errors").html(block);
                } else if (response.status === 1) {
                    $("#" + customDataTableId + "  tbody").html("");
                    var d = convertData(response);
                    console.log(d);
                    createTableData(d);
                    let block = createErrorBlock(
                        trans("messages.saved_success"),
                        "success"
                    );
                    $("#form-errors").html(block);

                    switchCircle(tabledata, true);
                }
            })
            .fail(function (jqXHR, textStatus) {
                let block = createErrorBlock(trans("messages.server_error"));
                $("#form-errors").html(block);
                // alert("error " + textStatus);
            });
    });
    $(".disc").click(function () {
        $("#disclaimer").toggle();
    });

    function getpoint() {
        //  ////console.log('getting point');
        let id = location.href.substring(location.href.lastIndexOf("/") + 1);
        if (!id) {
            return;
        }
        $.get({
            url:
                "/admin/getpoints/" +
                location.href.substring(location.href.lastIndexOf("/") + 1),
            cache: "false"
        }).then(function (data) {
            var d = convertData(data);
            createTableData(d);

            switchCircle(d);

            if ($("#data-table").length && 0 == 1) {
                table = new Tabulator("#data-table", {
                    layout: "fitColumns",
                    selectable: 1,
                    data: d,
                    columns: [
                        {
                            title: "ID",
                            field: "id"
                        },
                        {
                            title: trans("basic.title"),
                            field: "title",
                            editor: true
                        },
                        {
                            title: trans("basic.start"),
                            field: "start",

                            editor: dateEditor,
                            cellClick: function (e, cell) {
                                cell.setValue(
                                    moment(new Date()).format(
                                        "DD-MM-YYYY HH:mm"
                                    )
                                );
                            }
                        },
                        {
                            title: trans("basic.stop"),
                            field: "stop",
                            editor: dateEditor,
                            cellClick: function (e, cell) {
                                cell.setValue(
                                    moment(new Date()).format(
                                        "DD-MM-YYYY HH:mm"
                                    )
                                );
                            }
                        },
                        {
                            title: trans("basic.lat"),
                            field: "lat",
                            editor: true
                        },
                        {
                            title: trans("basic.lon"),
                            field: "lon",
                            editor: true
                        },
                        {
                            title: trans("basic.radius"),
                            field: "radius",
                            editor: true
                        },
                        {
                            title: trans("basic.timer"),
                            field: "time",
                            align: "center",
                            editor: true,
                            formatter: "tickCross"
                        },
                        {
                            title: trans("basic.show_title"),
                            field: "showtitle",
                            align: "center",
                            editor: true,
                            formatter: "tickCross"
                        },
                        {
                            title: "",
                            headerSort: false,
                            formatter: function (
                                cell,
                                formatterParams,
                                onRendered
                            ) {
                                return '<i class="fas fa-copy"></i>';
                            },
                            width: 30,
                            align: "center",
                            cellClick: function (e, cell) {
                                var curdata = getTableData();
                                // var curdata = table.getData();
                                if (curdata.length) {
                                    var nid =
                                        curdata[curdata.length - 1].id + 1;
                                } else {
                                    var nid = 1;
                                }
                                table.addData(
                                    [
                                        {
                                            id: nid,
                                            start: cell._cell.row.data.start,
                                            stop: cell._cell.row.data.stop,
                                            loc: cell._cell.row.data.loc,
                                            lat: cell._cell.row.data.lat,
                                            lon: cell._cell.row.data.lon,
                                            radius: cell._cell.row.data.radius,
                                            title: cell._cell.row.data.title,
                                            time: cell._cell.row.data.time,
                                            showtitle:
                                                cell._cell.row.data.showtitle
                                        }
                                    ],
                                    false
                                );
                            }
                        },
                        {
                            title: "",
                            headerSort: false,
                            formatter: "buttonCross",
                            width: 30,
                            align: "center",
                            cellClick: function (e, cell) {
                                console.log(cell.getRow().getData());
                                let nid = cell.getRow().getData().id;
                                if (marker[nid]) {
                                    map.removeLayer(marker[nid]);
                                }
                                delete marker[nid];
                                table.deleteRow(cell.getRow().getData().id);
                            }
                        }
                    ],
                    rowSelectionChanged: function (data, rows) {
                        $.each(circle, function (ind, val) {
                            if (circle[ind]) {
                                if (circle[ind]._radius) {
                                    this.setStyle(styleCircle(ind));
                                }
                            }
                        });

                        if (data.length != 0) {
                            if (circle[data[0].id]) {
                                selected = data[0];
                                if (circle[data[0].id]._radius) {
                                    circle[data[0].id].bringToFront();
                                    circle[data[0].id].setStyle(yellow);
                                }
                                var nrad = 20 - Math.log(data[0].radius);
                                map.setView([data[0].lat, data[0].lon], nrad);
                            }
                        }
                    },
                    dataEdited: function (data) {
                        $.each(data, function (ind, val) {
                            if (this.lat == "" || this.lon == "") {
                                table.updateData([
                                    {
                                        id: ind + 1,
                                        loc: false
                                    }
                                ]);
                            } else {
                                table.updateData([
                                    {
                                        id: ind + 1,
                                        loc: true
                                    }
                                ]);
                            }
                        });

                        switchCircle(data);
                    },
                    rowDeleted: function (row) {
                        switchCircle(getTableData());
                        // switchCircle(table.getData());
                    }
                });
            }
        });
    }

    function convertData(data) {
        var res = [];
        // var row = data.split('\n');
        var row = data.data;
        $.each(row, function (ind, val) {
            //   ////console.log("tl "+this.length);
            //   ////console.log(this);
            if (this.length != 0) {
                var obj = new Object();
                //var cols = this.split(del);
                obj.stop = moment(
                    this.stop + " " + z,
                    "YYYY-MM-DD hh:mm:ss Z"
                ).format("DD-MM-YYYY HH:mm");
                obj.start = moment(
                    this.start + " " + z,
                    "YYYY-MM-DD hh:mm:ss Z"
                ).format("DD-MM-YYYY HH:mm");
                obj.title = this.title;
                obj.time = this.time;
                obj.showtitle = this.showtitle;
                //    ////console.log(cols[1]);
                //var coord = cols[1].split('/');
                if (this.lat.length && this.lon.length) {
                    obj.lat = this.lat;
                    obj.lon = this.lon;
                    obj.loc = true;
                } else {
                    obj.loc = false;
                    obj.lat = "";
                    obj.lon = "";
                }
                obj.color = this.color;
                obj.transparant = this.transparant;
                obj.radius = this.radius;
                obj.code = this.code;
                obj.showclaim = parseInt(this.showclaim);
                obj.showrequest = parseInt(this.showrequest);
                obj.id = ind + 1;
                obj.pid = this.id;
                res.push(obj);
            }
        });

        return res;
    }

    function createTableData(data) {
        for (let i = 0; i < data.length; i++) {
            let element = data[i];
            let id = i + 1;
            let html = getRowHtml(id, element);

            $("#" + customDataTableId + "  tbody").append(html);

            $(".set-tooltip").tooltip();
        }
    }

    function getRowHtml(id, element) {
        let radius = element.radius ? element.radius : 0;
        let pid = element.pid ? element.pid : -1;
        let showTimer = element.time
            ? getCheckedToggle("timer" + id, "timercheckBox")
            : getunCheckedToggle("timer" + id, "timercheckBox");
        let showtitle = element.showtitle
            ? getCheckedToggle("title" + id, "titlecheckBox")
            : getunCheckedToggle("title" + id, "titlecheckBox");
        let showclaim = element.showclaim
            ? getCheckedToggle("claim" + id, "claimcheckBox")
            : getunCheckedToggle("claim" + id, "claimcheckBox");
        let showrequest = element.showrequest
            ? getCheckedToggle("request" + id, "requestcheckBox")
            : getunCheckedToggle("request" + id, "requestcheckBox");
        // if (showclaim) {

        // }
        if (element.showclaim && (!element.code || element.code == "0")) {
            showclaim += '<i class="fas fa-cog code-error"></i>';
        } else {
            showclaim += '<i class="fas fa-cog"></i>';
        }
        let html = '<tr data-pid="' + pid + '" data-id="' + id + '">';
        html += "<td>" + id + "</td>";
        html += "<td class='track-title'>" + element.title + "</td>";
        html +=
            "<td ><div class='date-field'  ><div class='date-start-value'>" +
            element.start +
            '</div><i class="fas fa-calendar-alt" data-type="start"></i></div></td>';
        html +=
            "<td ><div class='date-field' ><div class='date-stop-value'>" +
            element.stop +
            '</div><i class="fas fa-calendar-alt" data-type="stop"></i></div></td>';
        // html += "<td>" + element.stop + "</td>";
        html +=
            "<td class='track-lat' id='tracklat" +
            id +
            "'>" +
            parseFloat(element.lat).toFixed(8) +
            "</td>";
        html +=
            "<td class='track-lon' id='tracklon" +
            id +
            "'>" +
            parseFloat(element.lon).toFixed(8) +
            "</td>";
        html += "<td class='track-radius'>" + radius + "</td>";
        html += "<td>" + showTimer + "</td>";
        html += "<td>" + showtitle + "</td>";
        html +=
            "<td class='track-claim'><div class='track-claim-group set-tooltip' data-toggle='tooltip' data-placement='bottom' title='" + textClaimSetting + "'>" +
            showclaim +
            "</div></td>";
        // html += "<td class='track-request'>" + showrequest + "</td>";
        html +=
            '<td class="actions">' +
            // '<i class="fas fa-clone"></i>' +
            '<i id="cell-color-' + pid + '" class="fas fa-fill-drip cell-color cell-color-' + id + '" data-pid="' + pid + '" data-id="' + id + '" data-color="' + element.color + '" style="color: ' + element.color + '" data-transparant="' + (element.transparant ? 1 : 0) + '"></i>' +
            '<i class="fas fa-edit"></i>' +
            '<i class="fas fa-copy"></i>' +
            '<i class="fas fa-trash set-tooltip" data-toggle="tooltip" data-placement="bottom" title="' + textRemove + '"></i>' +
            "</td>";
        html += "</tr>";
        return html;
    }

    $("body").on("click", "#" + customDataTableId + " .fa-fill-drip", function () {
        let pid = $(this)
            .parents("tr")
            .data("pid");

        if (pid == -1) {
            let block = createErrorBlock(trans("messages.save_point"));
            $("#form-errors").html(block);
            return;
        }

        let id = $(this).data('pid');
        let color = $(this).data('color');
        transparant = $(this).data('transparant');

        newColor = color;

        $("#modal-color").find('input[name="id"]').val(id);
        $("#modal-color").find('input[name="color"]').val(color);
        $("#modal-color").find('input[name="transparant"]').prop("checked", transparant == 1);

        loadPanelColor();

        $("#modal-color").modal("show");
    });

    function loadPanelColor() {
        $("#modal-color").find("#color-radius").remove();
        if (!transparant) {
            $("#modal-color").find("#note-color").hide();
            $("#modal-color").find(".modal-body").append('<div id="color-radius"></div>');

            $("#modal-color").find('#color-radius').loads({
                color: newColor,
                layout: 'rgbhex',
                enableAlpha: false,
                onChange: function (ev) {
                    newColor = "#" + ev.hexa;
                },
            });
        } else {
            $("#modal-color").find("#note-color").show();
        }
    }

    $("body").on("click", "#modal-color #reset-color", function () {
        newColor = blue.color;
        transparant = false;

        $("#modal-color #transparant").prop("checked", transparant);
        loadPanelColor()
    });

    $("body").on("click", "#modal-color #transparant", function () {
        transparant = $(this).is(":checked");
        loadPanelColor();
    });

    $("body").on("click", "#modal-color #save-modal-color", function () {
        let id = $("#modal-color").find('input[name="id"]').val();
        transparant = $("#modal-color").find('input[name="transparant"]').is(":checked") ? 1 : 0;

        axios
            .post('/admin/point/save_color', {
                id,
                color: newColor,
                transparant,
            })
            .then((response) => {
                $('#cell-color-' + id).css('color', newColor);
                $('#cell-color-' + id).data('color', newColor);
                $('#cell-color-' + id).data('transparant', transparant);

                switchCircle(getTableData());

                $("#modal-color").find('.alert').slideDown();

                setTimeout(() => {
                    $("#modal-color").find('.alert').slideUp();
                }, 2500);
            })
            .catch((error) => console.log(error));
    });

    // Duplicate points
    $("body").on("click", "#" + customDataTableId + " .fa-clone", function () {
        $("#modal-clone-point").modal("show");
    });

    // Clone point
    $("#modal-clone-point").on("click", "#action-duplicates", function () {
        let counfOfDuplicates = $("#count-of-duplicates").val();

        for (let index = 0; index < parseInt(counfOfDuplicates); index++) {
            copyPoint($("#" + customDataTableId + " tbody").find("tr:last"));
        }

        $("#modal-clone-point")
            .find(".form-success")
            .show();

        setTimeout(() => {
            $("#modal-clone-point").modal("hide");
            $("#modal-clone-point")
                .find(".form-success")
                .hide();
        }, 1000);
    });

    $("body").on("click", "#" + customDataTableId + " .fa-copy", function () {
        copyPoint($(this).parents("tr"));
    });

    function copyPoint(tr) {
        var curdata = getTableData();
        // var curdata = table.getData();
        if (curdata.length) {
            var nid = curdata[curdata.length - 1].id + 1;
        } else {
            var nid = 1;
        }
        let id = nid;
        let $r = tr;
        let time = $("#timer" + id).prop("checked") ? 1 : 0;
        let showtitleVal = $("#title" + id).prop("checked") ? 1 : 0;
        let data = {
            id: id,
            lat: $r.find("td:eq(4)").text(),
            lon: $r.find("td:eq(5)").text(),
            radius: $r.find("td:eq(6)").text(),
            showtitle: showtitleVal,
            start: $r.find("td:eq(2)").text(),
            stop: $r.find("td:eq(3)").text(),
            time: time,
            title: $r.find("td:eq(1)").text(),
            color: $r.find(".cell-color").data("color"),
            transparant: $r.find(".cell-color").data("transparant"),
        };
        console.log(data);
        let html = getRowHtml(id, data);
        $("#" + customDataTableId + " tbody").append(html);
        switchCircle(getTableData());
    }

    $("body").on("click", "#" + customDataTableId + " tbody tr", function (e) {
        let id = $(this).data("id");
        let data = createDataJson(id);
        if ($(e.target).closest(".actions").length) {
            return;
        }
        if (circle[id]) {
            selected = data;
            $.each(circle, function (ind, val) {
                if (circle[ind]) {
                    if (circle[ind]._radius) {
                        this.setStyle(styleCircle(ind));
                    }
                }
            });
            if (circle[id]._radius) {
                circle[id].bringToFront();
                circle[id].setStyle(yellow);
            }
            var nrad = 20 - Math.log(data.radius);
            map.setView([data.lat, data.lon], nrad);
        }

        // switchCircle(getTableData());
    });

    function styleCircle(id) {
        let clr = $(".cell-color-" + id).data("color");
        let trp = $(".cell-color-" + id).data("transparant");
        let mainColor = clr.substr(0, 7);
        let opacity = 0.5;

        return {
            original: clr,
            main: mainColor,
            color: clr,
            fillColor: mainColor + (trp ? "00" : ""),
            fillOpacity: opacity,
            transparant: trp == 1,
            text: clr,
        };
    }

    function createDataJson(id) {
        let $r = $("tr[data-id=" + id + "]");
        let pid = $r.data('pid');
        let time = $("#timer" + id).prop("checked") ? 1 : 0;
        let showtitleVal = $("#title" + id).prop("checked") ? 1 : 0;
        // id = parseInt(id) + 1;
        let data = {
            id: id,
            lat: $r.find("td:eq(4)").text(),
            lon: $r.find("td:eq(5)").text(),
            radius: $r.find("td:eq(6)").text(),
            showtitle: showtitleVal,
            start: $r.find("td:eq(2)").text(),
            stop: $r.find("td:eq(3)").text(),
            time: time,
            title: $r.find("td:eq(1)").text(),
            color: $("#cell-color-" + pid).data('color')
        };
        return data;
    }

    function updateMovableMarker(position, nid, table) {
        $(".tabulator-table .tabulator-row:nth-child(" + nid + ")").addClass(
            "bg-highlight"
        );

        $("#" + customDataTableId + " tr[data-id=" + nid + "]").addClass(
            "bg-highlight"
        );

        setTimeout(() => {
            $("#" + customDataTableId + " tr[data-id=" + nid + "]").removeClass(
                "bg-highlight"
            );
            $(
                ".tabulator-table .tabulator-row:nth-child(" + nid + ")"
            ).removeClass("bg-highlight");
        }, 1000);

        // if (0 == 1) {
        // table.updateData([
        //     {
        //         id: nid,
        //         lat: position.lat,
        //         lon: position.lng
        //     }
        // ]);

        // }

        let lat = position.lat.toFixed(8);
        let lng = position.lng.toFixed(8);

        while (lng < -180) {
            lng = parseFloat(lng) + 360;
        }
        while (lng > 180) {
            lng = parseFloat(lng) - 360;
        }

        $("#tracklat" + nid).text(lat);
        $("#tracklon" + nid).text(lng);
        // selected = ;
        // getTableData();
        let data = getTableData();
        // let data = table.getData();
        if (data.length) {
            selected = data.find(item => item.id === nid);
            // console.log(selected);
        }
        switchCircle(getTableData());
    }

    function switchCircle(d, saved = false) {
        // ////console.log('x');
        circle.forEach((item) => {
            map.removeLayer(item);
        });
        infoMarker.forEach((item) => {
            map.removeLayer(item);
        });

        // infoMarker = [];
        if (saved) {
            marker.forEach((item) => {
                map.removeLayer(item);
            });
            marker = [];
        }
        $.each(d, function (ind, val) {
            ////console.log("this.loc",this.loc);
            //to del
            //this.loc = true
            // if (this.lat == '' || this.lon == '') {
            //     this.loc == false;
            // } else {
            //     this.loc == true;
            // }

            //cGroup[ind] = L.layerGroup().addTo(map);
            if (this.loc) {
                if (circle[this.id]) {
                    map.removeLayer(circle[this.id]);
                    $(".my-div-icon-" + ind).remove();
                }
                ////console.log(this.lat, this.lon,this.radius);
                var nid = this.id;
                //
                // if (saved && marker[this.id]) {
                //     console.log("saved", saved, marker, marker[this.id], this.id);
                //     map.removeLayer(marker[this.id]);
                //     delete marker[this.id];
                // }
                if (this.radius <= 1 && !(marker.length && marker[this.id])) {
                    circle[this.id] = L.marker([this.lat, this.lon], {
                        draggable: "true",
                        autoPan: true,
                        myid: this.id
                    }).addTo(map);
                    circle[this.id].on("dragend", function (event) {
                        var markerNew = event.target;
                        var position = markerNew.getLatLng();

                        updateMovableMarker(position, nid, table);
                    });
                } else {
                    circle[this.id] = L.circle([this.lat, this.lon], {
                        radius: this.radius,
                        myid: this.id,
                        draggable: "true"
                    }).addTo(map);

                    circle[this.id].setStyle(styleCircle(this.id));

                    circle[this.id].on("dragend", function (e) {
                        let latlng = e.target.getLatLng();
                        circle[nid].setLatLng(latlng);
                        if (marker.length && marker[nid]) {
                            map.removeLayer(marker[nid]);
                            marker[nid] = L.marker([latlng.lat, latlng.lng], {
                                icon: redIcon,
                                draggable: "true",
                                autoPan: true,
                                myid: nid
                            }).addTo(map);

                            marker[nid].on("dragend", function (event) {
                                var markerNew = event.target;
                                var position = markerNew.getLatLng();
                                updateMovableMarker(position, nid, table);
                            });
                        }
                        updateMovableMarker(latlng, nid, table);
                        map.dragging.enable();
                    });
                    // circle[this.id].on({
                    //     mousedown: function() {
                    //         map.dragging.disable();
                    //         map.on("mousemove", function(e) {
                    // circle[nid].setLatLng(e.latlng);
                    // if (marker.length && marker[nid]){
                    //     map.removeLayer(marker[nid]);
                    //      marker[nid] = L.marker(
                    //          [e.latlng.lat, e.latlng.lng],
                    //          {
                    //              icon: redIcon,
                    //              draggable: "true",
                    //              autoPan: true,
                    //              myid: nid
                    //          }
                    //      ).addTo(map);

                    //      marker[nid].on("dragend", function(event) {
                    //          var markerNew = event.target;
                    //          var position = markerNew.getLatLng();
                    //          updateMovableMarker(
                    //              position,
                    //              nid,
                    //              table
                    //          );
                    //      });
                    // }
                    // updateMovableMarker(e.latlng, nid, table);
                    //         });
                    //     }
                    // });
                    // map.on("mouseup", function(e) {
                    //     map.dragging.enable();
                    //     map.removeEventListener("mousemove");
                    // });
                }
                circle[this.id].bindPopup(
                    "Rij: " +
                    this.id +
                    "<br/>Title:" +
                    this.title +
                    "<br/>Start: " +
                    moment(
                        this.start + " " + z,
                        "DD-MM-YYYY HH:mm Z"
                    ).toDate() +
                    "<br/>Stop: " +
                    moment(
                        this.stop + " " + z,
                        "DD-MM-YYYY HH:mm Z"
                    ).toDate() +
                    "<br/> Lat: " +
                    this.lat +
                    "<br/> Lon: " +
                    this.lon +
                    "<br/> Radius: " +
                    this.radius +
                    " meters"
                );
                //circle[this.id].bindPopup("Rij: " + this.id + "<br/>Title:" + this.title + "<br/>Start: " + moment(this.start + " "+z, "YYYY-MM-DD hh:mm:ss Z").toDate() +"<br/>Stop: " + moment(this.stop + " "+z, "YYYY-MM-DD hh:mm:ss Z").toDate() +  "<br/> Lat: " + this.lat + "<br/> Lon: " + this.lon + "<br/> Radius: " + this.radius + " meters");

                //   circle[ind].on('mouseover', function (e) {
                //      this.bringToFront();
                //  });
                //  circle[ind].on('mouseout', function (e) {
                //     this.bringToFront();
                //  });

                circle[this.id].on("click", function (e) {
                    this.openPopup();
                    // ////console.log(e);
                    //  ////console.log(this);
                    // table.selectRow(this.options.myid);
                    //  ////console.log(this.options.myid);
                    //  ////console.log(selected.id);
                    if (befselected == this.options.myid) {
                        // this.bringToBack();
                    }
                    befselected = this.options.myid;
                });

                // circle[this.id].on('dblclick', function (e) {

                //     map.removeLayer(this);
                //     return false;
                // });

                var myIcon = L.divIcon({ className: "my-div-icon-" + ind });
                var pstop = moment(
                    this.stop + " " + z,
                    "DD-MM-YYYY HH:mm Z"
                ).isAfter();
                var sstop = moment(
                    this.stop + " " + z,
                    "DD-MM-YYYY HH:mm"
                ).format("YYYY-MM-DD HH:mm:ss");
                if (this.time && pstop) {
                    infoMarker[this.id] = L.marker([this.lat, this.lon], {
                        draggable: "true",
                        autoPan: true,
                        myid: this.id,
                        icon: myIcon
                    }).addTo(map);

                    infoMarker[this.id].on("dragend", function (e) {
                        let latlng = e.target.getLatLng();
                        infoMarker[nid].setLatLng(latlng);
                        if (marker.length && marker[nid]) {
                            map.removeLayer(marker[nid]);
                            marker[nid] = L.marker([latlng.lat, latlng.lng], {
                                icon: redIcon,
                                draggable: "true",
                                autoPan: true,
                                myid: nid
                            }).addTo(map);

                            marker[nid].on("dragend", function (event) {
                                var markerNew = event.target;
                                var position = markerNew.getLatLng();
                                updateMovableMarker(position, nid, table);
                            });
                        }
                        updateMovableMarker(latlng, nid, table);
                        map.dragging.enable();
                    });

                    // console.log(
                    //     "add timer",
                    //     this.stop,
                    //     moment(
                    //         this.stop + " " + z,
                    //         "DD-MM-YYYY HH:mm Z"
                    //     ).isAfter()
                    // );
                    let title = this.showtitle ? this.title : "";
                    $(".my-div-icon-" + ind).addClass("my-div-icon");
                    $(".my-div-icon-" + ind)
                        .countdown(sstop)
                        // .countdown(sstop)
                        .on("update.countdown", function (event) {
                            var totalHours =
                                event.offset.totalDays * 24 +
                                event.offset.hours;
                            let html = event.strftime(totalHours + ":%M:%S");
                            if (title) {
                                html += "<br>" + title;
                            }
                            let resultClr = styleCircle(ind + 1);
                            $(this).html(html);
                            $(".my-div-icon-" + ind).css("color", resultClr.text);
                            $(".my-div-icon-" + ind).css("width", "unset");
                            $(".my-div-icon-" + ind).css("height", "unset");
                            $(".my-div-icon-" + ind).css("z-index", 9);
                            $(".my-div-icon-" + ind).css("z-index", 9);
                            $(".my-div-icon-" + ind).css(
                                "text-align",
                                "center"
                            );

                            $(".my-div-icon-" + ind).css(
                                "margin-left",
                                -($(".my-div-icon-" + ind).width() / 2)
                            );
                            $(".my-div-icon-" + ind).css(
                                "margin-top",
                                -($(".my-div-icon-" + ind).height() / 2)
                            );
                        })
                        .on("finish.countdown", function (event) {
                            switchCircle(getTableData());
                        });
                } else if (this.showtitle) {
                    infoMarker[this.id] = L.marker([this.lat, this.lon], {
                        draggable: "true",
                        autoPan: true,
                        myid: this.id,
                        icon: myIcon
                    }).addTo(map);
                    infoMarker[this.id].on("dragend", function (e) {
                        let latlng = e.target.getLatLng();
                        infoMarker[nid].setLatLng(latlng);
                        if (marker.length && marker[nid]) {
                            map.removeLayer(marker[nid]);
                            marker[nid] = L.marker([latlng.lat, latlng.lng], {
                                icon: redIcon,
                                draggable: "true",
                                autoPan: true,
                                myid: nid
                            }).addTo(map);

                            marker[nid].on("dragend", function (event) {
                                var markerNew = event.target;
                                var position = markerNew.getLatLng();
                                updateMovableMarker(position, nid, table);
                            });
                        }
                        updateMovableMarker(latlng, nid, table);
                        map.dragging.enable();
                    });

                    let resultClr = styleCircle(ind + 1);

                    $(".my-div-icon-" + ind).addClass("my-div-icon");
                    $(".my-div-icon-" + ind).html(
                        "<div class='my-div-icon-title'>" +
                        this.title +
                        "</div>"
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
                // console.log("hop");
            }
        });
        if (!selected) {
            selected = d[0];
        }
        // console.log("lll",selected);
        if (selected) {
            map.panTo(new L.LatLng(selected.lat, selected.lon));
            var nrad = 20 - Math.log(selected.radius);

            map.setView([selected.lat, selected.lon], nrad);
        }
        if (cGroup) {
            // ////console.log('removing circle');
            //map.removeLayer(cGroup);
        }
    }
    $("#saveTrackData").click(function () {
        let lon = $("#editFormModal #track-lon").val();
        $("#editFormModal #error-long").hide();

        if (parseFloat(lon) < -180 || parseFloat(lon) > 180) {
            $("#editFormModal #error-long").show();
        } else {
            let title = $("#editFormModal #track-title").val();
            let lat = $("#editFormModal #track-lat").val();
            let radius = $("#editFormModal #track-radius").val();
            radius = radius ? radius : 0;
            let id = $("#editFormModal #track-id").val();
            let $parentRow = $("tr[data-id=" + id + "]");
            $parentRow.find(".track-title").text(title);
            $parentRow.find(".track-lat").text(lat);
            $parentRow.find(".track-lon").text(lon);
            $parentRow.find(".track-radius").text(radius);
            switchCircle(getTableData());
            $("#editFormModal").modal("hide");

            map.panTo(new L.LatLng(lat, lon));
            $("#data-table-custom").find('tr[data-pid="' + id + '"]').trigger('click');
        }
    });
    $("body").on("click", "#" + customDataTableId + " .fa-trash", function () {
        let id = $(this)
            .parents("tr")
            .data("id");
        if (marker[id]) {
            map.removeLayer(marker[id]);
        }
        $(this)
            .parents("tr")
            .remove();
        switchCircle(getTableData());
    });
    $("#addTrackData").click(function () {
        $(".add-title-error, #AddFormModal #error-long").hide();

        let lon = $("#AddFormModal #track-lon").val();

        if (parseFloat(lon) < -180 || parseFloat(lon) > 180) {
            $("#AddFormModal #error-long").show();
        } else {
            let title = $("#AddFormModal #track-title").val();
            let lat = $("#AddFormModal #track-lat").val();
            let lon = $("#AddFormModal #track-lon").val();
            let radius = $("#AddFormModal #track-radius").val();
            radius = radius ? radius : 0;
            let id = $("#AddFormModal #track-id").val();
            if (!title) {
                $(".add-title-error").show();

                // let block = createErrorBlock(
                //     trans("messages.title_required"),
                //     "danger"
                // );
                // $("#add-form-errors").html(block);
                return;
            }

            marker[id] = L.marker([lat, lon], {
                icon: redIcon,
                draggable: "true",
                autoPan: true,
                myid: id
            }).addTo(map);

            marker[id].on("dragend", function (event) {
                var markerNew = event.target;
                var position = markerNew.getLatLng();
                updateMovableMarker(position, id, table);
            });
            let data = {
                id: id,
                loc: false,
                lat: lat,
                lon: lon,
                // lat: "52.144681",
                // lon: "6.394280",
                radius: radius,
                title: title,
                start: moment(new Date()).format("DD-MM-YYYY HH:mm"),
                stop: moment(new Date()).add(1, 'hours').format("DD-MM-YYYY HH:mm"),
                time: false,
                showtitle: false,
                color: blue.color,
                transparant: false
            };
            let html = getRowHtml(id, data);
            $("#" + customDataTableId + "  tbody").append(html);

            // let $parentRow = $("tr[data-id=" + id + "]");
            // $parentRow.find(".track-title").text(title);
            // $parentRow.find(".track-lat").text(lat);
            // $parentRow.find(".track-lon").text(lon);
            // $parentRow.find(".track-radius").text(radius);
            // switchCircle(getTableData());
            $("#AddFormModal").modal("hide");
        }
    });

    function getTableData() {
        var data = [];
        var target = $("#" + customDataTableId + " tr").not("thead tr");
        //table.find('tr').each(function (rowIndex, r) {
        target.each(function (rowIndex, r) {
            var col = {};
            let id = $(r).data("id");
            let pid = $(r).data("pid");
            let lat = $(r)
                .find("td:eq(4)")
                .text();
            let lon = $(r)
                .find("td:eq(5)")
                .text();
            col.id = id;
            col.pid = pid;
            col.title = $(r)
                .find("td:eq(1)")
                .text();
            col.start = $(r)
                .find("td:eq(2)")
                .text();
            col.stop = $(r)
                .find("td:eq(3)")
                .text();
            col.lat = lat;
            col.lon = lon;
            col.loc = lat && lon ? true : false;
            col.radius = parseInt(
                $(r)
                    .find("td:eq(6)")
                    .text()
            );
            col.time = $("#timer" + id).prop("checked") ? 1 : 0;
            col.showtitle = $("#title" + id).prop("checked") ? 1 : 0;
            col.showclaim = $("#claim" + id).prop("checked") ? 1 : 0;
            col.color = $("#cell-color-" + pid).data('color');
            col.transparant = $("#cell-color-" + pid).data('transparant');
            // col.showrequest = $("#request" + i÷d).prop("checked") ? 1 : 0;
            data.push(col);
        });
        return data;
    }
    $(".remove-user").on("click", function (e) {
        e.preventDefault();
        event.stopPropagation();

        if (
            confirm(
                trans("basic.delete_user_cnf") +
                " #" +
                $(this).attr("data-uid") +
                " " +
                $(this).attr("data-email") +
                "?"
            )
        ) {
            window.location = "/admin/removeu/" + $(this).attr("data-uid");
        } else {
            // Do nothing!
        }
    });
    $(".duplicate-track").on("click", function (e) {
        e.preventDefault();
        event.stopPropagation();

        let moveid = $(this).data("id");
        $("#modal-duplicate-track")
            .find('input[name="moveid"]')
            .val(moveid);

        $("#modal-duplicate-track").modal("show");
    });
    $("#action-duplicate-track").on("click", function (e) {
        let formData = $("#form-duplicate-track").serialize();
        let counfOfDuplicates = $("#form-duplicate-track").find('input[name="number"]').val();

        $("#modal-duplicate-track")
            .find(".alert")
            .hide();

        if (parseInt(counfOfDuplicates) < 1) {
            $("#modal-duplicate-track")
                .find(".form-error")
                .show();
        } else {
            NProgress.start();
            axios
                .post("/admin/track/duplicate", formData)
                .then(function (response) {
                    $("#modal-duplicate-track")
                        .find(".form-success")
                        .show();

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .then(function () {
                    NProgress.done();
                });
        }
    });
    $(".remove-track").on("click", function (e) {
        e.preventDefault();
        event.stopPropagation();

        if (
            confirm(
                trans("basic.delete_track_cnf") +
                " #" +
                $(this).attr("data-tid") +
                " " +
                $(this).attr("data-title") +
                "?"
            )
        ) {
            window.location = "/admin/removetrack/" + $(this).attr("data-tid");
        } else {
            // Do nothing!
        }
    });
    $(".edit-track,.edit-tracktitle").on("click", function (e) {
        e.preventDefault();
        event.stopPropagation();

        window.location = $(this).attr("href");
    });
    $(".tracktitle").on("click", function () {
        //  alert($(this).attr('id').replace("track","trackc"));
        $(
            "#" +
            $(this)
                .attr("id")
                .replace("track", "trackc")
        ).toggle();
        var id = $(this)
            .attr("id")
            .split("-");
        fetchtrack(id[1]);
    });
    $("body").on("change", ".timercheckBox", function () {
        let data = getTableData();
        switchCircle(data);
    });
    $("body").on("change", ".titlecheckBox", function () {
        let data = getTableData();
        switchCircle(data);
    });
});

function fetchtrack(id) {
    ////console.log("loading");
    var fetch = $.ajax({
        url: "/admin/getpoints/" + id,
        type: "get",

        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },

        success: function (data) {
            ////console.log(data.data)
            ////console.log("putting in #trackc-"+id);
            if (!data.data.length) {
                ////console.log("no");
                $("#trackc-" + id).html(trans("basic.no_points"));
            } else {
                ////console.log("yes");
                $("#trackc-" + id).html("");
                $("#trackc-" + id).append(pointstable(data.data));
                // $.each(data.data, function (ind, val) {
                //     $('#trackc-'+id).append(pointstable(val));
                // })
            }
        }
    });
}

var dateEditor = function (cell, onRendered, success, cancel, editorParams) {
    var cellValue = cell.getValue(),
        input = document.createElement("input");
    //console.log(cell);
    input.setAttribute("type", "text");

    input.style.padding = "4px";
    input.style.width = "100%";
    input.style.boxSizing = "border-box";

    input.value = typeof cellValue !== "undefined" ? cellValue : "";

    onRendered(function () {
        input.style.height = "100%";
        // //console.log("ggg");

        // $(input).attr('data-target','#datetimepicker5');
        // $(input).attr('id','datetimepicker5');
        // $(input).attr('data-toggle','datetimepicker');
        // $(input).addClass('datetimepicker-input');
        // $(input).addClass('form-control');
        // $('#datetimepicker5').datetimepicker(); //turn input into datepicker
        // //console.log( $('#datetimepicker5'));
        // input.focus();

        $("#myModal").modal("show");

        $("#datetimepicker13").on("change.datetimepicker", function (e) {
            ////console.log("hhh",e.date);
            cell.setValue(e.date.format("DD-MM-YYYY HH:mm"));
            // $("#" + customDataTableId + " tr[data-id=" + nid + "]");

            // success(e.date.format("DD-MM-YYYY HH:mm"));
            //$('#myModal').modal('hide');
        });
    });

    function onChange(e) {
        if (
            ((cellValue === null || typeof cellValue === "undefined") &&
                input.value !== "") ||
            input.value != cellValue
        ) {
            success(input.value);
        } else {
            cancel();
        }
    }

    //submit new value on blur or change
    // input.addEventListener("change", onChange);
    // input.addEventListener("blur", onChange);

    // //submit new value on enter
    // input.addEventListener("keydown", function(e){
    //     switch(e.keyCode){
    //         case 13:
    //         success(input.value);
    //         break;

    //         case 27:
    //         cancel();
    //         break;
    //     }
    // });

    return input;
};
$(function () {
    $("#datetimepicker13").datetimepicker({
        inline: true,
        sideBySide: true,
        format: "DD-MM-YYYY HH:mm"
    });
});

function pointstable(points) {
    var res = `<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Title</th>
      <th scope="col">Start</th>
      <th scope="col">Stop</th>
      <th scope="col">Lat</th>
      <th scope="col">Lon</th>
      <th scope="col">Radius</th>
      <th scope="col">Timer</th>
      <th scope="col">Show Title</th>
      <th scope="col">Claimable</th>
    </tr>
  </thead>
  <tbody>`;

    $.each(points, function (index, value) {
        res +=
            `<tr>
      <th scope="row">` +
            (index + 1) +
            `</th>
      <td>` +
            value.title +
            `</td>
      <td>` +
            value.start +
            `</td>
      <td>` +
            value.stop +
            `</td>
      <td>` +
            value.lat +
            `</td>
      <td>` +
            value.lon +
            `</td>
      <td>` +
            value.radius +
            `</td>
      <td>` +
            value.time +
            `</td>
            <td>` +
            value.showtitle +
            `</td>
              <td>` +
            value.showclaim +
            `</td>

    </tr>`;
    });
    res += `</tbody>
</table>`;
    return res;
}

$(document).ready(function () {
    $("#copy-button").tooltip();

    $("#copy-button").on("click", function () {
        var input = document.querySelector("#copy-input");
        input.focus();
        input.setSelectionRange(0, input.value.length + 1);
        try {
            var success = document.execCommand("copy");
            if (success) {
                $("#copy-button").trigger("copied", ["Copied!"]);
            } else {
                $("#copy-button").trigger("copied", ["Use Ctrl+C"]);
            }
        } catch (err) {
            //console.log(err);
            $("#copy-button").trigger("copied", ["Use Ctrl+C"]);
        }
    });

    $("#copy-button").on("copied", function (event, message) {
        $(this)
            .attr("title", message)
            .tooltip("_fixTitle")
            .tooltip("show")
            .attr("title", "Copy")
            .tooltip("_fixTitle");
    });

    $("body").on("click", "#" + customDataTableId + " .fa-edit", function () {
        let id = $(this)
            .parents("tr")
            .data("id");
        let $parentRow = $(this).parents("tr");
        let title = $parentRow.find(".track-title").text();
        let lat = $parentRow.find(".track-lat").text();
        let lon = $parentRow.find(".track-lon").text();
        let radius = $parentRow.find(".track-radius").text();
        $("#editFormModal #track-title").val(title);
        $("#editFormModal #track-lat").val(lat);
        $("#editFormModal #track-lon").val(lon);
        $("#editFormModal #track-radius").val(radius);
        $("#editFormModal #track-id").val(id);
        $("#editFormModal").modal("show");
    });
    $("body").on("click", "#" + customDataTableId + " .fa-cog", function () {
        $("#claimFormModal #form-errors").html("");

        let id = $(this)
            .parents("tr")
            .data("id");
        let pid = $(this)
            .parents("tr")
            .data("pid");
        if (pid == -1) {
            let block = createErrorBlock(trans("messages.save_point"));
            $("#form-errors").html(block);
            return;
        }
        $("#claimFormModal .loader").show();
        $("#claimFormModal").modal("show");
        $.ajax({
            type: "GET",
            url: "/admin/get-claim-setting/" + pid,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        })
            .done(function (response) {
                $("#title-claim-setting").html($("#title-claim-setting").data('title') + ": " + response.title);

                $("#claimFormModal #track-id").val(id);
                $("#claimFormModal #point-id").val(pid);

                $("#claimFormModal .loader").hide();
                var target = $("#" + customDataTableId + " tr").not("thead tr");
                let options = "<option value=''>" + textNone + "</option>";
                target.each(function (rowIndex, r) {
                    let rid = $(r).data("pid");
                    let title = $(r)
                        .find("td:eq(1)")
                        .text();
                    // if (rid == id) {
                    //     return;
                    // }
                    options +=
                        "<option value=" + rid + ">" + title + "</option>";
                    $("#claim-next-point").html(options);
                });
                $("#claimFormModal #claim-code").val(response.code);
                $("#claimFormModal #claim-question").val(
                    response.remarks_questions
                );

                let upload_photo = response.upload_photo == 1 ? true : false;
                $("#claimFormModal #upload_photo").prop("checked", upload_photo);

                let remarkCheck = response.showrequest == 1 ? true : false;
                $("#claimFormModal #remarks").prop("checked", remarkCheck);

                if (remarkCheck)
                    $("#container-remarks").show();
                else
                    $("#container-remarks").hide();

                let requestToken = response.request_token == 1 ? true : false;
                $("#claimFormModal #request_token").prop("checked", requestToken);

                if (requestToken)
                    $("#container-token").show();
                else
                    $("#container-token").hide();


                // $("#claimFormModal #claim-next-point")
                //     .val(response.next_point)
                //     .change();
                if (response.next_point) {
                    $.each((response.next_point + "").split(","), function (i, e) {
                        console.log(e);
                        $(
                            "#claimFormModal #claim-next-point option[value='" +
                            e +
                            "']"
                        ).prop("selected", true);
                    });
                }
            })
            .fail(function (jqXHR, textStatus) {
                $("#claimFormModal .loader").hide();
                console.log(textStatus);

                // let block = createErrorBlock(
                //     trans("messages.server_error")
                // );
                // $("#form-errors").html(block);
                // // alert("error " + textStatus);
            });

        // let $parentRow = $(this).parents("tr");
    });
    $("body").on("change", "#remarks", () => {
        let isChecked = $("#remarks").is(":checked");

        if (isChecked)
            $("#container-remarks").slideDown();
        else
            $("#container-remarks").slideUp();
    });
    $("body").on("change", "#request_token", () => {
        let isChecked = $("#request_token").is(":checked");

        if (isChecked)
            $("#container-token").slideDown();
        else
            $("#container-token").slideUp();
    });
    $("body").on("click", "#saveClaimData", function () {
        $("#claimFormModal #form-errors").html("");
        $("#claimFormModal .claim-code-error").hide();
        $("#claimFormModal .claim-followUp-error").hide();
        let id = $("#claimFormModal #track-id").val();
        let pid = $("#claimFormModal #point-id").val();
        let code = $("#claimFormModal #claim-code").val();
        let next_point = $("#claimFormModal #claim-next-point")
            .val()
            .join();
        let remarks = $("#claimFormModal #remarks").prop("checked") ? 1 : 0;
        let upload_photo = $("#claimFormModal #upload_photo").prop("checked") ? 1 : 0;
        let request_token = $("#claimFormModal #request_token").prop("checked") ? 1 : 0;
        let remarks_questions = $("#claimFormModal #claim-question").val();
        if (!code && request_token == 1) {
            $("#claimFormModal .claim-code-error").show();
        }
        // if (!next_point) {
        //     $("#claimFormModal .claim-followUp-error").show();
        // }

        if (request_token == 0 || (request_token == 1 && code)) {
            $.ajax({
                type: "POST",
                url: "/admin/save-claim-setting/" + pid,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    code,
                    next_point,
                    remarks,
                    upload_photo,
                    request_token,
                    remarks_questions
                }
            })
                .done(function (response) {
                    if (response.status == 1) {
                        $("tr[data-pid=" + pid + "] .fa-cog").removeClass(
                            "code-error"
                        );
                        let block = createErrorBlock(
                            trans("messages.saved_success"),
                            "success"
                        );
                        $("#claimFormModal #form-errors").html(block);
                    }
                    console.log(response);
                })
                .fail(function (jqXHR, textStatus) {
                    console.log(textStatus);

                    // let block = createErrorBlock(
                    //     trans("messages.server_error")
                    // );
                    // $("#form-errors").html(block);
                    // // alert("error " + textStatus);
                });
        }
    });

    $("body").on(
        "click",
        "#" + customDataTableId + " .fa-calendar-alt",
        function () {
            let type = $(this).data("type");
            let id = $(this)
                .parents("tr")
                .data("id");
            console.log(type, id);
            updateDateTime(type, id);
        }
    );

    $("#form-game-settting").on("click", "#hide-menu-bar", () => {
        let isChecked = $("#hide-menu-bar").is(":checked");
        let trackID = $("#form-game-settting").find('input[name="id"]').val();

        NProgress.start();
        axios
            .post("/admin/track/hide-menu-bar", {
                is_checked: isChecked,
                track_id: trackID
            })
            .then((response) => {
                $("#alert-success").slideDown();

                setTimeout(() => {
                    $("#alert-success").slideUp();
                }, 2500);
            })
            .catch((error) => console.log(error))
            .then(() => NProgress.done());
    })

    $("#form-game-settting").on("click", "#hide-menu-bar", () => {
        let isChecked = $("#hide-menu-bar").is(":checked");
        let trackID = $("#form-game-settting").find('input[name="id"]').val();

        NProgress.start();
        axios
            .post("/admin/track/hide-menu-bar", {
                is_checked: isChecked,
                track_id: trackID
            })
            .then((response) => {
                $("#alert-success").slideDown();

                setTimeout(() => {
                    $("#alert-success").slideUp();
                }, 2500);
            })
            .catch((error) => console.log(error))
            .then(() => NProgress.done());
    })

    $("#form-show_log_public").on("click", "#show_log_public", () => {
        let isChecked = $("#show_log_public").is(":checked");
        let trackID = $("#form-show_log_public").find('input[name="id"]').val();

        if (isChecked) $("#link-log-public").show();
        else $("#link-log-public").hide();

        NProgress.start();
        axios
            .post("/admin/track/show_log_public", {
                is_checked: isChecked,
                track_id: trackID
            })
            .then((response) => {
                $("#alert-success").slideDown();

                setTimeout(() => {
                    $("#alert-success").slideUp();
                }, 2500);
            })
            .catch((error) => console.log(error))
            .then(() => NProgress.done());
    })
});

function updateDateTime(type, id) {
    $("#myModal").modal("show");

    $("#datetimepicker13").on("change.datetimepicker", function (e) {
        console.log(e.date.format("DD-MM-YYYY HH:mm"));
        $(
            "#" +
            customDataTableId +
            " tr[data-id=" +
            id +
            "] .date-" +
            type +
            "-value"
        ).text(e.date.format("DD-MM-YYYY HH:mm"));
    });
}

function createErrorBlock(message, type = "danger") {
    let html =
        '<div class="alert alert-' +
        type +
        ' alert-dismissible fade show" role="alert">' +
        message +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        "</button>" +
        "</div>";
    return html;
}
$("#myModal").on("hide.bs.modal", function () {
    $("#datetimepicker13").off("change.datetimepicker");
});
