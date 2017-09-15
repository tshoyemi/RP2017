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

// Include the syndicate functions only once
require_once( dirname(__FILE__) . '/' . 'helper.php' );

$document = JFactory::getDocument();
$document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false");

/* load css. */
mod_JUX_RealEstate_MapRealtyHelper::css();

$rows = mod_JUX_RealEstate_MapRealtyHelper::getdata($params);
$types = mod_JUX_RealEstate_MapRealtyHelper::getTypes($params);
$data = mod_JUX_RealEstate_MapRealtyHelper::getMakers($rows, $params);

require JModuleHelper::getLayoutPath('mod_jux_realestate_maprealty');
?>