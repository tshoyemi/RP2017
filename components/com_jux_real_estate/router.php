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
require_once (JPATH_ROOT . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'query.php');

function JUX_Real_EstateBuildRoute(& $query) {
    $segments = array();

    $app = JFactory::getApplication();
    $menu = $app->getMenu();

    if (empty($query['Itemid'])) {
	$menuItem = $menu->getActive();
    } else {
	$menuItem = $menu->getItem($query['Itemid']);
    }

    $mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
    $mLayout = (empty($menuItem->query['layout'])) ? null : $menuItem->query['layout'];


    if (isset($query['view'])) {
	$view = $query['view'];
	$segments[] = $view;
	if ($view == 'myrealty') {
	    if (isset($query['tab'])) {
		$segments[] = $query['tab'];
		unset($query['tab']);
	    }
	}
	unset($query['view']);
    }

    // layout
    if (isset($query['layout'])) {
	if ($query['layout'] == $mLayout) {
	    $segments[] = $query['layout'];
	    unset($query['layout']);
	}
    }

    if (isset($query['id']) && isset($view) && ($view != 'message')) {

	$id = $query['id'];
	$segments[] = $id;
	unset($query['id']);
    }

    if (isset($query['typeid'])) {

	if (strpos($query['typeid'], ':') === false) {
	    $db = JFactory::getDbo();
	    $db->setQuery($db->getQuery(true)
			    ->select('title')
			    ->from('#__re_types')
			    ->where('id=' . (int) $query['typeid'])
	    );
	    $title = $db->loadResult();
	    if ($title) {
		$title = JFilterOutput::stringURLSafe($title);
		$query['typeid'] = $query['typeid'] . ':' . $title;
	    }
	}

	$segments[] = $query['typeid'];
	unset($query['typeid']);
    }
    if (isset($query['cat_id'])) {

	if (strpos($query['cat_id'], ':') === false) {
	    $db = JFactory::getDbo();
	    $db->setQuery($db->getQuery(true)
			    ->select('title')
			    ->from('#__categories')
			    ->where('id=' . (int) $query['cat_id'])
	    );
	    $title = $db->loadResult();
	    if ($title) {
		$title = JFilterOutput::stringURLSafe($title);
		$query['cat_id'] = $query['cat_id'] . ':' . $title;
	    }
	}

	$segments[] = $query['cat_id'];
	unset($query['cat_id']);
    }


    return $segments;
}

function JUX_Real_EstateParseRoute($segments) {
    $vars = array();
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    $item = $menu->getActive();
    $vars['view'] = $segments[0];
    switch ($segments[0]) {
	case 'realty':
	case 'form':
	case 'agentrealties':
	case 'category':
	    if (isset($segments[1])) {
		if (strpos($segments[1], ':') == true) {
		    list($id, $alias) = explode(':', $segments[1], 2);

		    $segments[1] = $id;
		}
		$vars['id'] = $segments[1];
	    }
	    break;
	case 'list' :

	    if (isset($segments[1]) && $segments[1] == 'table') {
		if (isset($segments[2]) && isset($segments[3])) {
		    if (strpos($segments[2], ':') === true) {
			$segments[2] = str_replace(':', '-', $segments[2]);
		    }
		    if (strpos($segments[3], ':') === true) {
			$segments[3] = str_replace(':', '-', $segments[3]);
		    }

		    $vars['typeid'] = $segments[2];
		    $vars['cat_id'] = $segments[3];
		}
		$vars['layout'] = 'table';
	    } else {
		if (strpos($segments[1], ':') === true) {
		    $segments[1] = str_replace(':', '-', $segments[1]);
		}
		if (strpos($segments[2], ':') === true) {
		    $segments[2] = str_replace(':', '-', $segments[2]);
		}
		$vars['typeid'] = $segments[1];
		$vars['cat_id'] = $segments[2];
	    }

	    break;
	case 'myrealty':
	    if (isset($segments[1])) {
		$vars['tab'] = $segments[1];
	    }
	    if (isset($segments[2])) {
		$vars['id'] = $segments[2];
	    }
	    break;
    }

    return $vars;
}
 