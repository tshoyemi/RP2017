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
 * JUX_Real_Estate Component - Hot Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelMyrealty extends JModelList {

    /**
     * Model context string.
     *
     * @var        string
     */
    protected $_context = 'com_jux_real_estate.myrealty';

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
		'city', 'r.city',
		'country_id', 'r.country_id',
		'beds', 'r.beds',
		'baths', 'r.baths',
		'sqft', 'r.sqft',
		'price', 'r.price',
		'date_created', 'r.date_created',
		'modified', 'r.modified',
		'ordering', 'r.ordering', 't.ordering',
		'language', 'r.language',
		'access', 'r.access', 'access_level'
	    );
	}

	parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null) {

	// Initialise variables.
	$app = JFactory::getApplication();
	$params = $app->getParams();
	$type_id = JRequest::getInt('typeid', 0);
	$cat_id = JRequest::getInt('cat_id', 0);
	$this->setState('list.type_id', $type_id);
	$this->setState('list.cat_id', $cat_id);
	$this->setState('tabs', 'avainable');
	$params = $app->getParams();

	$limit = $params->get('display_num', $app->getCfg('list_limit'));
	$limit = $app->getUserStateFromRequest('com_jux_real_estate.myrealty.limit', 'limit', (int) $limit, 'int');

	$limitstart = JRequest::getInt('limitstart', 0, '', 'int');

	// In case limit has been changed, adjust limitstart accordingly
	$this->setState('list.limit', $limit);
	$this->setState('list.start', $limitstart);

	$orderCol = JRequest::getCmd('filter_order', 'r.ordering');
	if (!in_array($orderCol, $this->filter_fields)) {
	    $orderCol = $this->_buildContentOrderBy();
	}
	$this->setState('list.ordering', $orderCol);

	$listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');
	if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
	    $listOrder = 'ASC';
	}
	$this->setState('list.direction', $listOrder);
	$this->setState('list.limit', 3);

	$this->setState('filter.search', JRequest::getString('filter-search'));

	$this->setState('filter.language', $app->getLanguageFilter());

	// Load the parameters.
	$this->setState('params', $params);
	$this->setState('filter.title', $app->getLanguageFilter());
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param    string        $id    A prefix for the store id.
     *
     * @return    string        A store id.
     * @since    1.6
     */
    protected function getStoreId($id = '') {
	// Compile the store id.
	$id .= ':' . $this->getState('filter.search');
	$id .= ':' . $this->getState('filter.access');
	$id .= ':' . $this->getState('filter.language');
	$id .= ':' . $this->getState('list.cat_id');
	$id .= ':' . $this->getState('list.type_id');

	return parent::getStoreId($id);
    }

    /**
     * @return    JDatabaseQuery
     */
    protected function getListQuery() {

	$permissions = new JUX_Real_EstatePermission();
	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();
        
	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);
        
        // Filter by start and end dates.
        $nullDate = $db->Quote($db->getNullDate());
	$timeZone = JFactory::getConfig()->get('offset');
	$date = JFactory::getDate('now', $timeZone);
	$nowDate = $db->Quote($date->toSql(true));

	$query->select('r.*, r.id AS id, r.title AS title,r.description as description, r.date_created as date_created, r.alias as realty_alias, c.alias as cat_alias, a.alias as agent_alias')
		->from('#__re_realties AS r')
		->leftJoin('#__re_realtyamid as ra ON ra.realty_id = r.id')
		->leftJoin('#__re_agentrealty as ar ON ar.realty_id = r.id')
		->leftJoin('#__categories as c ON c.id = r.cat_id')
		->leftJoin('#__re_types as t ON t.id = r.type_id')
		->leftJoin('#__re_agents as a on a.id = r.agent_id')
		->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
		->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');

	if (is_array($groups) && !empty($groups)) {
	    $query->where('r.access IN (' . implode(",", $groups) . ')')
		    ->where('c.access IN (' . implode(",", $groups) . ')');
	}

	// Filter by type
	if ($this->getState('list.type_id')) {
	    $query->where('r.type_id = ' . (int) $this->getState('list.type_id'));
	}

	// Filter by language
	if ($this->getState('filter.language')) {
	    $query->where('r.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
	}

	$tabs = JRequest::getVar('tab', 'available');
	if ($tabs == "available" || $tabs == "") {
	    $query->where("r.published = 1 AND r.sale = 0 AND r.approved = 1 AND r.agent_id = " . (int) $permissions->getUagentId());
	}
	if ($tabs == "unavailable") {
	    $query->where("r.published = 1 AND r.sale = 1 AND r.approved = 1 AND r.agent_id = " . (int) $permissions->getUagentId());
	}
	if ($tabs == "pending") {
	    if ($permissions->getUagentId()) {
		$query->where("r.approved != 1 AND r.agent_id = " . (int) $permissions->getUagentId() );
	    } else {
		$query->where("r.approved != 1 AND r.user_id= " . (int) $user->id . "");
	    }
	}
	if ($tabs == "unpublished") {
	    $query->where("r.published != 1 AND r.agent_id = " . (int) $permissions->getUagentId());
	}

	// Filter by language
	if ($this->getState('filter.language')) {
	    $query->where('r.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
	}

	$query->group('r.id');

	// Ordering
	$query->order($this->getState('list.ordering', 'r.ordering') . ' ' . $this->getState('list.direction', 'ASC'));

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

		$items[$i]->street_address = JUX_Real_EstateHTML::getStreetAddress($configs, $items[$i]->title, $items[$i]->address);
		$items[$i]->baths = (!$configs->get('baths_fraction')) ? round($items[$i]->baths) : $items[$i]->baths;
		$items[$i]->lat_pos = ($items[$i]->address) ? round($items[$i]->latitude, $hide_round) : $items[$i]->latitude;
		$items[$i]->long_pos = ($items[$i]->address) ? round($items[$i]->longitude, $hide_round) : $items[$i]->longitude;
		$items[$i]->realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($items[$i]->id . ':' . $items[$i]->alias));
		$items[$i]->formattedprice = JUX_Real_EstateHTML::getFormattedPrice($items[$i]->price, $items[$i]->currency_id, false, $items[$i]->call_for_price, $items[$i]->price2);
		$items[$i]->formattedsqft = number_format($items[$i]->sqft);

		# Check if new or updated
		$items[$i]->new = JUX_Real_EstateHTML::isNew($items[$i]->date_created, $configs->get('new_days'));
		$items[$i]->updated = JUX_Real_EstateHTML::isNew($items[$i]->modified, $configs->get('updated_days'));

		# Get last modified date if available
		$items[$i]->last_updated = ($items[$i]->modified != '0000-00-00 00:00:00') ? JHTML::_('date', htmlspecialchars($items[$i]->modified), JText::_('DATE_FORMAT_LC2'), $tzoffset) : '';

		$last_updated = ($items[$i]->last_updated && $items[$i]->updated) ? JText::_('COM_JUX_REAL_ESTATE_LAST_MODIFIED') . ': ' . $items[$i]->last_updated : '';

		//convert value sale to text
		if (isset($items[$i]->sale)) {
		    if ($items[$i]->sale == 0) {
			$items[$i]->text_sale = 'SALE';
		    } elseif ($items[$i]->sale == 1) {
			$items[$i]->text_sale = 'SOLD';
		    }
		}
	    }
	}

	return $items;
    }

    public function getAgent() {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$user = JFactory::getUser();
	$user->get('id');
	$date_current = JHTML:: date(time(), 'Y-m-d');
        $query->select(
		$this->getState(
			'list.select', ' DISTINCT a.*, count(m.id) AS `count`, p.name AS `plan`,  p.days AS `days`, p.count_limit AS `plan_countlimit`'
			. ', (CASE WHEN p.days_type="day" '
			. ' THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days DAY),"%Y-%m-%d"), "' . $date_current . '")'
			. ' WHEN p.days_type="month" '
			. ' THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days MONTH),"%Y-%m-%d"), "' . $date_current . '")'
			. ' END) AS `sub_days`'
		)
	);
       // $query->select('DISTINCT a.*, count(m.id) AS `count`, p.name AS `plan`, p.count_limit AS `plan_countlimit`');
        $query->from('#__re_agents as a');
        $query->join('LEFT', '#__re_realties AS m ON (m.agent_id = a.id OR m.user_id = a.user_id)');
        $query->join('LEFT', '#__re_plans AS p ON p.id = a.plan_id');
        $query->where('a.user_id = ' . (int) $user->id);
        $query->where('a.published = 1');
        $db->setQuery($query);
        $results = $db->loadObject();

        return $results;
    }

    function _buildContentOrderBy() {
	// Get the page/component configuration
	$app = JFactory::getApplication();
	$params = &$app->getParams();
	$orderby_type = $params->def('orderby_type', 'alpha');
	$orderby_category = $params->def('orderby_category', 'alpha');
	$orderby_pri = $params->def('orderby_pri', 'rdate');
	$orderbyType = JUX_Real_EstateHelperQuery::orderbyType($orderby_type);
	$orderbyCategoty = JUX_Real_EstateHelperQuery::orderbyCategory($orderby_category);
	$primary = JUX_Real_EstateHelperQuery::orderbyPrimary($orderby_pri);

	$orderby = "$primary $orderbyCategoty $orderbyType";
	return $orderby;
    }

}
