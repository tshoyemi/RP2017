<?php
/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate Ouragents
 * @copyright	Copyright (C) 2015 JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once( dirname(__FILE__) . '/' . 'helper.php' );
/* load javascript. */
mod_RealtyEstateOuragentsHelper::javascript($params);
/* load css. */
$text=$params->get('text_image');

mod_RealtyEstateOuragentsHelper::css($text);
$agents = mod_RealtyEstateOuragentsHelper::getdata($params);
$customcss = 'modules/mod_jux_real_estate_ouragents/assets/css/style/custom-'.$module->id.'.css';

if (mod_JUX_Real_Estate_Ouragents_Helper::getCssProcessor($params,$customcss,'#jux_real_estate_ouragents'.$module->id)){
	$document = JFactory::getDocument();
	$document->addStyleSheet($customcss);
}
require( JModuleHelper::getLayoutPath('mod_jux_real_estate_ouragents', $params->get('layout', 'default')) );