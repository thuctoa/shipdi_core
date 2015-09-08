<?php
use yii\helpers\Url;
use app\models\Location;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
Tổng số lượng đơn là: <?= count($arraylocation)?> đem chia cho <?=$sokhuvuc?> bạn ship là<br>
<?php
    for($i=0;$i<$sokhuvuc;$i++){
?>

Số lượng đơn của khu vực: <?=$i?> là: 
<?=  count($array[$i])?> đơn và tổng khoảng cách là: <?= $tongkhoangcach[$i]?>
 Thời gian dự tính là: <?= $tongkhoangcach[$i]/0.025?> giờ
<br>
<?php
    }
?>
<div class="site-index">
    <style>
        #panel {
          position: absolute;
          top: 10px;
          left: 50px;
          z-index: 5;
          background-color: #fff;
          padding: 5px;
          border: 1px solid #999;
          text-align: center;
        }

        /**
         * Provide the following styles for both ID and class, where ID represents an
         * actual existing "panel" with JS bound to its name, and the class is just
         * non-map content that may already have a different ID with JS bound to its
         * name.
         */

        #panel, .panel {
          font-family: 'Roboto','sans-serif';
          line-height: 30px;
          padding-left: 10px;
        }

        #panel select, #panel input, .panel select, .panel input {
          font-size: 15px;
        }

        #panel select, .panel select {
          width: 100%;
        }

        #panel i, .panel i {
          font-size: 12px;
        }

    </style>
