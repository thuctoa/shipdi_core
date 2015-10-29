<?php
    $this->title = 'Location';
    use dosamigos\datepicker\DatePicker;

?>
       
<link href="../css/maps.css"/>
<div id="panel" style="margin-top: -50px;">
<!--    <input onclick="clearMarkers();" type=button value="Hide Markers">
    <input onclick="showMarkers();" type=button value="Show All Markers">
    <input onclick="deleteMarkers();" type=button value="Delete Markers">-->
    <div class="col-lg-2">
        <select id="calamviec" onchange="chonngaylamviec();" style="width: 160px;">
            <option value="1" <?php if($ca==1){echo 'selected';}?>>Ca sáng</option>
            <option value="2" <?php if($ca==2){echo 'selected';}?>>Ca chiều </option>
            <option value="3" <?php if($ca==3){echo 'selected';}?>>Ca tối </option>
        </select>
        <select id="thoigian" class= "tach" style="width: 160px;">
            <option value="1" >Thời gian T1</option>
            <option value="2">Thời gian T2</option>
            <option value="3" selected>Thời gian T3</option>
        </select>
        <div class="tach"></div>
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
    <div class="col-lg-4">
        <div class="row">
            <div class="input-group " >
                <span class="input-group-addon" id="basic-addon1">Số Shiper:</span>
                <input type="text" id="socum" class="form-control" aria-describedby="basic-addon1" onchange="choncum();" value="<?=$socum?>">
            </div>
            
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">Mã vận đơn:</span>
                <input type="text" id="idorder" class="form-control" placeholder="Nhập mã vận đơn" aria-describedby="basic-addon1">
            </div>
        </div>
        
    </div>
     <div class="col-lg-2">
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"> T1:</span>
                <input type="text" id="thoigianhoatdongt1" onchange="thoigianhoatdong(1);"  value="<?=  round($thoigiantheot1,2)?>"  class="form-control" placeholder="Thời gian" aria-describedby="basic-addon1">
                <span class="input-group-addon" id="basic-addon1"> giờ </span>
            </div>
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"> T2:</span>
                <input type="text" id="thoigianhoatdongt2" onchange="thoigianhoatdong(2);"  value="<?=  round($thoigiantheot2,2)?>" class="form-control" placeholder="Thời gian" aria-describedby="basic-addon1">
                <span class="input-group-addon" id="basic-addon1"> giờ </span>
            </div>
        </div>
         <div class="row">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"> T3:</span>
                <input type="text" id="thoigianhoatdongt3" onchange="thoigianhoatdong(3);" value="<?=  round($thoigiantheot3,2)?>" class="form-control" placeholder="Thời gian" aria-describedby="basic-addon1">
                <span class="input-group-addon" id="basic-addon1"> giờ </span>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
