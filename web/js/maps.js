
// In the following example, markers appear when the user clicks on the map.
// The markers are stored in an array.
// The user can then click an option to hide, show or delete the markers.
var map;
var markers = [];
var lamviecmotlan=0;
function initMap() {
    var haightAshbury = {lat: 20.9930851, lng: 105.8259845};

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
    addMarker(haightAshbury);
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
function chonngaylamviec(){
    lamviecmotlan++;
    if(lamviecmotlan==1){
        window.open("/site/location?"+'ngay='+document.getElementById('location-date').value,'_parent');
    }

    if(lamviecmotlan>=3){
        lamviecmotlan=0;
    }
}