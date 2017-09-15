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
// Include dependancies
jimport('joomla.application.component.controller');
//load asset
$document = JFactory::getDocument();
JHtml::_('jquery.framework');

require_once(JPATH_COMPONENT . '/' . 'defines.php');
// add assets
$live_site = JURI::base(true);
$doc = JFactory::getDocument();
$doc->addScriptDeclaration("var jp_live_site = '$live_site';");
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/jux-responsivestyle.css');
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/jux-font-awesome.css');
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/font-awesome.min.css');
$doc->addScript(JURI::base(true) . '/components/com_jux_real_estate/assets/js/jquery.matchHeight.js');
$doc->addScript(JURI::base(true) . '/components/com_jux_real_estate/assets/js/tmpl.js');
if (JVERSION < '3.0.0') {
    $doc->addStyleSheet($live_site . '/components/com_jux_real_estate/assets/css/bootstrap.css');
    $doc->addStyleSheet($live_site . '/components/com_jux_real_estate/assets/css/bootstrap-responsive.css');
    $doc->addScript($live_site . '/components/com_jux_real_estate/assets/js/bootstrap-tooltip.js');
    $doc->addScript($live_site . '/components/com_jux_real_estate/assets/js/bootstrap.min.js');
    $doc->addScript($live_site . '/components/com_jux_real_estate/assets//js/jquery.min.js');
}
JUX_Real_EstateTemplate::addStyleSheet('jux_real_estate');
JUX_Real_EstateTemplate::addScript('jux_real_estate');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'tables');
JHtml::addIncludePath(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers');
// require the base controller
require_once(JPATH_COMPONENT . '/' . 'controller.php');

// Get an instance of the controller prefixed by JUX_Real_Estate
$controller = JControllerLegacy::getInstance('JUX_Real_Estate');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>