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

// include the syndicate functions only once
require_once(dirname(__FILE__) . '/' . 'helper.php');

$helper = new ModJUXRealtyEstate_TypesHelper();
$rows = $helper->getTypes($params);

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base(true) . '/modules/' . $module->module . '/assets/css/style.css');
require(JModuleHelper::getLayoutPath($module->module));