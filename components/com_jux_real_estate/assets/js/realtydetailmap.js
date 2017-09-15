var directionDisplay;
var directionsService = new google.maps.DirectionsService();
var map;
var origin = null;
var destination = null;
var waypoints = [];
var markers = [];
var directionsVisible = false;
var geocoder;
var infowindow;
function initialize() {
     geocoder = new google.maps.Geocoder();
    var jux_current_lat = document.getElementById("jform_latitude").value;
    var jux_current_long = document.getElementById("jform_longitude").value;
    var jform_address = document.getElementById("jform_address").value;
    if (jux_current_lat != "" && jux_current_long != "") {

    } else {
        jux_current_lat = "21.039101";// default map
        jux_current_long = "-105.853821";//default map
    }
    directionsDisplay = new google.maps.DirectionsRenderer();
    var jux_current = new google.maps.LatLng(jux_current_lat, jux_current_long);
    var myOptions = {
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: jux_current,
        scrollwheel:false
    }
    
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

     infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });

     getAddress(jux_current_lat, jux_current_long);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("directionsPanel"));
    
    google.maps.event.addListener(map, 'click', function(event) {
        if (origin == null) {
            origin = event.latLng;
            addMarker(origin);
        } else if (destination == null) {
            destination = event.latLng;
            addMarker(destination);
        } else {
            
            
            if (waypoints.length < 9) {
                waypoints.push({location: destination, stopover: true});
                destination = event.latLng;
                addMarker(destination);
                
              
            } else {
                alert("Maximum number of waypoints reached");
            }
        }
    });
}
function getAddress(latitude, longitude) {

    var latlng = new google.maps.LatLng(latitude, longitude);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                map.setCenter(latlng);
                 var img = uri_icon + icon;
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                     icon :img,
                });

                infowindow.setContent(results[1].formatted_address);
                infowindow.open(map, marker);
                google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
          });
            } else {
                alert("No results found");
            }
        } else {
            alert("Geocoder failed due to: " + status);
        }
    });
}
function addMarker(latlng) {
    markers.push(new google.maps.Marker({
        position: latlng,
        map: map,
        icon: "http://maps.google.com/mapfiles/marker" + String.fromCharCode(markers.length + 65) + ".png"
    }));
}

function calcRoute() {
    var canvas = document.getElementById("map_canvas");
    if (origin == null) {
        alert("Click on the map to add a start point");
        return;
    }

    if (destination == null) {
        alert("Click on the map to add an end point");
        return;
    }

    var mode;
    switch (document.getElementById("mode").value) {
       
        case "driving":
            mode = google.maps.DirectionsTravelMode.DRIVING;
            break;
        case "bicycling":
             mode = google.maps.DirectionsTravelMode.BICYCLING;
        break;
        case "walking":
            mode = google.maps.DirectionsTravelMode.WALKING;
            break;
    }

    var request = {
        origin: origin,
        destination: destination,
        waypoints: waypoints,
        travelMode: mode
        
    };

    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
              canvas.style.width = "100%";
        }
    });

    clearMarkers();
    directionsVisible = true;
}

function updateMode() {
    if (directionsVisible) {
        calcRoute();
       
    }
}

function clearMarkers() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
}

function clearWaypoints() {
    markers = [];
    origin = null;
    destination = null;
    waypoints = [];
    directionsVisible = false;
}

function reset() {
    var canvas = document.getElementById("map_canvas");
    clearMarkers();
    clearWaypoints();
    directionsDisplay.setMap(null);
    directionsDisplay.setPanel(null);
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("directionsPanel"));
    canvas.style.width = "143%";
}

google.maps.event.addDomListener(window, 'load', initialize);