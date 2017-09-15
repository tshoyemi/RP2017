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

class JUX_Real_EstateHelperRoute {

    protected static $lookup;

    //agent realties
    public static function getAgentRealtyRoute($id) {
	$needles = array(
	    'agentrealties' => array((int) $id),
	    'agentrealties' => array(),
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

    //category
    static function getCategoryRoute($id) {
	$needles = array(
	    'category' => array((int) $id),
	    'list' => array()
	);

	$id = (self::_sef()) ? $id : (int) $id;
	$link = 'index.php?option=com_jux_real_estate&view=realties&cat_id=' . $id;

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    static function getTypeRoute($id) {
	$needles = array(
	    'type' => array((int) $id),
	    'list' => array()
	);
	$id = (self::_sef()) ? $id : (int) $id;
	$link = 'index.php?option=com_jux_real_estate&view=realties&type_id=' . $id;
	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //home
    function getHomeRoute() {
	$needles = array(
	    'home' => array(),
	    'list' => array()
	);

	$link = 'index.php?option=com_jux_real_estate&view=list';

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
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

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    //realty
    public static function getRealtyRoute($id) {
	$needles = array(
	    'realty' => array((int) $id),
	    'realty' => array(),
	    'list' => array()
	);

	//Create the link
	$id = (self::_sef()) ? $id : (int) $id;
	$link = 'index.php?option=com_jux_real_estate&view=realty&id=' . $id;

//        var_dump(self::_findItem($needles));
	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    public static function getRealtySearchRoute() {
	$needles = array(
	    'realties' => array(),
	    'list' => array()
	);

	//Create the link
	$link = 'index.php?option=com_jux_real_estate&view=realties';
	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    public static function getRealtyItemid() {
	$needles = array(
	    'realties' => array(),
	    'list' => array()
	);

	//Create the link
	$link = 'index.php?option=com_jux_real_estate&view=realties';
	if ($item = self::_findItem($needles)) {
	    return $item;
	} elseif ($item = self::_findItem()) {
	    return $item;
	}
	return $item;
    }

    public static function getAgentPlan() {
	$needles = array(
	    'agentplan' => array(),
	    'list' => array()
	);

	//Create the link
	$link = 'index.php?option=com_jux_real_estate&view=agentplan';

	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
	}
	return $link;
    }

    public static function getMyProperty() {
	$needles = array(
	    'myrealty' => array(),
	    'list' => array()
	);

	//Create the link
	$link = 'index.php?option=com_jux_real_estate&view=myrealty';
	if ($item = self::_findItem($needles)) {
	    $link .= '&Itemid=' . $item;
	} elseif ($item = self::_findItem()) {
	    $link .= '&Itemid=' . $item;
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
	return $config->get('sef');
    }

    public static function _getBasic($itemid, $view, $id = null) {
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
		->where("link LIKE '%" . $link . "%'")
		->where('published = 1');
	if (is_array($groups) && !empty($groups)) {
	    $query->where('access IN (' . implode(",", $groups) . ')');
	}

	$db->setQuery($query);
	if ($result = $db->loadObjectList()) {
	    if (count($result) >= 1) {

		return $result[0]->id;
	    } else {
		return $itemid;
	    }
	}

	//return the originally looked up itemid
	return $itemid;
    }

}

?>