<!--<div id="panel">
        <input onclick="clearMarkers();" type=button value="Hide Markers">
        <input onclick="showMarkers();" type=button value="Show All Markers">
        <input onclick="deleteMarkers();" type=button value="Delete Markers">
    </div>-->
    <div id="map" style="float:left;width:800px;height:600px;"></div>
        <div id="control_panel" style="width:20%;float:left;text-align:left;padding-top:20px" class="panel">
        <div style="margin:20px;border-width:2px;" class="panel">
        <b>Start:</b>
        <select id="start">
            <option value="179 Vinh hung, ha noi">Vinh hung</option>
            <option value="Boston, MA">Boston, MA</option>
            <option value="New York, NY">New York, NY</option>
            <option value="Miami, FL">Miami, FL</option>
        </select>
        <br>
        <b>Waypoints:</b> <br>
        <i>(Ctrl-Click for multiple selection)</i> <br>
        <select multiple id="waypoints">
            <option value="43 kim giang , ha noi">Chua lang</option>
            <option value="86 lac hong, ha noi">86 lac hong</option>
            <option value="chicago, il">Chicago</option>
            <option value="winnipeg, mb">Winnipeg</option>
            <option value="fargo, nd">Fargo</option>
            <option value="calgary, ab">Calgary</option>
            <option value="spokane, wa">Spokane</option>
        </select>
        <br>
        <b>End:</b>
        <select id="end">
            <option value="56 Ba dinh, ha noi">Ba dinh</option>
            <option value="Seattle, WA">Seattle, WA</option>
            <option value="San Francisco, CA">San Francisco, CA</option>
            <option value="Los Angeles, CA">Los Angeles, CA</option>
        </select>
        <br>
            <input type="submit" id="submit">
        </div>
        <div id="directions_panel" class="panel" style="margin:20px;background-color:#FFEE77;"></div>
        <script>
            var arraycolor=['Red','Blue','Green','Brown','Fuchsia','DarkViolet','Tomato','MidnightBlue'];
            var map;
            var markers = [];
            var khuvuc=[];
            var diemdau =({
                        lat:<?= $tam[0]['x']?>,lng:<?= $tam[0]['y']?>
                    });
            var diemcuoi=({
                        lat:'20.9930851',lng:'105.8259845'
                    });        
            function initMap() {
                var directionsService = new google.maps.DirectionsService;
                var directionsDisplay = new google.maps.DirectionsRenderer;
                map = new google.maps.Map(document.getElementById('map'), {
                  zoom: 13,
                  center: {lat: 21.00331240148827, lng: 105.82194328308105}
                });
                directionsDisplay.setMap(map);

                document.getElementById('submit').addEventListener('click', function() {
                    calculateAndDisplayRoute(directionsService, directionsDisplay);
                    var str;
                    for(var i=0;i<khuvuc.length;i++){
                        str='x='+khuvuc[i].location.G+'&y='+khuvuc[i].location.K;
                        insertlocation(str);
                    }
                    deleteMarkers();
                    khuvuc=[];
                });

                // This event listener will call addMarker() when the map is clicked.
                map.addListener('click', function(event) {
                    addMarker(event.latLng);
                    console.log(diemdau);
                    khuvuc.push({
                        location: event.latLng,
                        stopover: true
                    });
                });
                var array=[];
                <?php
                    for ($i=0;$i<$sokhuvuc;$i++){
                ?>
                        array[<?=$i?>]=[];
                        
                <?php
                //thuc hien gan mang con 
                        foreach ($array[$i] as $value){
                ?>
                            array[<?=$i?>].push ({
                                lat:<?= $value['x']?>,
                                lng:<?= $value['y']?>
                            });   
                <?php
                        }
                    }
                ?>
                <?php
                //ve khu vuc tren ban do
                    for($i=0;$i<$sokhuvuc;$i++){
                ?>
                        var color=arraycolor[<?=$i?>%(arraycolor.length+1)];
                        // Construct the polygon.
                        var bermudaTriangle = new google.maps.Polygon({
                          paths: array[<?=$i?>],
                          strokeColor: color,
                          strokeOpacity: 0.8,
                          strokeWeight: 3,
                          fillColor: color,
                          fillOpacity: 0.35
                        });
                        bermudaTriangle.setMap(map);
                <?php
                    }
                ?>
               
                

            }
            // Adds a marker to the map and push to the array.
            function addMarker(location) {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
                markers.push(marker);
            }

            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

            // Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                setMapOnAll(null);
            }

            // Shows any markers currently in the array.
            function showMarkers() {
                setMapOnAll(map);
            }

            // Deletes all markers in the array by removing references to them.
            function deleteMarkers() {
                clearMarkers();
                markers = [];
            }

            function calculateAndDisplayRoute(directionsService, directionsDisplay) {
                var waypts = [];
                var checkboxArray = document.getElementById('waypoints');
                for (var i = 0; i < checkboxArray.length; i++) {

                if (checkboxArray.options[i].selected) {
                     console.log( checkboxArray[i].value);
                  waypts.push({
                    location: checkboxArray[i].value,
                    stopover: true
                  });
                }
              }

              directionsService.route({
                // origin: document.getElementById('start').value,
                origin: diemdau,
                //destination: document.getElementById('end').value,
                destination: diemcuoi,
                waypoints: khuvuc,
                optimizeWaypoints: true,
                travelMode: google.maps.TravelMode.DRIVING
              }, function(response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                  directionsDisplay.setDirections(response);
                  var route = response.routes[0];
                  var summaryPanel = document.getElementById('directions_panel');
                  summaryPanel.innerHTML = '';
                  // For each route, display summary information.
                  for (var i = 0; i < route.legs.length; i++) {
                    var routeSegment = i + 1;
                    summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                        '</b><br>';
                    summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
                    summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
                    summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
                  }
                } else {
                  window.alert('Directions request failed due to ' + status);
                }
              });
            }
            function insertlocation(str) {
                if (str=="") {
                      return;
                } 
                if (window.XMLHttpRequest) {
                      // code for IE7+, Firefox, Chrome, Opera, Safari
                      xmlhttp=new XMLHttpRequest();
                } else { // code for IE6, IE5
                      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function() {
                      if (xmlhttp.readyState==4 && xmlhttp.status==200) {

                      }
                }
                xmlhttp.open("GET","location/create?"+str,true);
                xmlhttp.send();
            }
        </script>
        <script  src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap" async defer></script>
    </div>
</div>