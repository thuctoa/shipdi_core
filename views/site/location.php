<?php
    $this->title = 'Location';
    use dosamigos\datepicker\DatePicker;
    
?>

<link href="../css/maps.css"/>
<div id="panel">
<!--    <input onclick="clearMarkers();" type=button value="Hide Markers">
    <input onclick="showMarkers();" type=button value="Show All Markers">
    <input onclick="deleteMarkers();" type=button value="Delete Markers">-->
    <div class="col-lg-1">
        <select id="calamviec" onchange="chonngaylamviec();">
            <option value="1" <?php if($ca==1){echo 'selected';}?>>Ca sáng</option>
            <option value="2" <?php if($ca==2){echo 'selected';}?>>Ca chiều </option>
            <option value="3" <?php if($ca==3){echo 'selected';}?>>Ca tối </option>
        </select>
    </div>
    <div class="col-lg-1" style="margin-right: 15px;">
        <select id="thoigian">
            <option value="1" >Thời gian T1</option>
            <option value="2">Thời gian T2</option>
            <option value="3" selected>Thời gian T3</option>
        </select>
    </div>
    <div class="col-lg-2" style="margin-top: -5px;">
        <?=
            DatePicker::widget([
                'name' => 'date', 
                'value' => date("d-m-Y", strtotime($date)),
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
    <input id="pac-input" class="controls" style="width: 500px;margin: 10px;" type="text" placeholder="Tìm kiếm địa chỉ khách hàng...">
    <span >Mã vận đơn: </span><input id="idorder" style="width: 50px;"  />
    <span >Số Shiper: </span><input id="socum" style="width: 30px;" onchange="choncum();" value="<?=$socum?>"/>
    <span >Thời gian: </span><input id="thoigianhoatdong" style="width: 30px;" onchange="thoigianhoatdong();" value="<?=$thoigianhoatdong?>"/> giờ
    <input onclick="phanchia();" type=button value="Phân chia">
    <div id="map" style="float:left;width:1000px;height:600px;"></div>
    <!--    <div class="col-lg-2">
        <input onclick="hienthibien();" type=button value="Vẽ đường biên ">
        <input onclick="chonngaylamviec();" type=button value="Ẩn đường biên ">
    </div>-->
    <p></p>
    <ul>
        <li>Số đơn T1: 
        <?php
            $i=0;
            foreach ($location as $val){
                if($val['time']==1){
                    $i++;
                }
            }
            echo $i.' đơn';
        ?>
        </li>
        <li>Số đơn T2:   
            <?php
                $i=0;
                foreach ($location as $val){
                    if($val['time']==2){
                        $i++;
                    }
                }
                echo $i.' đơn';
            ?>
        </li>
        <li>Số đơn T3:  
            <?php
                $i=0;
                foreach ($location as $val){
                    if($val['time']==3){
                        $i++;
                    }
                }
                echo $i.' đơn';
            ?>
        </li>
    </ul>
</div>


<script> 
    var map;
    var markers = [];
    var lamviecmotlan=0;
     //ve duong bien
    var diembien=[];
    <?php
            foreach ($diembien as $value){
    ?>
                diembien.push ({
                    lat:<?= $value['x']?>,
                    lng:<?= $value['y']?>
                });   
    <?php
        }
    ?>
    var tamcum=[];
    <?php
            foreach ($tamcum as $value){
    ?>
                tamcum.push ({
                    lat:<?= $value['x']?>,
                    lng:<?= $value['y']?>
                });   
    <?php
        }
    ?>
    function initMap() {
        var haightAshbury = {lat: 20.9930851, lng: 105.8259845};
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: haightAshbury,
          mapTypeId: google.maps.MapTypeId.ROADMAP,

        });
        // This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
          addMarker(event.latLng);
        });
        
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        
        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });
        
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
              return;
            }
            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                var e           =   document.getElementById("thoigian");
                var thoigian    =   e.options[e.selectedIndex].value;
                var e1          =   document.getElementById("calamviec");
                var calamviec   =   e1.options[e1.selectedIndex].value;
                var date        =   document.getElementById('location-date').value;

                // Create a marker for each place.
                var marker=new google.maps.Marker({
                    map: map,
                    title: place.name,
                    position: place.geometry.location,
                    label: thoigian,
                    calamviec:calamviec,
                    date: date,
                });
                markers.push(marker);
                //insert vao database
                var str='x='+marker.getPosition().lat()
                    +'&y='+marker.getPosition().lng()
                    +'&time='+thoigian
                    +'&date='+date
                    +'&session='+calamviec
                    +'&idorder='+document.getElementById('idorder').value;
                insertlocation(str);
                document.getElementById('idorder').value='';
                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
            map.setZoom(12);
        });
        // Adds a marker at the center of the map.
       // addMarker(haightAshbury);
        
        //admap cho markers
        <?php
            if(count($location)>0){
                foreach ($location as $val){
        ?>
               
                    var date='<?= date("d-m-Y", strtotime($val['date']))?>';
                    var location={lat:<?= $val['x']?>,lng:<?= $val['y']?>};
                    var marker      =   new google.maps.Marker({
                        position: location,
                        map: map,
                        label: "<?= $val['time']?>",
                        calamviec:<?= $val['session']?>,
                        date: date,
                    });
                    markers.push(marker);
                    
        <?php
                }
           
            if(isset($_GET['vebien'])){
                if($_GET['vebien']==1){
        ?>
                vebien();
        <?php
                }
            }
        ?>
              vetamcum();  
            
        <?php
        }
        ?>
                
    }
    function phanchia(){
        var e1          =   document.getElementById("calamviec");
        var calamviec   =   e1.options[e1.selectedIndex].value;
        var date        =   document.getElementById('location-date').value;
        var socum        =   document.getElementById('socum').value;
        window.open("/site/location?"
        +'ngay='+date +'&ca='+calamviec+'&socum='+socum
        ,
        '_parent'
        );
    }
    function thongtincum(tamcum, cum){
        var coordInfoWindow = new google.maps.InfoWindow();
        coordInfoWindow.setContent('Cụm số ' + cum);
        coordInfoWindow.setPosition(tamcum);
        coordInfoWindow.open(map);
    }
    function hienthibien(){
        var e1          =   document.getElementById("calamviec");
        var calamviec   =   e1.options[e1.selectedIndex].value;
        var date        =   document.getElementById('location-date').value;
        var socum        =   document.getElementById('socum').value;
        window.open("/site/location?"
        +'ngay='+date +'&ca='+calamviec+'&vebien=1'+'&socum='+socum
        ,
        '_parent'
        );
    }
    //ve tam cum
    var diembientheocum=[];
    function vetamcum(){
        
        <?php
        foreach ($diembientheocum as $key=>$val){
        ?>
            diembientheocum[<?= $key?>]=[];
        <?php
            foreach ($val as $value){
        ?>
                diembientheocum[<?= $key?>].push ({
                    lat:<?= $value['x']?>,
                    lng:<?= $value['y']?>
                });   
        <?php
            }
        }
        ?>
        //ve bien tren ban do
        for(var i=0; i<diembientheocum.length;i++){
            var bermudaTriangle = new google.maps.Polygon({
                paths: diembientheocum[i],
                strokeColor: "Green",
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillColor: "Green",
                fillOpacity: 0.35
            });
            bermudaTriangle.setMap(map);
            
        }
        <?php
        foreach ($tamcum as $key => $val){
        ?>
            thongtincum(tamcum[<?=$key?>],<?=$key?>);
        <?php
        }
        ?>
    }
    function vebien(){
        
        //ve bien tren ban do
        var bermudaTriangle = new google.maps.Polygon({
            paths: diembien,
            strokeColor: "blue",
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: "blue",
            fillOpacity: 0.35
        });
        bermudaTriangle.setMap(map);
        diembien=[];
    }
    
    // Adds a marker to the map and push to the array.
    function addMarker(location) {
        var e           =   document.getElementById("thoigian");
        var thoigian    =   e.options[e.selectedIndex].value;
        var e1          =   document.getElementById("calamviec");
        var calamviec   =   e1.options[e1.selectedIndex].value;
        var date        =   document.getElementById('location-date').value;
        var marker      =   new google.maps.Marker({
            position: location,
            map: map,
            label: thoigian,
            calamviec:calamviec,
            date: date,
        });
        str='x='+location.G
            +'&y='+location.K
            +'&time='+thoigian
            +'&date='+date
            +'&session='+calamviec
            +'&idorder='+document.getElementById('idorder').value;
        insertlocation(str);
        document.getElementById('idorder').value='';
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
    function thoigianhoatdong(){
        lamviecmotlan++;
        if(lamviecmotlan==1){
            var e1          =   document.getElementById("calamviec");
            var calamviec   =   e1.options[e1.selectedIndex].value;
            var date        =   document.getElementById('location-date').value;
            var thoigianhoatdong        =   document.getElementById('thoigianhoatdong').value;
            window.open("/site/location?"
                    +'ngay='+date +'&ca='+calamviec+'&thoigianhoatdong='+thoigianhoatdong
                    ,
                    '_parent'
                    );
        }

        if(lamviecmotlan>=3){
            lamviecmotlan=0;
        }
    }
    function choncum(){
        lamviecmotlan++;
        if(lamviecmotlan==1){
            var e1          =   document.getElementById("calamviec");
            var calamviec   =   e1.options[e1.selectedIndex].value;
            var date        =   document.getElementById('location-date').value;
            var socum        =   document.getElementById('socum').value;
            window.open("/site/location?"
                    +'ngay='+date +'&ca='+calamviec+'&socum='+socum
                    ,
                    '_parent'
                    );
        }

        if(lamviecmotlan>=3){
            lamviecmotlan=0;
        }
    }
    function chonngaylamviec(){
        lamviecmotlan++;
        if(lamviecmotlan==1){
            var e1          =   document.getElementById("calamviec");
            var calamviec   =   e1.options[e1.selectedIndex].value;
            var date        =   document.getElementById('location-date').value;
            window.open("/site/location?"
                    +'ngay='+date +'&ca='+calamviec
                    ,
                    '_parent'
                    );
        }

        if(lamviecmotlan>=3){
            lamviecmotlan=0;
        }
    }
    //luu vao co so du lieu
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
        xmlhttp.open("GET","/location/create?"+str,true);
        xmlhttp.send();
    }
    // The mapping between latitude, longitude and pixels is defined by the web
    // mercator projection.

</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap&libraries=places">
</script>
