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
 * JUX_Real_Estate Component Query Helper
 *
 * @static
 * @package        Joomla
 * @subpackage    JUX_Real_Estate
 * @since 3.0
 */
class JUX_Real_EstateHelperQuery extends JObject {

    public static function orderbyPrimary($orderby) {
	switch ($orderby) {
	    case 'date':
		$orderby = 'r.date_created';
		break;

	    case 'rdate':
		$orderby = 'r.date_created DESC';
		break;

	    case 'alpha':
		$orderby = 'r.title';
		break;

	    case 'ralpha':
		$orderby = 'r.title DESC';
		break;

	    case 'order':
		$orderby = 'r.ordering';
		break;

	    case 'rorder':
		$orderby = 'r.ordering DESC';
		break;

	    case 'price':
		$orderby = 'r.price';
		break;

	    case 'rprice':
		$orderby = 'r.price DESC';
		break;

	    default:
		$orderby = 'r.ordering';
		break;
	}
	return $orderby;
    }

    public static function orderbyCategory($orderby) {
	switch ($orderby) {
	    case 'default':
		$orderby = ', c.ordering';
		break;

	    case 'alpha':
		$orderby = ', c.title';
		break;

	    case 'ralpha':
		$orderby = ', c.title DESC';
		break;

	    case 'order':
		$orderby = ', c.ordering';
		break;

	    case 'rorder':
		$orderby = ', c.ordering DESC';
		break;

	    default:
		$orderby = '';
		break;
	}

	return $orderby;
    }

    public static function orderbyType($orderby) {
	switch ($orderby) {
	    case 'default':
		$orderby = ', t.ordering';
		break;

	    case 'alpha':
		$orderby = ', t.title';
		break;

	    case 'ralpha':
		$orderby = ', t.title DESC';
		break;

	    case 'order':
		$orderby = ', t.ordering';
		break;

	    case 'rorder':
		$orderby = ', t.ordering DESC';
		break;

	    default:
		$orderby = '';
		break;
	}

	return $orderby;
    }

