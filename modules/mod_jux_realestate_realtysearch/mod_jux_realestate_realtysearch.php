<?php
/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Include the search functions only once
require_once (dirname(__FILE__) . '/' . 'helper.php');
$layout = $params->get('layout', 'vertical');

// load css and javascript
if(isset($layout) && $layout == 'horizontal'){
    JUX_Real_EstateAjaxSearch::css_horizontal();
} else {
    JUX_Real_EstateAjaxSearch::css();
}
JUX_Real_EstateAjaxSearch::javascript();

$extrafield = $params->get('extrafield', 1);
$advance = $params->get('advance', 1);
$toggle = $params->get('toggle', 1);
$bycurrency = $params->get('bycurrency', 1);
if ($extrafield) {
    // Include all the extra field function onlu one
    require_once (JPATH_ROOT . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'libraries' . '/' . 'fields.php');

    // Render all extra field
    $ZJFields = new JUX_Real_EstateAjaxSearch();
    if ($ZJFields->getSearchTotal()) {
        if ($layout == 'vertical') {
            $fields = $ZJFields->renderCustomSearchFields();
        } else if ($layout == 'horizontal') {
            $fields = $ZJFields->renderCustomSearchFieldsHorizontal();
        }
    }
}

$Itemid = JRequest::getCmd('Itemid', 9999);

$items = new JUX_Real_EstateAjaxSearch;
$mods = $items->getMod();
$type_id = $items->getType();

$country_id = $items->getCountry();
$state_id   = $items->getStates();

$category = $items->getCategory();

$currency = $items->getCurrency();

$new_price      = explode(',',$params->get('new_price'));
$new_min_price  = $new_price[0] ? $new_price[0] : 0;
$new_max_price  = $new_price[1] ? $new_price[1] : 10000;
$about_price    = explode(',', $params->get('about_price'));
$start_price    = (isset($_REQUEST['price_slider_lower'])) ? $_REQUEST['price_slider_lower'] : ($about_price[0] ? $about_price[0] : 200);
$end_price      = (isset($_REQUEST['price_slider_upper'])) ? $_REQUEST['price_slider_upper'] : ($about_price[1] ? $about_price[1] : 5000);
$new_currencies = $params->get('currencies');

$new_area       = explode(',', $params->get('new_area'));
$new_min_area   = $new_area[0] ? $new_area[0] : 0;
$new_max_area   = $new_area[1] ? $new_area[1] : 500;
$about_price    = explode(',', $params->get('about_area'));
$start_area     = (isset($_REQUEST['area_slider_lower'])) ? $_REQUEST['area_slider_lower'] : ($about_price[0] ? $about_price[0] : 30);
$end_area       = (isset($_REQUEST['area_slider_upper'])) ? $_REQUEST['area_slider_upper'] : ($about_price[1] ? $about_price[1] : 400);

$types_advance  = $params->get('types_advance');
$cat_advance    = $params->get('cat_advance');
$country_advance= $params->get('country_advance');
$states_advance = $params->get('states_advance');
$agent_advance  = $params->get('agent_advance');
$beds_advance   = $params->get('beds_advance');
$baths_advance  = $params->get('baths_advance');

if($new_currencies){
    $new_currencies = '$';
}else{
    $new_currencies = '€';
}

if($advance) {
    if(isset($bydate) && $bydate) {
        $fromdate	= $items->getDate('fromdate', 'From');
        $todate		= $items->getDate('todate', 'To'); 
    }  
}
$field_search = $items->getFieldSearch();

require(JModuleHelper::getLayoutPath('mod_jux_realestate_realtysearch', $layout));
?>

