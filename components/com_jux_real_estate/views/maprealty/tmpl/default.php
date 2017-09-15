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
$document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false");

$path = JURI::base() . "components/com_jux_real_estate";
$document->addScript($path . '/templates/default/js/jquery.tools.min.js');
$document->addStyleSheet($path . '/templates/default/css/style.css');
$Itemid = JRequest::getInt('Itemid');

$document->addScript(JUri::base() . 'components/com_jux_real_estate/templates/default/js/bootstrap-tooltip.js');
?>
<?php
if (true) {
    $model = JUX_Real_EstateFactory::getModel('maprealty');
    $data = $model->getData();
}
?>
<?php
if ($this->params->get('show_page_title', 1)) {
    ?>
    <h3 class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php echo $this->params->get('page_title'); ?>
    </h3>
    <?php
}
?>
<?php
if ($this->params->get('bylocation')) {
    if ($latlong = ($this->params->get('location'))) {
	$latitude = $latlong[0];
	$longitude = $latlong[1];
    }
    $flag = true;
} else {
    if ($latlong = ($this->params->get('location'))) {
	$latitude = $latlong[0];
	$longitude = $latlong[1];
    }
    $flag = FALSE;
}
?>
<script type="text/javascript">
    var flag = '<?php echo $flag ?>';
    if (flag == true) {
	var latitude = '<?php echo $latitude; ?>';
	var longitude = '<?php echo $longitude; ?>';
    }
    var jp_side_bar = "";
    var jp_markers = [];

    function createMarker(map, infowindow, point, title, html, icon) {

	var image = new google.maps.MarkerImage("<?php echo JURI::base(); ?>" + "administrator/components/com_jux_real_estate/assets/icon/" + icon,
		new google.maps.Size(30, 34),
		new google.maps.Point(0, 0),
		new google.maps.Point(0, 32)
		);
	var marker = new google.maps.Marker({
	    position: point,
	    map: map,
	    icon: image,
	    title: title
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
	    zoom: <?php echo (int) $this->params->get('zoomlevel'); ?>,
	    zoomControlOptions: {
		style: google.maps.ZoomControlStyle.SMALL
	    },
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    streetViewControl: true
	};
	var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var full_address = "";
	if (navigator.geolocation) {
	    if (flag == true) {
		var pos = new google.maps.LatLng(latitude, longitude);
		map.setCenter(pos);
		var marker = new google.maps.Marker({
		    map: map,
		    position: pos,
		    title: 'You are here'
		});
		geocoder.geocode({'latLng': pos}, function(results) {
		    if (results[0]) {
			full_address = results[0].formatted_address;
			var saddr = full_address;
		    } else {
			alert('No results found');
		    }
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
		    geocoder.geocode({'latLng': pos}, function(results) {
			if (results[0]) {
			    full_address = results[0].formatted_address;
			    var saddr = full_address;
			    jQuery('body').append('<input type="hidden" id="constess" name="constess" value="' + full_address + '"/>');//                 
			} else {
			    alert('No results found');
			}
		    });
		});
	    }
	} else {
	    // Browser doesn't support Geolocation
	    map.innerHTML = "Geolocation is not supported by this browser.";
	}

	var infowindow = new google.maps.InfoWindow();
	var url = '<?php echo JUri::base(); ?>index.php?option=com_jux_real_estate&view=maprealty&layout=map&Itemid=<?php echo $Itemid; ?>';
	downloadUrl(url, function(data) {
	    var xml = data.responseXML;
	    var markers = xml.documentElement.getElementsByTagName("marker");
	    for (i = 0; i < markers.length; i++) {
		var icon = markers[i].getAttribute("icon");
		var latitude = markers[i].getAttribute("latitude");
		var longitude = markers[i].getAttribute("longitude");
		var html = markers[i].getAttribute("html");
		var to_address = markers[i].getAttribute("address");
		var title = markers[i].getAttribute("title");
		var id = markers[i].getAttribute("id");
		var url = markers[i].getAttribute("url");
		var price = markers[i].getAttribute("price");
		var description = markers[i].getAttribute("description");
		var typeid = markers[i].getAttribute("typeid");
		var cat_id = markers[i].getAttribute("cat_id");
		var point = new google.maps.LatLng(latitude, longitude);
		var fullAddress = jQuery('#constess').val();
		if (!fullAddress) {
		    fullAddress = '';
		}
		var htmls = '<table><tr><td colspan="2"><a href="' + url + '"><span style="font-size:16px;"><strong>' + title + '</strong></span></a></td></tr><tr><td valign="top" width="150"><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_PRICE'); ?></strong>:<span style="color:red;">' + price + '</span><br><?php if ($this->params->get('show_desc')) { ?><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_DESCRIPTION'); ?></strong>: <br>' + description + '<?php } ?></td><td valign="top">' + html + '</td></tr>';
		htmls += '<tr><td colspan="2">\n\
                            <span class="gs-dd-link" style="cursor: pointer"><?php echo JText::_('COM_JUX_REAL_ESTATE_DIRECTIONS'); ?></span>\n\
                               </td></tr>';
		htmls += '<tr>\n\
			    <td colspan="2">\n\
				<form action="http://maps.google.com/maps" method="get" target="_blank">\n\
				<ul id="menutab">\n\
				<li class="from">\n\
				<?php echo JText::_('COM_JUX_REAL_ESTATE_FROM_ADDRESS'); ?></li>\n\
				<br>\n\
				<div class="form-inline"><input type="text" value="' + fullAddress + '" name="saddr" id="infolocation1" class="input_text" />\n\
				<input type="hidden" name="daddr" id="infolocation2" value="' + to_address + '"/>\n\
				<input class="btn" type="submit" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_GO'); ?>" name="Gobtn" /></div></form></td></tr>'
		htmls += '</table>';
		var marker = createMarker(map, infowindow, point, title, htmls, icon);
		jp_markers.push(marker);
		jp_side_bar += ('-') + " ";
		jp_side_bar += '<a href="javascript:void(0);" onclick="jp_markerClicked(' + i + ');">' + title + '</a><br><br>';
		var el_sidebar = document.getElementById('side_bar');
		if (el_sidebar) {
		    el_sidebar.innerHTML = jp_side_bar;
		}
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
<script>
    $(document).ready(function() {
	$("#flip").hover(function() {
	    $("#side_bar").fadeIn("slow");
	    $("#side_bar").mouseleave(function() {
		$("#side_bar").fadeOut();
	    });
	});
	$("#side_bar").click(function() {
	    $("#side_bar").hide();
	});
    });
</script>

<style type="text/css"> 
    #side_bar,#flip
    {     
        padding-bottom: 10px;
        margin-left: 0px;        
        background-color:#e5eecc;
        border:solid 1px #c3c3c3;
    }
    #side_bar
    {
        padding:0px;
        display:none;
        z-index: 204;
        position: absolute; 
    }
</style>
</head>
<div class="row-fluid">
    <div class="col-xs-12 span12">
        <div id="icon_bar">
	    <?php
		if ($this->params->get('enable_iconbar')) {
		    foreach ($this->types as $type) {
			echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=list&typeid=' . $type->id . '&cat_id=0') . '"><img src="' . JURI::base() . 'administrator/components/com_jux_real_estate/assets/icon/' . $type->icon . '" width="25" height="25"/>  ' . $type->title . '</a>';
		    }
		}
	    ?>
        </div>
        <div class="row-fluid">
            <div class="col-xs-9 span9">
            </div>
	    <?php if ($this->params->get('enable_sidebar')): ?>
    	    <div class="col-xs-3" style="position: relative; width: 23%">
    		<div style="width: 100%" id="flip" align="right"><?php echo JText::_('COM_JUX_REAL_ESTATE_MAP_CLICK_TO_SELECT_REALTY');?></div>
    		<div  id="side_bar" style="width: 100% ;text-decoration: none;overflow: auto;height: <?php echo (int) $this->params->get('sidebar_height'); ?>px;">
    		</div>
    	    </div>
	    <?php endif; ?>
        </div>
    </div>
    <div class="col-xs-12" style="margin: 0; margin-right: 0px;">   
        <div id="map" style="height: <?php echo (int) $this->params->get('map_height'); ?>px; max-width: auto !important"></div>
    </div>      
</div>
