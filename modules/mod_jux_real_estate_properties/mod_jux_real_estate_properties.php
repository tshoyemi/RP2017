<?php

/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate Properties
 * @copyright	Copyright (C) 2015 JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__) . '/' . 'helper.php');
require_once (JPATH_SITE . '/'. 'components' . '/'. 'com_jux_real_estate' . '/'. 'defines.php');
/* load javascript. */
mod_RealtyEstatePropertiesHelper::javascript($params);

/* load css. */
mod_RealtyEstatePropertiesHelper::css();

$realties = mod_RealtyEstatePropertiesHelper::getdata($params);
$customcss = 'modules/mod_jux_real_estate_properties/assets/css/style/custom-' . $module->id . '.css';
if (mod_JUX_Real_Estate_Properties_Helper::getCssProcessor($params, $customcss, '#jux_real_estate_properties' . $module->id)) {
	$document = JFactory::getDocument();
	$document->addStyleSheet($customcss);
}
$layout_style = $params->get('layout_style', 'fullwidth');

// require (JModuleHelper::getLayoutPath('mod_jux_real_estate_properties', $layout));
require (JModuleHelper::getLayoutPath('mod_jux_real_estate_properties',$params->get('layout', 'default')));