    function getImageSlide($realty_id) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);

	$query->select('*')->from('#__re_files')->where('realty_id= ' . (int) $realty_id);

	$db->setQuery($query);
	$result = $db->loadObjectList();
	return $result;
    }

    function getCategories($parent = null) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('id,title')->from('#__categories')->where('published = 1')->where('extension = "com_jux_real_estate"');
	$query->order('title');

	return $query;
    }

    function getAvailableCategories($id = null) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('cat_id')->from('#__re_realtyamid')

		//->where('amen_id = 0')
		->where('realty_id = ' . (int) $id);

	$db->setQuery($query);
	$result = $db->loadResultArray();

	return $result;
    }

    public static function getFileImageList($realty_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('id,path_image')->from('#__re_realtie_images')->where('realty_id = ' . (int) $realty_id)->where('realty_id != 0')->where('published = 1');
	$db->setQuery($query);

	//echo $query;
	$result = $db->loadObjectList();
	return $result;
    }

    public static function getAgentName($agent_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('id, username')->from('#__re_agents')->where('id = ' . (int) $agent_id);
	$db->setQuery($query, 0, 1);
	$result = $db->loadObject();
	return $result->username;
    }

    public static function getAgentEmail($agent_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('email')->from('#__re_agents')->where('id = ' . (int) $agent_id);

	$db->setQuery($query, 0, 1);
	$result = $db->loadResult();
	return $result;
    }

    public static function getAgents($id = null, $order = 'last_name ASC', $limit = 999999) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('a.*')->from('#__re_agents AS a')->leftJoin('#__re_realties AS r ON r.agent_id = a.id')->where('r.id = ' . (int) $id)->where('a.id = r.agent_id')->where('a.published = 1')->order($order)->group('a.id');

	$db->setQuery($query, 0, $limit);
	$result = $db->loadObjectList();

	return $result;
    }

    public static function getAvailableAgents($id = null, $order = 'username ASC', $limit = 999999) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('a.* ')->from('#__re_agents AS a')->leftJoin('#__re_realties AS r ON r.agent_id = a.id')->where('r.id = ' . (int) $id)->where('a.id = r.agent_id')->where('a.published = 1')->order($order)->group('a.id');

	$db->setQuery($query, 0, $limit);
	$result = $db->loadObjectList();

	return $result;
    }

    function getFullAddress($street = null, $city = null, $locstate = null, $province = null, $zip = null, $country_id = null) {
	$fulladdress = '';
	$fulladdress.= ($street) ? '<span>' . $street . '</span>, ' : '';
	$fulladdress.= ($city) ? $city : '';
	$fulladdress.= ($locstate) ? ', ' . JUX_Real_EstateHelperQuery::getStateName($locstate) : '';
	$fulladdress.= ($province) ? ', ' . $province : '';
	$fulladdress.= ($zip) ? ' ' . $zip : '';
	$fulladdress.= ($country_id) ? ', ' . JUX_Real_EstateHelperQuery::getCountryName($country_id) : '';
	return $fulladdress;
    }

    

    function getRealty($id) {
	$db = JFactory::getDbo();
	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();

	// Filter by start and end dates.
	$nullDate = $db->Quote($db->getNullDate());
	$date = JFactory::getDate();
	$nowDate = $db->Quote($date->toSql());

	$query = $db->getQuery(true);
	$query->select('r.*')->from('#__re_realties as r')->where('r.id = ' . (int) $id)->where('r.published = 1')->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');

	$db->setQuery($query, 0, 1);
	$result = $db->loadObject();

	return $result;
    }

    function getRealtyName($realty_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('title')->from('#__re_realties')->where('id = ' . (int) $realty_id);

	$db->setQuery($query, 0, 1);
	$result = $db->loadResult();

	return $result;
    }

    public static function getCountryName($country_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('name')->from('#__re_countries')->where('id = ' . (int) $country_id);

	$db->setQuery($query, 0, 1);
	$result = $db->loadResult();

	return $result;
    }

    public static function getStateName($state_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('state_name')->from('#__re_states')->where('id = ' . (int) $state_id);

	$db->setQuery($query, 0, 1);
	$result = $db->loadResult();

	return $result;
    }

    public static function getRealtiesList($tag = null, $attrib = null, $sel = null, $list = false, $isAgent = false) {
	$db = JFactory::getDbo();
	$user = JFactory::getUser();
	$user_id = $user->id;
	$groups = $user->getAuthorisedViewLevels();
	$isAdmin = $user->get('isRoot');

	// Filter by start and end dates.
	$nullDate = $db->Quote($db->getNullDate());
	$date = JFactory::getDate();
	$nowDate = $db->Quote($date->toSql());
	$realty_id = JRequest::getInt('realty_id');
	$realties = array();

	$query = $db->getQuery(true);

	$query->select('id AS value, CONCAT(title) AS text')->from('`#__re_realties`');

	(is_array($groups) && !empty($groups)) ? $query->where('access IN (' . implode(",", $groups) . ')') : '';
	if ($isAdmin) {
	    
	}
	else
	    $query->where('user_id =' . (int) $user_id);

	$query->where('published = 1')->where('(publish_up = ' . $nullDate . ' OR publish_up <= ' . $nowDate . ')')->where('(publish_down = ' . $nullDate . ' OR publish_down >= ' . $nowDate . ')')->order('street_num, street ASC');
	if ($realty_id == null) {
	    $realties[] = JHTML::_('select.option', '', '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_A_REALTY') . ' -', "value", "text");
	} else {
	    $query->where('id=' . (int) $realty_id);
	}
	$db->setQuery($query);
	$realties = array_merge($realties, $db->loadObjectList());
	if ($list) {
	    return $realties;
	} else {
	    return JHTML::_('select.genericlist', $realties, 'realty_id', $attrib, "value", "text", $sel);
	}
    }

    public static function getRealtyCats($id = null) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('cat_id')->from('#__re_realtyamid')->where('amen_id = 0')->where('realty_id = ' . (int) $id);

	$db->setQuery($query);
	$result = $db->loadResultArray();

	return $result;
    }

    // Built Category select list
    public static function getCategoryList($tag, $attrib, $selected = null, $available = false, $list = false) {
	$db = JFactory::getDbo();
	$categories = array();
	$categories[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_ALL_STATUS'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(c.id), c.id AS value, c.title AS text')->from('#__categories as c')->where('c.published = 1 and c.extension = "com_jux_real_estate"');
	$query->order('c.title ASC');

	$db->setQuery($query);
	$result = $db->loadObjectList();
	foreach ($result as $r) {
	    $categories[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}
	if ($list) {
	    return $categories;
	} else {
	    return JHTML::_('select.genericlist', $categories, $tag, $attrib, "value", "text", $selected);
	}
    }

    // Build Type select list
    public static function getTypesList($tag, $attrib, $selected = null, $available = false, $list = false) {
	$db = JFactory::getDbo();
	$types = array();
	$types[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_ALL_TYPES'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(t.id), t.id AS value, t.title AS text')->from('#__re_types as t')->where('t.published = 1');
	if ($available) {
	    $query->join('INNER', '#__re_realties AS r ON r.type_id = t.id');
	}
	$query->order('t.title ASC');
	$db->setQuery($query);
	$result = $db->loadObjectList();
	foreach ($result as $r) {
	    $types[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}
	if ($list) {
	    return $types;
	} else {

	    return JHTML::_('select.genericlist', $types, $tag, $attrib, "value", "text", $selected);
	}
    }

    // build OrderList realty
    function buildOrderList($filter_order_dir, $attrib = '', $list = false, $tag = false) {
	$orderbys = array();
	$orderbys[] = JHTML::_('select.option', 'ASC', JText::_('COM_JUX_REAL_ESTATE_ASCENDING'));
	$orderbys[] = JHTML::_('select.option', 'DESC', JText::_('COM_JUX_REAL_ESTATE_DESCENDING'));

	$tag = ($tag) ? $tag : 'filter_order_dir';

	if ($list) {
	    return $orderbys;
	} else {
	    return JHTML::_('select.genericlist', $orderbys, $tag, $attrib, 'value', 'text', $filter_order_dir);
	}
    }

    function buildSortList($filter_order, $attrib = '', $tag = false, $list = false) {

	$sortbys = array();

	$sortbys[] = JHTML::_('select.option', 'r.street', JText::_('COM_JUX_REAL_ESTATE_STREET'));
	$sortbys[] = JHTML::_('select.option', 'r.beds', JText::_('COM_JUX_REAL_ESTATE_BEDS'));
	$sortbys[] = JHTML::_('select.option', 'r.baths', JText::_('COM_JUX_REAL_ESTATE_BATHS'));
	$sortbys[] = JHTML::_('select.option', 'r.sqft', JText::_('COM_JUX_REAL_ESTATE_SQFTDD'));
	$sortbys[] = JHTML::_('select.option', 'r.price', JText::_('COM_JUX_REAL_ESTATE_PRICE'));
	$sortbys[] = JHTML::_('select.option', 'r.date_created', JText::_('COM_JUX_REAL_ESTATE_LISTED_DATE'));
	$sortbys[] = JHTML::_('select.option', 'r.modified', JText::_('COM_JUX_REAL_ESTATE_MODIFIED_DATE'));

	$tag = ($tag) ? $tag : 'filter_order';

	if ($list) {
	    return $sortbys;
	} else {
	    return JHTML::_('select.genericlist', $sortbys, $tag, $attrib, 'value', 'text', $filter_order);
	}
    }

    function buildAgentSortList($filter_order, $attrib = '', $tag = false, $list = false) {
	$sortbys = array();
	$sortbys[] = JHTML::_('select.option', 'a.ordering', JText::_('COM_JUX_REAL_ESTATE_SELECT'));
	$sortbys[] = JHTML::_('select.option', 'a.last_name', JText::_('COM_JUX_REAL_ESTATE_LAST_NAME'));
	$sortbys[] = JHTML::_('select.option', 'a.first_name', JText::_('COM_JUX_REAL_ESTATE_FIRST_NAME'));
	

	$tag = ($tag) ? $tag : 'filter_order';

	if ($list) {
	    return $sortbys;
	} else {
	    return JHTML::_('select.genericlist', $sortbys, $tag, $attrib, 'value', 'text', $filter_order);
	}
    }

    

    public static function getChildren($id) {
	$db = JFactory::getDbo();
	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();

	$query = $db->getQuery(true);
	$query->select('*')->from('#__categories')->where('published = 1');
	if (is_numeric($id)) {
	    $query->where('id = ' . (int) $id);
	}
	if (is_array($groups) && !empty($groups)) {
	    $query->where('access IN (' . implode(",", $groups) . ')');
	}
	$query->order('title');

	$db->setQuery($query);
	return $db->loadObjectList();
    }

    public static function getAgent($agent_id) {
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);
	$query->select('a.*, a.alias as alias')->from('#__re_agents as a')->where('a.id = ' . (int) $agent_id)->where('a.published = 1');

	$db->setQuery($query, 0, 1);
	return $db->loadObject();
    }

    function getCountryList($tag, $attrib, $selected = null, $available = false, $list = false) {
	$db = JFactory::getDbo();
	$countries = array();
	$countries[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_COUNTRY'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(c.id), c.id AS value, c.name AS text')->from('#__re_countries as c');
	if ($available) {
	    $query->join('INNER', '#__re_realties AS r ON r.country_id = c.id');
	}
	$query->order('c.name ASC');

	$db->setQuery($query);
	$result = $db->loadObjectList();

	foreach ($result as $r) {
	    $countries[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}

	if ($list) {
	    return $countries;
	} else {

	    return JHTML::_('select.genericlist', $countries, $tag, $attrib, "value", "text", $selected);
	}
    }

    function getStateList($tag, $attrib, $selected = null, $available = false, $list = false, $country = false) {
	$db = JFactory::getDbo();
	$states = array();
	$states[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_STATE'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(s.id), s.id AS value, s.state_name AS text')->from('#__re_states as s');
	$query->join('LEFT', '#__re_countries AS c ON c.id = s.country_id');

	if ($country) {
	    $query->where('s.country_id = ' . (int) $country);
	}
	if ($available) {
	    $query->join('INNER', '#__re_realties AS r ON r.locstate = s.id');
	}

	$query->order('s.state_name ASC');

	$db->setQuery($query);
	$result = $db->loadObjectList();

	foreach ($result as $r) {
	    $states[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}
	if ($list) {
	    return $states;
	} else {
	    return JHTML::_('select.genericlist', $states, $tag, $attrib, "value", "text", $selected);
	}
    }

    function getRegionList($tag, $attrib, $selected = null, $list = false) {
	$db = JFactory::getDbo();
	$regions = array();
	$regions[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_REGION'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(region) AS value, region AS text')->from('#__re_realties')->where('published = 1')->where('region != ""')->order('region');

	$db->setQuery($query);
	$result = $db->loadObjectList();

	foreach ($result as $r) {
	    $regions[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}

	if ($list) {
	    return $regions;
	} else {
	    return JHTML::_('select.genericlist', $regions, $tag, $attrib, "value", "text", $selected);
	}
    }

    public static function getCityList($tag, $attrib, $selected = null, $list = false, $state = false) {
	$db = JFactory::getDbo();
	$cities = array();
	$cities[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_CITY'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(city) AS value, city AS text')->from('#__re_realties')->where('published = 1')->where('city != ""');
	if ($state) {
	    $query->where('locstate = ' . (int) $state);
	}
	$query->order('city');

	$db->setQuery($query);
	$result = $db->loadObjectList();

	foreach ($result as $r) {
	    $cities[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}

	if ($list) {
	    return $cities;
	} else {
	    return JHTML::_('select.genericlist', $cities, $tag, $attrib, "value", "text", $selected);
	}
    }

    function getCountylist($tag, $attrib, $selected = null, $list = false) {
	$db = JFactory::getDbo();
	$counties = array();
	$counties[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_COUNTY'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(county) AS value, county AS text')->from('#__re_realties')->where('state = 1')->where('county != ""')->order('county');

	$db->setQuery($query);
	$result = $db->loadObjectList();

	foreach ($result as $r) {
	    $counties[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}

	if ($list) {
	    return $counties;
	} else {
	    return JHTML::_('select.genericlist', $counties, $tag, $attrib, "value", "text", $selected);
	}
    }

    function getProvinceList($tag, $attrib, $selected = null, $list = false) {
	$db = JFactory::getDbo();
	$provs = array();
	$provs[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_PROVINCE'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('DISTINCT(province) AS value, province AS text')->from('#__re_realties')->where('published = 1')->where('province != ""')->order('province');

	$db->setQuery($query);
	$result = $db->loadObjectList();

	foreach ($result as $r) {
	    $provs[] = JHTML::_('select.option', $r->value, JText::_($r->text), "value", "text");
	}

	if ($list) {
	    return $provs;
	} else {
	    return JHTML::_('select.genericlist', $provs, $tag, $attrib, "value", "text", $selected);
	}
    }

    public static function getType($type_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('title')->from('#__re_types')->where('id = ' . (int) $type_id);

	$db->setQuery($query, 0, 1);
	$result = $db->loadResult();

	return JText::_($result);
    }

    public static function getCategory($cat_id) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('title')->from('#__categories')->where('id = ' . (int) $cat_id);

	$db->setQuery($query, 0, 1);
	$result = $db->loadResult();

	return JText::_($result);
    }

    public static function getInfoCategory($id) {

	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('*')->from('#__categories')->where('id = ' . (int) $id)->where('published = 1');
	if (is_array($groups) && !empty($groups)) {
	    $query->where('access IN (' . implode(",", $groups) . ')');
	}

	$db->setQuery($query, 0, 1);

	return $db->loadObject();
    }

    public static function getOpenHouses($realty_id) {
	$db = JFactory::getDBO();

	// Filter by start and end dates.
	$nullDate = $db->Quote($db->getNullDate());
	$date = JFactory::getDate();
	$nowDate = $db->Quote($date->toSql());

	$query = $db->getQuery(true);
	$query->select('id, name, publish_up AS startdate, publish_down AS enddate, comments AS comments')->from('#__re_openhouses')->where('realty_id = ' . (int) $realty_id)->where('published = 1')->where('publish_down >= ' . $nowDate)->order('publish_up DESC');
	$db->setQuery($query);
	return $db->loadObjectList();
    }

    public static function getAvailableCats($id = null) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('cat_id')->from('#__re_realtyamid')->where('amen_id = 0')->where('realty_id = ' . (int) $id);

	$db->setQuery($query);
	$result = $db->loadResultArray();

	return $result;
    }

    public static function getRealtyAmenities($id) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('a.*')->from('#__re_amenities AS a')->leftJoin('#__re_realtyamid AS ra ON ra.amen_id = a.id')->where('ra.realty_id = ' . (int) $id);

	$db->setQuery($query);
	$amens = $db->loadObjectList();

	return $amens;
    }

    public static function getRealtyList($id) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('a.*')->from('#__re_agents AS a')->leftJoin('#__re_realties AS r ON r.agent_id = a.id')->where('r.agent_id = ' . (int) $id);

	$db->setQuery($query);
	$agents = $db->loadObjectList();

	return $agents;
    }

    public static function countRealty($cat = "") {
	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();
	$db = JFactory::getDbo();

	// Filter by start and end dates.
	$nullDate = $db->Quote($db->getNullDate());
	$date = JFactory::getDate();
	$nowDate = $db->Quote($date->toSql());

	// Create a new query object.

	$query = $db->getQuery(true);

	$query->select('count(r.id) AS `count_realty`')->from('#__re_realties AS r')->leftJoin('#__re_realtyamid as ra ON ra.realty_id = r.id')->leftJoin('#__re_agentrealty as ar ON ar.realty_id = r.id')->leftJoin('#__categories as c ON c.id = r.cat_id')->leftJoin('#__re_types as t ON t.id = r.type_id')->leftJoin('#__re_agents as a on a.id = ar.agent_id')->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')')->where('r.published = 1 AND c.published = 1 AND a.published = 1 AND co.published = 1')->where('r.approved = 1');
	if (is_array($groups) && !empty($groups)) {
	    $query->where('r.access IN (' . implode(",", $groups) . ')')->where('c.access IN (' . implode(",", $groups) . ')');
	}

	if ($cat) {
	    $children = JUX_Real_EstateHelperQuery::getChildren($cat);
	    if ($children) {
		$child_array = array();
		foreach ($children as $c) {
		    $child_array[] = $c->id;
		}
		$child_array = implode(',', $child_array);
		$query->where('(ra.cat_id = ' . (int) $cat . ' OR ra.cat_id IN (' . $child_array . '))');
	    } else {
		$query->where('ra.cat_id = ' . (int) $cat);
	    }
	}

	$query->where('r.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');

	$query->group('r.id');

	$db->setQuery($query);

	return $db->loadResult();
    }

    public static function getAgentPlan($plan_id) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$url = JRoute::_('index.php?option=com_content&view=addgent&plan_id=' . $plan_id);
	$plan_id = JRequest::getVar('plan_id');
	$where[] = 'id =' . $plan_id;
	$where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
	$query->select('p.*')
		->from('#__re_plans as p')
		->where('p.id = ' . (int) $plan_id);

	$db->setQuery($query);
	$plan = $db->loadObjectList();

	return $plan;
    }

    public static function getAgentInfo($agent_id) {
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);
	$query->select('a.*, a.alias as alias')
		->from('#__re_agents as a')
		->where('a.id = ' . (int) $agent_id)
		->where('a.published = 1');

	$db->setQuery($query, 0, 1);

	return $db->loadObject();
    }

}
