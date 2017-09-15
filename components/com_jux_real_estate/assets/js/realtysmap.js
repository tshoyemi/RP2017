/**
 * @version		$Id: $
 * @author		joomlaux!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by joomlaux. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

 google.maps.event.addDomListener(window, 'load', initialize);

 var geocoder;
 var map;
 var infowindow;
 var marker;
 function initialize() {
    geocoder = new google.maps.Geocoder();

    var existlat = document.getElementById("jform_latitude").value;
    var existlong = document.getElementById("jform_longitude").value;

    if (existlat != "" && existlong != "") {

    } else {
        existlat = "21.039101";// default map
        existlong = "105.853821";//default map
    }
    var mapOptions = {
        center: new google.maps.LatLng(existlat, existlong),
        zoom: 14,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false
    };

    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });

     getAddress(existlat, existlong);
}

function getAddress(latitude, longitude) {

    var latlng = new google.maps.LatLng(latitude, longitude);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                map.setCenter(latlng);
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                });

                infowindow.setContent(results[1].formatted_address);
                infowindow.open(map, marker);
            } else {
                alert("No results found");
            }
        } else {
            alert("Geocoder failed due to: " + status);
        }
    });
}