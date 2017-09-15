/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla
 * @subpackage		com_jux_real_estate
 * 
 * @copyright		Copyright (C) 2012 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, See LICENSE.txt
 */

google.maps.event.addDomListener(window, 'load', initialize);
var geocoder;
var map;
var infowindow;
var marker;
var autocomplete;
var directionsDisplay;
var directionsService;
function initialize() {
    geocoder = new google.maps.Geocoder();
    directionsDisplay = new google.maps.DirectionsRenderer({draggable: true});
    directionsService = new google.maps.DirectionsService();

    var existlat = document.getElementById("jform_latitude").value;
    var existlong = document.getElementById("jform_longitude").value;

    if (existlat != "" && existlong != "") {

    } else {

        existlat = "40.71406416259599";// default map
        existlong = "-74.00559408281259";//default map
    }
    var mapOptions = {
        center: new google.maps.LatLng(existlat, existlong),
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    var input = document.getElementById('searchTextField');
    autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);

    infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });

    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("route"));

    infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });


    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(existlat, existlong),
        draggable: true
    });

    var id = document.getElementById("jform_id").value;

    if (id != "" && id != "0") {
//        clearOverlays();
//        getAddress(existlat, existlong);
//        var editcity = document.getElementById('jform_city').value;
//        var editstreet = document.getElementById('jform_street').value;
//        var editmessage = editstreet + ',' + editcity;
//        infowindow.setContent(editmessage);
//        infowindow.open(map, marker);
    } else {
        infowindow.setContent("Select position on map.");
        infowindow.open(map, marker);
    }
    google.maps.event.addListener(map, 'click', function(e) {
        marker.setPosition(e.latLng);
        getMap(marker);
    });

//    document.id('detail_pane').getElement('dt.location_panel').addEvent('click', function(e){
//        setTimeout( function() {
//            google.maps.event.trigger(map, 'resize');
//            map.setCenter(marker.getPosition());
//        }, 10);
//    });

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        var place = autocomplete.getPlace();

        if (place) {
            if (place.geometry) {
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                marker.setPosition(place.geometry.location);
//		  alert('ádfs');
                var address = '';
                if (place.address_components) {
                    address = [(place.address_components[0] &&
                                place.address_components[0].short_name || ''),
                        (place.address_components[1] &&
                                place.address_components[1].short_name || ''),
                        (place.address_components[2] &&
                                place.address_components[2].short_name || '')
                    ].join(', ');
                }

                infowindow.setContent('<strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);

                document.getElementById("jform_latitude").value = place.geometry.location.lat();
                document.getElementById("jform_longitude").value = place.geometry.location.lng()

                //  document.getElementById("jform_toAddress").value = content;
            }
        }
    });

    google.maps.event.addListener(map, "idle", function() {
        google.maps.event.trigger(map, 'resize');
    });

    // setupClickListener('changetype-all', []);
    // setupClickListener('changetype-establishment', ['establishment']);
    // setupClickListener('changetype-geocode', ['geocode']);
    drag(marker);
}

// Sets a listener on a radio button to change the filter type on Places
// Autocomplete.
function setupClickListener(id, types) {
    var radioButton = document.getElementById(id);
    google.maps.event.addDomListener(radioButton, 'click', function() {
        autocomplete.setTypes(types);
    });
}

function clearOverlays() {
    marker.setMap(null);
    marker = null;
}

function drag(marker) {
    google.maps.event.addListener(marker, 'drag', function() {
        getMap(marker);
    });
}

function getMap(marker) {

    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                var content = results[0].formatted_address;

                var street_number = '';
                var route = '';
                var city = '';
                var province = '';
                for (z = 0; z < results[0].address_components.length; z++) {

//                    if (results[0].address_components[z].types[0] == 'street_number') {
//                        if (results[0].address_components[z] && results[0].address_components[z].long_name)
//                            street_number = results[0].address_components[z].long_name;
//                    }
//
//                    if (results[0].address_components[z].types[0] == 'route') {
//                        if (results[0].address_components[z] && results[0].address_components[z].long_name)
//                            route = results[0].address_components[z].long_name;
//                    }
//                    if (street_number != "" && route != "") {
//                        street = street_number + ' ' + route;
//                    } else if (route != "") {
//                        street = route;
//                    }

                    // if (results[0].address_components[z].types[0] == 'locality') {
                    //     if (results[0].address_components[z] && results[0].address_components[z].long_name)
                    //         city = results[0].address_components[z].long_name;
                    // }
//                    if (results[0].address_components[z].types[0] == 'administrative_area_level_1') {
//                        administrative_area_level_1 = results[0].address_components[z];
//                        state = administrative_area_level_1.long_name;
//                    }
                    // if (results[0].address_components[z].types[0] == 'administrative_area_level_2') {
                    //     administrative_area_level_1 = results[0].address_components[z];
                    //     province = administrative_area_level_1.long_name;
                    // }
//                    if (results[0].address_components[z].types[0] == 'country') {
//                        if (results[0].address_components[z] && results[0].address_components[z].long_name)
//                            country = results[0].address_components[z].long_name;
//                    }
//                    if (results[0].address_components[z].types[0] == 'postal_code') {
//                        postal_code = results[0].address_components[z];
//                        zip = postal_code.long_name;
//                    }
                }

                // document.getElementById('jform_province').value = province;
                // document.getElementById('jform_city').value = city;

                infowindow.setContent(content);
                infowindow.open(map, marker);
                // lay longitude, lay latitude
                document.getElementById("jform_latitude").value = marker.getPosition().lat();
                document.getElementById("jform_longitude").value = marker.getPosition().lng();
                //document.getElementById("jform_fromAddress").value = content;
                document.getElementById("jform_address").value = content;


                // lay address khi di chuyen mouse
                //document.getElementById("fromAddress").value = content;
            }
        }
    });
}

function getAddress(latitude, longitude) {

    var latlng = new google.maps.LatLng(latitude, longitude);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                google.maps.event.trigger(map, 'resize');
                map.setZoom(11);
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    draggable: true
                });

                infowindow.setContent(results[1].formatted_address);
                infowindow.open(map, marker);
                // document.getElementById("toAddress").value = results[1].formatted_address;
                drag(marker);
            } else {
                alert("No results found");
            }
        } else {
            alert("Geocoder failed due to: " + status);
        }
    });
}


