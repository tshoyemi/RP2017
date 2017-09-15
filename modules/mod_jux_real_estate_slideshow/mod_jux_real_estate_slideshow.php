<?php

/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate slideshow
 * @copyright	Copyright (C) 2015 JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__) . '/' . 'helper.php');

/* load javascript. */
mod_RealtyEstateSlideShowHelper::javascript();

/* load css. */
mod_RealtyEstateSlideShowHelper::css();

$count_limit = $params->get('count_limit');

$realties = mod_RealtyEstateSlideShowHelper::getData($params);
require (JModuleHelper::getLayoutPath('mod_jux_real_estate_slideshow', $params->get('layout', 'default')));
