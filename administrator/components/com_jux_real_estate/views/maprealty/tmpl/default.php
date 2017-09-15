<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla
 * @subpackage		com_jux_real_estate
 * 
 * @copyright		Copyright (C) 2012 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, See LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$lat_req = JRequest::getVar('lat', '');
$long_req = JRequest::getVar('long', '');

// set Map Center
$latitude = "21.039101";
$longitude = "105.853821";

$config = JUX_Real_EstateFactory::getConfigs();
$document = JFactory::getDocument();
$langs = explode('-', $document->getLanguage());
$lang = $langs[0];
$region = $langs[1];

$script = <<<EOD
google.maps.event.addDomListener(window, 'load', function() {
    var latitude 	= document.getElementById("latitude").value;
    var longitude 	= document.getElementById("longitude").value;
    var myLatlng = new google.maps.LatLng(latitude,longitude);
    if( latitude!="" && longitude!="" ){
        initialize(myLatlng);
    }else{
        var cenlatitude = "{$latitude}";
        var cenlongitude = "{$longitude}";
        var myLatlng;
        if(cenlatitude !="" && cenlongitude!="") {
          myLatlng = new google.maps.LatLng(cenlatitude,cenlongitude);
        } else {
          existlat = "21.039101";// default map
          existlong = "105.853821";//default map
          myLatlng = new google.maps.LatLng(existlat,existlong);
        }
        initialize(myLatlng);
    }
});

var geocoder;
var map;
var marker;
function initialize(myLatLng) {
    geocoder = new google.maps.Geocoder();

    var mapOptions = {
        center: myLatLng,
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    marker = new google.maps.Marker({
        map: map,
        position: myLatLng,
        draggable: true
    });

    google.maps.event.addListener(map, 'click', function(e){
        marker.setPosition(e.latLng);
        getMap(marker);
    });

    drag(marker);
}

function drag(marker){
    google.maps.event.addListener(marker, 'drag', function() {
        getMap(marker);
    });
}

function getMap(marker){
    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                document.getElementById("latitude").value = marker.getPosition().lat();
                document.getElementById("longitude").value = marker.getPosition().lng();
            }
        }
    });
}
EOD;
$document->addScript('http://maps.google.com/maps/api/js?libraries=places&amp;key=' . $config->get('gmapapikey') . '&amp;sensor=false&amp;language=' . $lang . '&amp;region=' . $region);
$document->addScriptDeclaration($script);
?>

<label for="latitude"><?php echo JText::_('COM_JUX_REAL_ESTATE_LATITUDE'); ?></label>
<input type="text" id="latitude" name="latitude" class="inputbox" value="<?php echo $lat_req; ?>" size="30">
<label for="longitude"><?php echo JText::_('COM_JUX_REAL_ESTATE_LONGITUDE'); ?></label>
<input type="text" id="longitude" name="longitude" class="inputbox" value="<?php echo $long_req; ?>" size="30">
<input type="button" class="button" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_SELECT'); ?>"
       onclick="window.parent.jSelectLocation(document.getElementById('latitude').value, document.getElementById('longitude').value, '<?php echo JRequest::getVar('object'); ?>');"/>

<div id="map_canvas" style="width:100% ; height: 300px"></div>


