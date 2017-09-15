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

jimport('joomla.application.component.modellist');

/**
 * JUX_Real_Estate Component - AllRealties Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelRealties extends JModelList {

    /**
     * Model context string.
     *
     * @var        string
     */
    protected $_context = 'com_jux_real_estate.realties';

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
	if (empty($config['filter_fields'])) {
	    $config['filter_fields'] = array(
		'id', 'r.id',
		'cat_id', 'r.cat_id',
		'type_id', 'r.type_id',
		'locstate', 'r.locstate',
		'r.title', 'r.title',
		'date_created', 'r.date_created',
		'country_id', 'r.country_id',
		'beds', 'r.beds',
		'baths', 'r.baths',
		'sqft', 'r.sqft',
		'price', 'r.price',
		'date_ended', 'r.date_ended',
		'modified', 'r.modified',
		'language', 'r.language',
	    );
	}
	parent::__construct($config);
    }

    protected function getStoreId($id = '') {
	// Compile the store id.
	$id .= ':' . $this->getState('filter.search');
	$id .= ':' . $this->getState('filter.access');
	$id .= ':' . $this->getState('filter.language');
	$id .= ':' . $this->getState('list.cat_id');
	$id .= ':' . $this->getState('list.type_id');
	$id .= ':' . $this->getState('list.country_id');

	return parent::getStoreId($id);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null) {
	$app = JFactory::getApplication('site');
	$input = $app->input;
	$params = $app->getParams();
	$configs = JUX_Real_EstateFactory::getConfiguration();
	$orderCol = $app->input->get('filter_order', '');

	$list_style = $input->getString('list_style', 'list');
	$this->setState('list_style', $list_style);
	// filter
	$this->setState('filter.search', JRequest::getString('filter-search'));
	$this->setState('list.type_id', JRequest::getString('type_id'));

	$featured_show = JRequest::getCmd('featured_show', '');
	$this->setState('list.featured_show', $featured_show);


	$agent_id = JRequest::getCmd('agent_id', '');
	$this->setState('list.agent_id', $agent_id);

	$title = JRequest::getString('title', '');
	$this->setState('list.title', $title);

	$price_slider_lower = JRequest::getCmd('price_slider_lower');
	$this->setState('list.price_slider_lower', $price_slider_lower);

	$price_slider_upper = JRequest::getCmd('price_slider_upper');
	$this->setState('list.price_slider_upper', $price_slider_upper);

	$area_slider_lower = JRequest::getCmd('area_slider_lower');
	$this->setState('list.area_slider_lower', $area_slider_lower);

	$area_slider_upper = JRequest::getCmd('area_slider_upper');
	$this->setState('list.area_slider_upper', $area_slider_upper);

	$cat_id = $input->getString('cat_id', '');
	$this->setState('list.cat_id', $cat_id);

	$curstate = JRequest::getCmd('locstate', '');
	$this->setState('list.locstate', $curstate);

	$country_id = JRequest::getCmd('country_id', '');
	$this->setState('list.country_id', $country_id);

	$beds = JRequest::getCmd('beds', 0);
	$this->setState('list.beds', $beds);
	$baths = JRequest::getCmd('baths', 0);
	$this->setState('list.baths', $baths);

	//advance search
	$field_search = array();
	$data_field_search = array();
	foreach ($_REQUEST as $key => $val) {
	    if (strpos($key, 'jp_') !== false) {
		$field_search[$key] = $val;
	    }
	}
	if (isset($field_search) && count($field_search) > 0) {
	    foreach ($field_search as $key => $val) {
		if ($val == null) {
		    unset($field_search[$key]);
		}
	    }

	    if (!empty($field_search)) {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__re_realties');
		foreach ($field_search as $key => $val) {
		    $query->where("extra_field LIKE '%" . $key . ":" . $val . "%'");
		}
		$db->setQuery($query);
		$data_field_search = $db->loadAssocList();
		$ar_realties_id = array();
		foreach ($data_field_search as $key => $val) {
		    $ar_realties_id[] = $val['id'];
		}
		if (count($ar_realties_id) > 0) {
		    $ar_realties_id = '(' . implode(',', $ar_realties_id) . ')';
		    $this->setState('list.ar_realties_id', $ar_realties_id);
		} else {
		    $this->setState('list.ar_realties_id', '(-1)');
		}
	    }
	}
	//end advance search

	$orderCol = JRequest::getCmd('filter_order', 'r.ordering');

	if (!in_array($orderCol, $this->filter_fields)) {
	    $orderCol = $this->_buildContentOrderBy($params);
	    $orderCol = 'r.ordering';
	}
	$this->setState('list.ordering', $orderCol);
	$listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');
	if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
	    $listOrder = 'ASC';
	}
	$this->setState('list.direction', $listOrder);
	// Get list limit
	if ($params->get('display_num') == 0) {
	    $this->setState('list.limit', $configs->get('item_per_page'));
	} else {
	    $this->setState('list.limit', $params->get('display_num'));
	}
	$this->setState('list.start', $app->input->getInt('limitstart', 0));
	// Load the parameters.
	$this->setState('filter.language', $app->getLanguageFilter());
	$this->setState('params', $params);
    }

    protected function getListQuery() {
	$app = JFactory::getApplication();
	$params = $this->getState('params', $app->getParams('com_jux_real_estate'));
	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();
	$limitStart = $this->getState('limitstart');
	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);
	$nullDate = $db->Quote($db->getNullDate());
	$timeZone = JFactory::getConfig()->get('offset');
	$date = JFactory::getDate('now', $timeZone);
	$nowDate = $db->Quote($date->toSql(true));

	$query->select('r.*, r.id AS id, r.title AS title, r.description as description, r.date_created as date_created, r.alias as realty_alias, c.alias as cat_alias, a.alias as agent_alias')
		->from('#__re_realties AS r')
		->leftJoin('#__categories as c ON c.id = r.cat_id')
		->leftJoin('#__re_types as t ON t.id = r.type_id')
		->leftJoin('#__re_agents as a on a.id = r.agent_id')
		->leftJoin('#__re_countries as ct on ct.id = r.country_id')
		->where('r.published = 1 AND c.published = 1 AND a.published = 1 ')
		->where('r.approved = 1')
		->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
		->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
	if ($this->getState('list.featured_show')) {
	    $query->where('r.featured = 1');
	}

	if ($this->getState('list.locstate'))
	    $query->where('r.locstate = ' . (int) $this->getState('list.locstate'));
	if ($this->getState('list.title')) {
	    $query->where("r.title LIKE '%" . $this->getState('list.title') . "%'");
	}
	if ($this->getState('list.country_id'))
	    $query->where('r.country_id = ' . (int) $this->getState('list.country_id'));

	if ($this->getState('list.beds'))
	    $query->where('r.beds >= ' . (int) $this->getState('list.beds'));
	if ($this->getState('list.baths'))
	    $query->where('r.baths >= ' . (int) $this->getState('list.baths'));

	$price_slider_lower = $this->getState('list.price_slider_lower');
	$price_slider_upper = $this->getState('list.price_slider_upper');
	if ((isset($price_slider_lower) && $price_slider_lower != '') && (isset($price_slider_upper) && $price_slider_upper != '')) {
	    $query->where('r.price BETWEEN ' . $price_slider_lower . ' AND ' . $price_slider_upper);
	} elseif (isset($price_slider_lower) && $price_slider_lower != '') {
	    $query->where('r.price >= ' . $price_slider_lower);
	} elseif (isset($price_slider_upper) && $price_slider_upper != 0) {
	    $query->where('r.price <= ' . $price_slider_upper);
	}

	$area_slider_lower = $this->getState('list.area_slider_lower');
	$area_slider_upper = $this->getState('list.area_slider_upper');
	if ((isset($area_slider_lower) && $area_slider_lower != '') && (isset($area_slider_upper) && $area_slider_upper != '')) {
	    $query->where('r.sqft BETWEEN ' . $area_slider_lower . ' AND ' . $area_slider_upper);
	} elseif (isset($area_slider_lower) && $area_slider_lower != '') {
	    $query->where('r.sqft >= ' . $area_slider_lower);
	} elseif (isset($area_slider_upper) && $area_slider_upper != '') {
	    $query->where('r.sqft <= ' . $area_slider_upper);
	}

	if ($this->getState('list.type_id'))
	    $query->where('r.type_id = ' . (int) $this->getState('list.type_id'));
	if ($this->getState('list.typeid'))
	    $query->where('r.type_id = ' . (int) $this->getState('list.typeid'));
	if ($this->getState('list.cat_id') and $this->getState('list.cat_id') != "all")
	    $query->where('r.cat_id = ' . (int) $this->getState('list.cat_id'));
	if ($this->getState('list.catid') and $this->getState('list.catid') != "all")
	    $query->where('r.cat_id = ' . (int) $this->getState('list.catid'));
	//filter search

	$search = $this->getState('filter.search');
	if (!empty($search)) {
	    $where = array();
	    if (stripos($search, 'id:') === 0) {
		$query->where('r.id = ' . (int) substr($search, 3));
	    } else {
		$search = $db->Quote('%' . $db->escape($search, true) . '%');
		$where[] = '(r.title LIKE ' . $search . ' OR r.alias LIKE ' . $search . ')';
		$where[] = '(LOWER( r.description ) LIKE ' . $search . ')';
		$query->where('(' . implode(' OR ', $where) . ')');
	    }
	}
	// Filter by agent
	if ($this->getState('list.agent_id'))
	    $query->where('r.agent_id = ' . (int) $this->getState('list.agent_id'));

	//filter by extra_field
	if ($this->getState('list.ar_realties_id')) {
	    $query->where('r.id IN ' . $this->getState('list.ar_realties_id'));
	}

	//Option for featured status
	$featured_product = $params->get('featured_product', 'like_normal');
	switch ($featured_product) {
	    case 'only_featured':
		$query->where('r.featured = 1');
		break;
	    case 'no_featured':
		$query->where('r.featured != 1');
		break;
	    case 'featured_first':
		$query->order('r.featured DESC');
	    case 'like_normal':
	    default:
		break;
	}


	// Filter by language
	if ($this->getState('filter.language')) {
	    $query->where('r.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
	}

	// Filter by date
	$fromDate = $this->getState('list.fromdate');
	$toDate = $this->getState('list.todate');

	$query->group('r.id');
	// Ordering
	$query->order($db->escape($this->getState('list.ordering', 'r.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
	
	return $query;
    }

    public function getItems() {
	$items = parent::getItems();
	$configs = JUX_Real_EstateFactory::getConfiguration();
	$config = JFactory::getConfig();
	$tzoffset = $config->get('config.offset');
	$hide_round = 3;
	if (count($items)) {
	    for ($i = 0; $i < count($items); $i++) {

		$items[$i]->baths = (!$configs->get('baths_fraction')) ? round($items[$i]->baths) : $items[$i]->baths;

		// get realty link
		$items[$i]->realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($items[$i]->id . '-' . $items[$i]->alias));
		# Get the thumbnail
		# Format Price and SQft output
		$items[$i]->formattedprice = JUX_Real_EstateHTML::getFormattedPrice($items[$i]->price, $items[$i]->price_freq, $items[$i]->currency_id, false, $items[$i]->call_for_price, $items[$i]->price2);
		$items[$i]->formattedsqft = number_format($items[$i]->sqft);

		# Check if new or updated
		$items[$i]->new = JUX_Real_EstateHTML::isNew($items[$i]->date_created, $configs->get('new_days'));
		$items[$i]->updated = JUX_Real_EstateHTML::isNew($items[$i]->modified, $configs->get('updated_days'));

		# Get last modified date if available
		$items[$i]->last_updated = ($items[$i]->modified != '0000-00-00 00:00:00') ? JHTML::_('date', htmlspecialchars($items[$i]->modified), JText::_('DATE_FORMAT_LC2'), $tzoffset) : '';
	    }

	    return $items;
	}
    }

    function _buildContentOrderBy($params) {
	// Get the page/component configuration
	$app = JFactory::getApplication();
	$params = empty($params) ? $this->getState('params', null) : $params;
	if (empty($params)) {
	    return '';
	}
	$order_by = $params->get('order_by', '');

	$order_date = $params->get('order_date', '');
	switch ($order_by) {
	    case 'date':
		$order_by = $this->_getQueryDate($order_date);
		break;
	    case 'rdate':
		$order_by = $this->_getQueryDate($order_date) . ' DESC';
		break;
	    case 'alpha':
		$order_by = 'r.title';
		break;
	    case 'ralpha':
		$order_by = 'r.title DESC';
		break;
	    case 'hits':
		$order_by = 'r.hits DESC';
		break;
	    case 'rhits':
		$order_by = 'r.hits';
		break;
	    case 'order':
	    default:
		$order_by = 'r.ordering ASC';
	}


	return $order_by;
    }

    protected function _getQueryDate($orderDate) {
	$db = $this->getDbo();

	switch ($orderDate) {
	    case 'modified' :
		$queryDate = ' CASE WHEN r.date_ended = ' . $db->quote($db->getNullDate()) . ' THEN r.created ELSE r.modified END';
		break;

	    case 'created' :
	    default :
		$queryDate = ' r.date_created ';
		break;
	}

	return $queryDate;
    }
   

}

