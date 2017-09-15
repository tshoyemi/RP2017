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
jimport('joomla.application.component.helper');
require_once(JPATH_SITE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'html.helper.php');

abstract class JUX_Real_EstateHelperRoute {

    protected static $lookup;

    //agent realties
    public static function getAgentRealtyRoute($id) {
	$needles = array(
	    'agentrealties' => array((int) $id),
	    'agents' => array(),
	    'list' => array()
	);

	$id = (self::_sef()) ? $id : (int) $id;
	$link = 'index.php?option=com_jux_real_estate&view=agentrealties&id=' . $id;

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //category realties
    function getCategoryRealtyRoute($cat_id) {
	$needles = array(
	    'category' => array((int) $cat_id),
	    'list' => array()
	);

	$cat_id = (self::_sef()) ? $cat_id : (int) $cat_id;
	$link = 'index.php?option=com_jux_real_estate&view=category&id=' . $cat_id;

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //agents
    function getAgentsRoute() {
	$needles = array(
	    'agents' => array(),
	    'home' => array(),
	    'list' => array()
	);

	$link = 'index.php?option=com_jux_real_estate&view=agents';

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //all realties
    function getAllRealtiesRoute() {
	$needles = array(
	    'list' => array(),
	    'home' => array()
	);

	$link = 'index.php?option=com_jux_real_estate&view=list';

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //category
    function getCategoryRoute($cat_id = 0) {
	$needles = array(
	    'category' => array((int) $cat_id),
	    'list' => array()
	);

	$cat_id = (self::_sef()) ? $cat_id : (int) $cat_id;
	$link = 'index.php?option=com_jux_real_estate&view=category&id=' . $cat_id;

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //companies
    function getCompaniesRoute() {
	$needles = array(
	    'companies' => array(),
	    'list' => array()
	);

	$link = 'index.php?option=com_jux_real_estate&view=companies';

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //company agents
    

    //home
    function getHomeRoute() {
	$needles = array(
	    'home' => array(),
	    'list' => array()
	);

	$link = 'index.php?option=com_jux_real_estate&view=list';

	return $link;
    }

    function getHomeTagsRoute($tags) {
	$needles = array(
	    'home' => array(),
	    'list' => array()
	);
	if ($tags) {
	    $link = 'index.php?option=com_jux_real_estate&view=list&tags=' . $tags;
	} else {
	    $link = 'index.php?option=com_jux_real_estate&view=list';
	}

	return $link;
    }

    //realty
    function getRealtyRoute($id, $cat) {

	$needles = array(
	    'realty' => array((int) $id),
	);

	//Create the link
	$id = (self::_sef()) ? $id : (int) $id;
	$link = 'index.php?option=com_jux_real_estate&view=realty&id=' . $id;

	if ($item = self::_findItem($needles)) {
	    $link .= '&catID=' . $cat;
	} elseif ($item = self::_findItem()) {
	    $link .= '&catID=' . $cat;
	}

	return $link;
    }

    protected static function _findItem($needles = null) {
	$app = JFactory::getApplication();
	$menus = $app->getMenu('site');

	// Prepare the reverse lookup array.
	if (self::$lookup === null) {
	    self::$lookup = array();

	    $component = JComponentHelper::getComponent('com_jux_real_estate');
	    $items = $menus->getItems('component_id', $component->id);
	    foreach ($items as $item) {
		if (isset($item->query) && isset($item->query['view'])) {
		    $view = $item->query['view'];
		    if (!isset(self::$lookup[$view])) {
			self::$lookup[$view] = array();
		    }
		    if (isset($item->query['id'])) {
			self::$lookup[$view][$item->query['id']] = $item->id;
		    } else {
			self::$lookup[$view] = $item->id;
		    }
		}
	    }
	}

	if ($needles) {
	    foreach ($needles as $view => $ids) {
		if (isset(self::$lookup[$view])) {
		    if (empty($ids)) {
			return self::_getBasic(self::$lookup[$view], $view);
		    } else {
			foreach ($ids as $id) {
			    //echo '----------->'.$id;
			    if (isset(self::$lookup[$view][(int) $id])) {
				//echo '======>'.self::$lookup[$view][(int)$id];
				return self::_getBasic(self::$lookup[$view][(int) $id], $view, $id);
			    }
			}
		    }
		}
	    }
	} else {
	    $active = $menus->getActive();
	    if ($active) {
		return $active->id;
	    }
	}

	return null;
    }

    public static function _sef() {
	$config = JFactory::getConfig();
	//return $config->getValue('config.sef');
	return $config->get('config.sef');
    }

    function _getBasic($itemid, $view, $id = null) {
	$db = JFactory::getDbo();
	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();
	$app = JFactory::getApplication();
	$menus = $app->getMenu('site');

	//looking for links containing:
	$link = 'index.php?option=com_jux_real_estate&view=' . $view;
	if ($id)
	    $link .= '&id=' . $id;

	//search menu table for duplicate menu item types
	$query = $db->getQuery(true);
	$query->select('id')
		->from('#__menu')
		->where('link LIKE ' . $db->Quote('%' . $db->getEscaped($link, true) . '%', false))
		->where('published = 1');
	if (is_array($groups) && !empty($groups)) {
	    $query->where('access IN (' . implode(",", $groups) . ')');
	}

	$db->setQuery($query);

	if ($result = $db->loadObjectList()) {
	    if (count($result) > 1) {

		$basic = array();

		return $basic;
	    } else {
		return $itemid;
	    }
	}

	//return the originally looked up itemid
	return $itemid;
    }

}

?>