<!--        <input onclick="phanchia();" type=button value="Phân chia">-->
        <div class="input-group">
            <input type="text" id="xoamavandon" class="form-control" placeholder="Mã vận đơn">
            <span class="input-group-btn">
                <button class="btn btn-default" onclick="xoamavandon();" type="button">Xóa!</button>
            </span>
          </div>
        <input style="width: 78px;" class= "tach" onclick="xoacuoi();" type=button value="Xóa cuối">
        <input style="width: 78px;" class= "tach" onclick="xoahet();" type=button value="Xóa hết">
        <input class="btn-info tach" 
               onclick="<?php if(isset($_GET['phankhuvuc'])){
                   if($_GET['phankhuvuc']==0){
                       ?>
                           phuckhuvuc(1);
                    <?php
                   }else{
                       ?>
                            phuckhuvuc(0);
                           <?php
                   }
                }else{
                ?>
                phuckhuvuc(1);
                <?php
                }
                ?>
                " 
            type=button value="Phân khu vực">
    </div>
    <div class="col-lg-2">
        <select id="inkhuvuc" onclick="inkhuvuc();">
            <option  value="-1" selected="selected" > Chọn khu vực để in </option>
            <?php foreach ($tamcum as $key=>$val){?>
                <option value="<?=$key?>" >Khu vực <?=$key?> </option>
            <?php }?>
        </select>
        <select id="laydiachicum" class= "tach" onclick="laydiachicum();">
            <option  value="-10" selected="selected" > Lấy địa toàn bộ</option>
            <?php foreach ($tamcum as $key=>$val){?>
                <option value="<?=$key?>" >Khu vực <?=$key?> </option>
            <?php }?>
        </select>
    </div>
    <input id="pac-input" class="controls" style="width: 500px;margin: 10px;" type="text" placeholder="Tìm kiếm địa chỉ khách hàng...">
    <div id="map" style="float:left;width:1000px;height:600px;"></div>
    <!--    <div class="col-lg-2">
        <input onclick="hienthibien();" type=button value="Vẽ đường biên ">
        <input onclick="chonngaylamviec();" type=button value="Ẩn đường biên ">
    </div>-->
    <div style="float: right; margin-top: -600px;">
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
            <li>Tổng số đơn:  
                <?php
                    
                    echo count($location).' đơn';
                ?>
            </li>
            <br>
            <?php foreach ($tamcum as $key=>$val){?>
            <li> <?php if($ca==1){echo 'S'.$key;}if($ca==2){echo 'C'.$key;}if($ca==3){echo 'T'.$key;}?> 
             có: <?=  count($phancum[$key])?> đơn </li>
            <?php }?>
        </ul>
    </div>
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
            
                addMarker(event);
            
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
                            +'&idorder='+'thuc';//document.getElementById('idorder').value;
                        insertlocation(str);
                    document.getElementById('idorder').value='';
                    document.getElementById('pac-input').value='';

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
             <?php
                if(isset($_GET['phankhuvuc'])&&$_GET['phankhuvuc']==1){
             ?> 
                     vetamcum();  
            <?php
                }
            ?>
            
        <?php
        }
        ?>
                
    }
    function inkhuvuc(){
        var e2          =   document.getElementById("inkhuvuc");
        var inkhuvuc   =   e2.options[e2.selectedIndex].value;
        if(inkhuvuc!=-1){
            var e1          =   document.getElementById("calamviec");
            var calamviec   =   e1.options[e1.selectedIndex].value;
            var date        =   document.getElementById('location-date').value;
            var socum        =   document.getElementById('socum').value;
            
            window.open("/site/location?"
            +'ngay='+date +'&ca='+calamviec+'&socum='+socum+'&inkhuvuc=' +inkhuvuc+'&tam=0'
            ,
            '_blank'
            );
        }
    }
    function laydiachicum(){
        var e2          =   document.getElementById("laydiachicum");
        var laydiachicum   =   e2.options[e2.selectedIndex].value;
        if(laydiachicum!=-1){
            var e1          =   document.getElementById("calamviec");
            var calamviec   =   e1.options[e1.selectedIndex].value;
            var date        =   document.getElementById('location-date').value;
            var socum        =   document.getElementById('socum').value;
            
            window.open("/site/location?"
            +'ngay='+date +'&ca='+calamviec+'&socum='+socum+'&laydiachicum=' +laydiachicum+'&tam=0'
            ,
            '_blank'
            );
        }
    }
    function xoacuoi(){
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
        xmlhttp.open("GET","/location/delete?id="+'<?=end($location)['id']?>',true);
        xmlhttp.send();
        phanchia();
    }
    function xoamavandon(){
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
        var xoamavandon        =   document.getElementById('xoamavandon').value;
        xmlhttp.open("GET","/location/index?xoamavandon="+xoamavandon,true);
        xmlhttp.send();
        phanchia();
    }
    function xoahet(){
        if(confirm("Bạn chắc chắn muốn xóa toàn bộ dữ liệu này?")){
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
            xmlhttp.open("GET","/location/index?xoahet=1&date="+'<?=end($location)['date']?>'+'&session='+'<?=end($location)['session']?>',true);
            xmlhttp.send();
            phanchia();
        }
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
        coordInfoWindow.setContent('Khu vực ' + cum);
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
    function phuckhuvuc(phankhuvuc){
        var e1          =   document.getElementById("calamviec");
        var calamviec   =   e1.options[e1.selectedIndex].value;
        var date        =   document.getElementById('location-date').value;
        var socum        =   document.getElementById('socum').value;
        window.open("/site/location?"
        +'ngay='+date +'&ca='+calamviec+'&phankhuvuc='+phankhuvuc+'&socum='+socum
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
            draggable: true ,
            radius: 2000,
        });
        console.log(location);
        console.log(Object.keys(location));
        console.log(location[Object.keys(location)[2]][0]);
        str='x='+location[Object.keys(location)[0]]
            +'&y='+location[Object.keys(location)[1]]
            +'&time='+thoigian
            +'&date='+date
            +'&session='+calamviec
            +'&idorder='+ 'thuc';//document.getElementById('idorder').value;
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
    function thoigianhoatdong( t ){
        lamviecmotlan++;
        if(lamviecmotlan==1){
            var e1          =   document.getElementById("calamviec");
            var calamviec   =   e1.options[e1.selectedIndex].value;
            var date        =   document.getElementById('location-date').value;
            var thoigianhoatdongt3        =   document.getElementById('thoigianhoatdongt3').value;
            var thoigianhoatdongt2        =   document.getElementById('thoigianhoatdongt2').value;
            var thoigianhoatdongt1        =   document.getElementById('thoigianhoatdongt1').value;
            window.open("/site/location?"
                    +'ngay='+date +'&ca='+calamviec
                    +'&thoigianhoatdongt3='+thoigianhoatdongt3
                    +'&thoigianhoatdongt2='+thoigianhoatdongt2
                    +'&thoigianhoatdongt1='+thoigianhoatdongt1
                    +'&t='+t
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
    src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap&libraries=places&sensor=false">
</script>
