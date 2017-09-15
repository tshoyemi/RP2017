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

/**
 * JUX_Real_Estate Component - Route Library.
 * @package        JUX_Real_Estate
 * @subpackage    Library
 */
class JUX_Real_EstateRoute {

    /**
     * Get Itemid for component.
     */
    public static function getItemid($view = null, $id = null, $url = null) {
	$route_model = JUX_Real_EstateFactory::getModel('route');

	return $route_model->getItemid($view, $id, $url);
    }

    /**
     * Translates an internal Joomla URL to a humanly readible URL.
     */
    public static function _($url, $xhtml = true, $ssl = null) {
	// parse the url to get the view parameter
	parse_str($url);
	$view = !empty($view) ? $view : 'list';
	$id = !empty($id) ? $id : null;

	return JRoute::_($url, $xhtml, $ssl);
    }

    /**
     * Get current uri.
     */
    public static function getURI($encode = true) {
	// get request uri
	$uri = JRequest::getVar('REQUEST_URI', '', 'server', 'string');

	// encode the uri
	$uri = !$encode ? $uri : base64_encode($uri);

	return $uri;
    }

    /*
     * Check home
     */

    public static function checkHome($Itemid = 0) {
	$application = JFactory::getApplication();
	$menu = $application->getMenu();
	$item = $menu->getItem($Itemid);
	$home = false;
	if (is_object($item)) {
	    if ($item->home) {
		$home = true;
	    }
	}
	return $home;
    }

}