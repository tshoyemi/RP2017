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
 * JUX_Real_Estate Component - Agents Modellist
 * @package        JUX_Real_Estate
 * @subpackage    Modellist
 * @since        3.0
 */
class JUX_Real_EstateModelAgents extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
	if (empty($config['filter_fields'])) {
	    $config['filter_fields'] = array('id', 'a.id', 'name', 'a.username', 'date_created', 'a.date_created');
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
	$input = $app->input;
	$itemsarams = $app->getParams();
	$itemsost = JRequest::get('post');
	$configs = JUX_Real_EstateFactory::getConfiguration();
	$params = $app->getParams();
	$orderCol = $app->input->get('filter_order', '');

	$list_style = $input->getString('list_style', 'list');
	$this->setState('list_style', $list_style);

	// Get limit
	$this->setState('list.limit', $params->get('display_num', $params->get('list_limit', 5)));
	$this->setState('list.start', $app->input->getInt('limitstart', 0));

	// feature
	$featured_show = JRequest::getCmd('featured_show', '');
	$this->setState('list.featured_show', $featured_show);

	$title = JRequest::getString('title', '');
	$this->setState('list.title', $title);

	// search
	if (count($itemsost)) {

	    if (@$itemsost['locstate']) {
		$this->setState('agents.locstate', $itemsost['locstate']);
	    }

	    if (@$itemsost['country_id']) {
		$this->setState('agents.country_id', $itemsost['country_id']);
	    }
	}

	// fiter username
	$username = JRequest::getCmd('username', '');
	$this->setState('list.username', $username);

	// Optional filter text
	$this->setState('filter.search', JRequest::getString('filter-search'));

	$orderCol = JRequest::getCmd('filter_order', 'a.ordering');

	if (!in_array($orderCol, $this->filter_fields)) {
	    $orderCol = $this->_buildContentOrderBy($params);
	    $orderCol = 'a.ordering';
	}
	$this->setState('list.ordering', $orderCol);
	$this->setState('orderCol', $orderCol);
	$listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');
	if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
	    $listOrder = 'ASC';
	}
	$this->setState('list.direction', $listOrder);

	// Load the parameters.
	$this->setState('params', $itemsarams);

	$this->setState('filter.language', $app->getLanguageFilter());
	$this->setState('params', $params);
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
	$id.= ':' . $this->getState('filter.search');
	$id.= ':' . $this->getState('filter.published');
	$id.= ':' . $this->getState('filter.locstate');
	$id.= ':' . $this->getState('filter.country_id');

	return parent::getStoreId($id);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return    string    An SQL query
     * @since    1.6
     */
    protected function getListQuery() {
	$user = JFactory::getUser();
	$app = JFactory::getApplication();
	$params = $this->getState('params', $app->getParams('com_jux_real_estate'));

	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select($this->getState('list.select', 'a.*', 'a.username'));
	$query->from('#__re_agents AS a');
	$query->where('a.published = 1');
	$query->where('a.approved = 1');

	if ($this->getState('agents.country_id')) {
	    $query->where('a.country_id =' . (int) $this->getState('agents.country_id'));
	}

	if ($this->getState('agents.locstate')) {
	    $query->where('a.locstate = ' . (int) $this->getState('agents.locstate'));
	}

	// Filter by search in title.
	$search_module = $this->getState('list.title');

	if (!empty($search_module)) {
	    if (stripos($search_module, 'id:') === 0) {
		$query->where('a.id = ' . (int) substr($search_module, 3));
	    } else {
		$search_module = $db->Quote('%' . $db->escape($search_module, true) . '%');
		$query->where('(a.username LIKE ' . $search_module . ' OR a.alias LIKE ' . $search_module . ' )   )');
	    }
	}
	$search = $this->getState('filter.search');

	if (!empty($search)) {
	    if (stripos($search, 'id:') === 0) {
		$query->where('a.id = ' . (int) substr($search, 3));
	    } else {
		$search = $db->Quote('%' . $db->escape($search, true) . '%');
		$query->where('(a.username LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')   ');
	    }
	}

	//Option for featured status
	$featured_product = $params->get('featured_product', 'like_normal');
	switch ($featured_product) {
	    case 'only_featured':
		$query->where('a.featured = 1');
		break;

	    case 'no_featured':
		$query->where('a.featured != 1');
		break;

	    case 'featured_first':
		$query->order('a.featured DESC');
	    case 'like_normal':
	    default:
		break;
	}

	// Ordering
	$query->group('a.id');
	$query->order($db->escape($this->getState('list.ordering', 'r.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

	return $query;
    }

    function _buildContentOrderBy($params) {
	// Get the page/component configuration
	$app = JFactory::getApplication();
	$params = empty($params) ? $this->getState('params', null) : $params;

	if (empty($params)) {
	    return '';
	}

	$order_by = $params->get('order_by', '');

	switch ($order_by) {
	    case 'alpha':
		$order_by = 'a.username';
		break;

	    case 'ralpha':
		$order_by = 'a.username DESC';
		break;

	    case 'order':
	    default:
		$order_by = 'r.ordering ASC';
	}

	return $order_by;
    }

}
