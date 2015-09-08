<?php
    /* @var $this yii\web\View */
    $this->title = 'Create location';
    use dosamigos\datepicker\DatePicker;
    use app\models\Location;
    use app\controllers\SiteController;
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #map {
                height: 100%;
            }
            #panel {
                //position: absolute;
               // top: 0px;
                //left: 15%;
                z-index: 5;
                background-color: #fff;
                padding: 5px;
                border: 1px solid #999;
                text-align: center;
                width: 430px;
                float: right;
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
                //width: 100%;
            }

            #panel i, .panel i {
                font-size: 12px;
            }
            
        </style>
    </head>
    <body>
        <div id="map" style="float:left;width:700px;height:500px;"></div>
        <div id="panel">
<!--            <input onclick="clearMarkers();" type=button value="Hide Markers">
            <input onclick="showMarkers();" type=button value="Show All Markers">-->
            <input onclick="saveMarkers();" type=button value="Lưu lại">
            <input onclick="deleteMarker();" type=button value="Quay lại một mốc">
            <input onclick="deleteMarkers();" type=button value="Xóa hết các mốc">
            
            <div class="row" style="    margin-bottom: -15px;">
                <div class="col-lg-4">
                    <select id="thoigian">
                        <option value="1" selected>Thời gian T1</option>
                        <option value="2">Thời gian T2</option>
                        <option value="3">Thời gian T3</option>
                    </select>
                </div>
                
                <div class="col-lg-3">
                    <select id="calamviec">
                        <option value="1" selected>Ca sáng</option>
                        <option value="2">Ca chiều </option>
                        <option value="3">Ca tối </option>
                    </select>
                </div>
                <div class="col-lg-5">
                    <?=
                        DatePicker::widget([
                            'name' => 'date', 
                            'value' => date("d-m-Y"),
                            'language' => 'vi',
                            'id'=>'location-date',
                            'clientOptions' => [
                                'autoclose' => TRUE,
                                'format' => 'dd-mm-yyyy',
                            ],
                            'clientEvents' => [
                                'change' => 'function () {chonngaylamviec();}',
                            ],
                        ])
                    ?>
                </div>
            </div>
        </div>
        <script>
            // In the following example, markers appear when the user clicks on the map.
            // The markers are stored in an array.
            // The user can then click an option to hide, show or delete the markers.
            var map;
            var markers = [];
            var lamviecmotlan=0;
            var haightAshbury = {lat: 20.9930851, lng: 105.8259845};
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: haightAshbury,
                    //mapTypeId: google.maps.MapTypeId.TERRAIN
                });

                // This event listener will call addMarker() when the map is clicked.
                map.addListener('click', function(event) {
                    addMarker(event.latLng);
                });

                // Adds a marker at the center of the map.
                //addMarker(haightAshbury);
            }

            // Adds a marker to the map and push to the array.
            function addMarker(location) {
                var e = document.getElementById("thoigian");
                var thoigian = e.options[e.selectedIndex].value;
                var e1 = document.getElementById("calamviec");
                var calamviec = e1.options[e1.selectedIndex].value;
                var date=document.getElementById('location-date').value;
                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    label: thoigian,
                    calamviec:calamviec,
                    date: date,
                });
                markers.push(marker);
            }
            //Save all markers to database 
            function saveMarkers(){
                var str;
                for(var i=0;i<markers.length;i++){
                    str='x='+markers[i].position.G
                        +'&y='+markers[i].position.K
                        +'&time='+markers[i].label
                        +'&date='+markers[i].date
                        +'&session='+markers[i].calamviec;
                        insertlocation(str);
                }
                deleteMarkers();
            }
            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }
            
            //xoa phan tu cuoi cung
            function setMapuUndo(map){
                markers[markers.length-1].setMap(map);
            }
            // Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                setMapOnAll(null);
            }

            // Shows any markers currently in the array.
            function showMarkers() {
                setMapOnAll(map);
            }
            //Delete marker last
            function deleteMarker(){
                if (typeof markers !== 'undefined' && markers.length > 0) {
                    setMapuUndo(null);
                    markers.pop();
                }
            }
            // Deletes all markers in the array by removing references to them.
            function deleteMarkers() {
                clearMarkers();
                markers = [];
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
                          console.log('sdf');
                      }
                }
                xmlhttp.open("GET","/location/create?"+str,true);
                xmlhttp.send();
            }
            
            function chonngaylamviec(){
                lamviecmotlan++;
                if(lamviecmotlan==1){
                    <?php
                        $ngaychon=  Location::find()->where(1)->all();
                        $dungngaychon=[];
                        //thuc hien gan mang con 
                        foreach ($ngaychon as $key=>$value){
                            array_push($dungngaychon, $value);
                    ?>
                        var date='<?= date("d-m-Y", strtotime($value['date']))?>';
                        if(document.getElementById('location-date').value==date){
                            var location={lat:<?= $value['x']?>,lng:<?= $value['y']?>};
                            var marker = new google.maps.Marker({
                                position: location,
                                map: map,
                                label: "<?= $value['time']?>",
                                calamviec:<?= $value['session']?>,
                                date: date,
                            });
                            markers.push(marker);
                        }
                    <?php
                        }
                        $tamngaychon=  SiteController::tamarray($dungngaychon);
                    ?>   
                    haightAshbury.lat=<?=$tamngaychon['x']?>;    
                    haightAshbury.lng=<?=$tamngaychon['y']?>;  
                    initMap();
                    showMarkers();
                    markers = [];
                }
                   
                if(lamviecmotlan>=3){
                    lamviecmotlan=0;
                }
            }
            
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"></script>
    </body>
</html>