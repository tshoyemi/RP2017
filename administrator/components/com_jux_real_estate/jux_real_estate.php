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
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jux_real_estate')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
if (JVERSION >= '3.2.0') {
    JHtml::_('behavior.tabstate');
}

// add assets
$admin_live_site = JURI::base(true);
$live_site = JUri::root();

$doc = JFactory::getDocument();
$doc->addStyleSheet($admin_live_site . '/components/com_jux_real_estate/assets/css/jux_real_estate.css');
$doc->addScriptDeclaration("var jse_admin_live_site = '$admin_live_site';");
$doc->addScriptDeclaration("var jse_live_site = '$live_site';");
$doc->addScript($admin_live_site . '/components/com_jux_real_estate/assets/js/jux_real_estate.js');
$doc->addScript($admin_live_site . '/components/com_jux_real_estate/assets/js/jse_digital_store.js');

define('JUX_REAL_ESTATE_IMG', JURI::root() . 'components/com_jux_real_estate/libraries/image.php');
require_once(JPATH_COMPONENT . '/' . 'libraries' . '/' . 'factory.php');
require_once(JPATH_COMPONENT . '/' . 'libraries' . '/' . 'fields.php');
require_once(JPATH_COMPONENT . '/' . 'libraries' . '/' . 'image.php');
require_once (JPATH_COMPONENT_SITE . '/' . 'helpers' . '/' . 'query.php');

// Register helper class
JLoader::register('JUX_Real_EstateHelper', dirname(__FILE__) . '/' . 'helpers' . '/' . 'jux_real_estate.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by JUX_Real_Estate
$controller = JControllerLegacy::getInstance('JUX_Real_Estate');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();