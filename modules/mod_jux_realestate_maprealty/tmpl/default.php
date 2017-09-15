<?php
/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
    var juri_base = "<?php echo JUri::base();?>"
    var flag = "<?php echo ($params->get('userlocation')) ? true : false; ?>";
    if (flag == false) {
        var latitude = "<?php echo $params->get('location')[0]; ?>";
        var longitude = "<?php echo $params->get('location')[1]; ?>";
    }
    var jp_side_bar = "";
    var jp_markers = [];
    function createMarker(map, infowindow, point, title, html, icon,title2) {

        var image = new google.maps.MarkerImage("<?php echo JURI::base(); ?>" + "administrator/components/com_jux_real_estate/assets/icon/" + icon,
            new google.maps.Size(30, 34),
            new google.maps.Point(0, 0),
            new google.maps.Point(0, 32)
            );
        var marker = new google.maps.Marker({
            position: point,
            map: map,
            icon: image,
            title: title2
        });
        bindInfoWindow(marker, map, infowindow, html);
        return marker;
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
        infoWindow.setContent(html);
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
    }

    function jp_markerClicked(i) {
        google.maps.event.trigger(jp_markers[i], 'click');
    }

    function showElement(element) {

        var myEle = document.getElementById(element);
        if (myEle.style.display == "none") {
            myEle.style.display = "block";
            myEle.backgroundPosition = "top";
            document.getElementById("fieldtext").style.display = "block";
        } else {
            myEle.style.display = "none";
            document.getElementById("fieldtext").style.display = "none";
        }
    }

    google.maps.event.addDomListener(window, 'load', function() {
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
            // center: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
            zoom: <?php echo (int) $params->get('zoom_level'); ?>,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: '<?php echo ($params->get('scrollwheel')) ? true : false; ?>'
        };
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        if (navigator.geolocation) {

            if (flag == false) {
                var pos = new google.maps.LatLng(latitude, longitude);
                map.setCenter(pos);
                var marker = new google.maps.Marker({
                    map: map,
                    position: pos,
                    title: 'You are here'
                });
            } else {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    map.setCenter(pos);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: pos,
                        title: 'You are here'
                    });
                });
            }
        } else {
            // Browser doesn't support Geolocation
            map.innerHTML = "Geolocation is not supported by this browser.";
        }
        var infowindow = new google.maps.InfoWindow();
        downloadUrl(juri_base + '/modules/mod_jux_realestate_maprealty/xml/data.xml', function(data) {
           
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName("marker");
            for (i = 0; i < markers.length; i++) {
                var icon = markers[i].getAttribute("icon");
                var latitude = markers[i].getAttribute("latitude");
                var longitude = markers[i].getAttribute("longitude");
                var image = markers[i].getAttribute("image");
                var beds = markers[i].getAttribute("beds");
                var baths = markers[i].getAttribute("baths");
                var sqft = markers[i].getAttribute("sqft");

                var title = markers[i].getAttribute("title");
                var title2 = markers[i].getAttribute("title2");
                var url = markers[i].getAttribute("url");
                var price = markers[i].getAttribute("price");
                var category = markers[i].getAttribute("category");
                var point = new google.maps.LatLng(latitude, longitude);
                var htmls = '<div class="map_info_details clearfix">' +
                '<div class="top_info">' + image + '<span class="contact">' + category + '</span></div>' +
                '<div class="bottom_info"><div class="title">' + title + '</div><div class="price">' + price +
                '<span id="infosize">' + sqft + '</span><span id="infobath">' + baths + '</span><span id="inforoom">' + beds + '</span></div>'
                '</div></div>';
                var marker = createMarker(map, infowindow, point, title, htmls, icon, title2);
                jp_markers.push(marker);
            }
        });
});
function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
    new ActiveXObject('Microsoft.XMLHTTP') :
    new XMLHttpRequest;
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            request.onreadystatechange = function() {
            };
            callback(request, request.status);
        }
    };
    request.open('GET', url, true);
    request.send(null);
}
</script>

</head>
<div class="row-fluid">
    <div class="span12">
        <div id="icon_bar">
            <?php
            if ($params->get('enable_iconbar')) {
                foreach ($types as $type) {
                    echo '<img src="' . JURI::base() . 'administrator/components/com_jux_real_estate/assets/icon/' . $type->icon . '" width="25" height="25"/>  ' . $type->title . '';
                }
            }
            ?>        
        </div>       
    </div>
    <div class="span12" style="margin: 0; margin-right: 0px;">   
        <div id="map" style="height: <?php echo $params->get('map_height') ?>; max-width: auto !important"></div>
    </div>      
</div>