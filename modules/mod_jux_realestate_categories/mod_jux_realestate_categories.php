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

require_once (JPATH_SITE . '/'. 'components' . '/'. 'com_jux_real_estate' . '/'. 'defines.php');

// include the syndicate functions only once
require_once(dirname(__FILE__) . '/'. 'helper.php');

$count_realties = $params->get('count_realties', 1);

$helper = new ModJUXRealtyEstate_CategoriesHelper();
$rows = $helper->getCategories($params);
// TODO: Add assets here...
$live_site = JURI::base(true);
$document = JFactory::getDocument();

$document->addStyleSheet($live_site . '/modules/' . $module->module . '/assets/css/style.css');
require(JModuleHelper::getLayoutPath($module->module));