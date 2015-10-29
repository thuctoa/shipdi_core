<link href="../css/maps.css"/>
<div id="map" style="float:left;width:1000px;height:600px;"></div>
<!--<div id="right-panel">
  <p>Total Distance: <span id="total"></span></p>
</div>-->
<?php
    if(count($sobando)>1){
        $len=  count($sobando);

            for($i=0;$i<$len;$i++){
?>
                <a href="/site/location?ngay=<?=$_GET['ngay']?>&ca=<?=$_GET['ca']?>&socum=<?=$_GET['socum']?>&inkhuvuc=<?=$_GET['inkhuvuc']?>&tam=<?=$i?>" 
                    <?php
                        if($_GET['tam']==$i){
                    ?>
                   style="background:  #c7bfbf;"
                    <?php
                        }
                    ?>
                    >Tấm <?=$i?>, </a>
<?php
            }
    }
?>
<a href="https://www.google.com/maps/dir/21.017736,105.8414898/21.001174,105.8185587/21.0300178,105.8120355/@21.0357902,105.8352953,13z/data=!4m2!4m1!3e0">
 Ban do
</a>
<?php
    $inmap='https://www.google.com/maps/dir/';
    $endmap='@21.0357902,105.8352953,13z/data=!4m2!4m1!3e0';
    foreach ($array as $val){
      $inmap=$inmap.$val['x'].','.$val['y'].'/';
    }
    $inmap=$inmap.$endmap;
?>
<a href="<?=$inmap?>">
 Ban do chuan
</a>
<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: {lat: 20.9930851, lng: 105.8259845} // Australia.
        });

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({
            draggable: true,
            map: map,
            panel: document.getElementById('right-panel'),
        });

        directionsDisplay.addListener('directions_changed', function() {
            computeTotalDistance(directionsDisplay.getDirections());
        });
        <?php
            $array=$sobando[$_GET['tam']];
            if(count($array)>10){
        ?>
            var diemdau={
                lat:<?= current($array)['x']?>,
                lng:<?= current($array)['y']?>,
            };
            var diemcuoi={
                lat:<?= $array[9]['x']?>,
                lng:<?= $array[9]['y']?>
            };

            var diemgiua=[];
            <?php
                array_shift($array);
                for($i=1;$i<9;$i++){
            ?>
                    var dg={
                        lat:<?= $array[$i]['x']?>,
                        lng:<?= $array[$i]['y']?>
                    };
                    diemgiua.push({
                                location: dg,
                                stopover: true
                            });
                    
            <?php
                unset($array[$i]);
                }
            ?> 
            displayRoute(diemdau, diemcuoi,diemgiua, directionsService,
                directionsDisplay);
        <?php
            }else{
        ?>
            var diemdau={
                lat:<?= current($array)['x']?>,
                lng:<?= current($array)['y']?>,
            };
            var diemcuoi={
                lat:<?= end($array)['x']?>,
                lng:<?= end($array)['y']?>
            };

            var diemgiua=[];
            <?php
                array_shift($array);
                array_pop($array);
                foreach ($array as $val){
            ?>
                    var dg={
                        lat:<?= $val['x']?>,
                        lng:<?= $val['y']?>
                    };
                    diemgiua.push({
                                location: dg,
                                stopover: true
                            });
            <?php
                }
            ?> 
            displayRoute(diemdau, diemcuoi,diemgiua, directionsService,
                directionsDisplay);
        <?php
        }
        ?>
    }

    function displayRoute(origin, destination,diemgiua ,service, display) {
        
        service.route({
            origin: origin,
            destination: destination,
            waypoints: diemgiua,
            travelMode: google.maps.TravelMode.DRIVING,
            avoidTolls: true
        }, function(response, status) {
            if (status === google.maps.DirectionsStatus.OK) {
              display.setDirections(response);
            } else {
              alert('Could not display directions due to: ' + status);
            }
        });
    }

    function computeTotalDistance(result) {
        var total = 0;
        var myroute = result.routes[0];
        for (var i = 0; i < myroute.legs.length; i++) {
            total += myroute.legs[i].distance.value;
        }
        total = total / 1000;
//        document.getElementById('total').innerHTML = total + ' km';
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"
    async defer></script>
