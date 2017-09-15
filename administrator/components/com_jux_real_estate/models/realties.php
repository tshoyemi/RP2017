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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

/**
 * JUX_Real_Estate Component - Realties Model
 * @package		JUX_Real_Estate
 * @subpackage	Model
 * @since		1.6
 */
class JUX_Real_EstateModelRealties extends JModelList {

    /**
     * Constructor.
     *
     * @param	array	An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array()) {
	if (empty($config['filter_fields'])) {
	    $config['filter_fields'] = array(
		'id', 'r.id',
		'cat_id', 'r.cat_id', 'category_title',
		'r.title', 'r.title',
		'r.alias', 'r.alias',
		't.title', 't.title',
		'c.title', 'c.title',
		'username', 'username',
		'agentname', 'agentname',
		'date_created', 'r.date_created',
		'price', 'r.price',
		'd.title', 'd.title',
		'sale', 'r.sale',
		'type_id', 'r.type_id',
		'agent_id', 'r.agent_id',
		'cat_id', 'r.cat_id',
		'locstate', 'r.locstate',
		'city', 'r.city',
		'country_id', 'r.country_id',
		'beds', 'r.beds',
		'baths', 'r.baths',
		'sqft', 'r.sqft',
		'price', 'r.price',
		'date_created', 'r.date_created',
		'modified', 'r.modified',
		'featured', 'r.featured',
		'approved', 'r.approved',
		'count', 'r.count',
		'published', 'r.published',
		'checked_out', 'r.checked_out',
		'checked_out_time', 'r.checked_out_time',
		'ordering', 'r.ordering',
		'access', 'r.access',
		'language', 'r.language'
	    );
	}

	parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param	string	An optional ordering field.
     * @param	string	An optional direction (asc|desc).
     *
     * @return	void
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {


	$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
	$this->setState('filter.search', $search);

	$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
	$this->setState('filter.published', $published);

	$featured = $this->getUserStateFromRequest($this->context . '.filter.featured', 'filter_featured', '');
	$this->setState('filter.featured', $featured);

	$approved = $this->getUserStateFromRequest($this->context . '.filter.approved', 'filter_approved', '');
	$this->setState('filter.approved', $approved);


	$typeId = $this->getUserStateFromRequest($this->context . '.filter.type_id', 'filter_type_id');
	$this->setState('filter.type_id', $typeId);

	$cat_id = $this->getUserStateFromRequest($this->context . '.filter.cat_id', 'filter_cat_id');
	$this->setState('filter.cat_id', $cat_id);


	$agentid = $this->getUserStateFromRequest($this->context . '.filter.agent_id', 'filter_agent_id');
	$this->setState('filter.agent_id', $agentid);
	$city = $this->getUserStateFromRequest($this->context . '.filter.city', 'filter_city');
	$this->setState('filter.city', $city);

	$locstate = $this->getUserStateFromRequest($this->context . '.filter.locstate', 'filter_locstate');
	$this->setState('filter.locstate', $locstate);

	$beds = $this->getUserStateFromRequest($this->context . '.filter.beds', 'filter_beds');
	$this->setState('filter.beds', $beds);

	$baths = $this->getUserStateFromRequest($this->context . '.filter.baths', 'filter_baths');
	$this->setState('filter.baths', $baths);

	$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
	$this->setState('filter.access', $access);

	$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
	$this->setState('filter.language', $language);

	// List state information.
	parent::populateState('r.ordering', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     *
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
	// Compile the store id.
	$id .= ':' . $this->getState('filter.search');
	$id .= ':' . $this->getState('filter.published');
	$id .= ':' . $this->getState('filter.approved');
	$id .= ':' . $this->getState('filter.approved');
	$id .= ':' . $this->getState('filter.cat_id');
	
	$id .= ':' . $this->getState('filter.locstate');
	$id .= ':' . $this->getState('filter.city');
	$id .= ':' . $this->getState('filter.type_id');
	$id .= ':' . $this->getState('filter.city');
	$id .= ':' . $this->getState('filter.country_id');
	$id .= ':' . $this->getState('filter.agent_id');
	$id .= ':' . $this->getState('filter.beds');
	$id .= ':' . $this->getState('filter.baths');
	$id .= ':' . $this->getState('filter.access');
	$id .= ':' . $this->getState('filter.language');

	return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);
	$user = JFactory::getUser();
	// Select the required fields from the table.
	$query->select(
		$this->getState(
			'list.select', 'r.*, r.cat_id , d.sign AS `currency`, t.title AS `type`, c.title AS `category`, CONCAT_WS(" ", a.username) AS `agentname`, u.name AS `username`'
		)
	);
	$query->from('#__re_realties AS r');

	// Join over the type
	$query->join('LEFT', '#__re_types AS t ON t.id = r.type_id');

	// Join over the categories.
	$query->select('c.title AS category_title')
		->join('LEFT', '#__categories AS c ON c.id = r.cat_id');
	$query->join('LEFT', '#__re_currencies AS d ON d.id = r.currency_id');
	//Join over agent
	$query->join('LEFT', '#__re_agents AS a ON a.id = r.agent_id');
	//Join over users
	$query->join('LEFT', '#__users AS u ON u.id = r.user_id');

	// Join over the users for the checked out user.
	$query->select('uc.name AS editor');
	$query->join('LEFT', '#__users AS uc ON uc.id=r.checked_out');

	// Join over the language
	$query->select('l.title AS language_title');
	$query->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = r.language');

	// Join over the asset groups.
	$query->select('ag.title AS access_level');
	$query->join('LEFT', '#__viewlevels AS ag ON ag.id = r.access');

	// Filter by published state
	$published = $this->getState('filter.published');
	if (is_numeric($published)) {
	    $query->where('r.published = ' . (int) $published);
	} else if ($published === '') {
	    $query->where('(r.published = 0 OR r.published = 1)');
	}

	// Filter by published state
	$published = $this->getState('filter.approved');
	if (is_numeric($published)) {
	    $query->where('r.approved = ' . (int) $published);
	} else if ($published === '') {
	    $query->where('(r.approved = -1 OR r.approved = 1 OR r.approved = 0)');
	}

	// Filter by published state
	$published = $this->getState('filter.featured');
	if (is_numeric($published)) {
	    $query->where('r.featured = ' . (int) $published);
	} else if ($published === '') {
	    $query->where('(r.featured = 0 OR r.featured = 1)');
	}

	// Filter by search in title.
	$search = $this->getState('filter.search');
	if (!empty($search)) {
	    if (stripos($search, 'id:') === 0) {
		$query->where('r.id = ' . (int) substr($search, 3));
	    } else {
		$search = $db->Quote('%' . $db->escape($search, true) . '%');
		$query->where('(r.title LIKE ' . $search . ')');
	    }
	}

	// Filter by a single or group of types.
	$typeId = $this->getState('filter.type_id');
	if (is_numeric($typeId)) {
	    $query->where('r.type_id = ' . (int) $typeId);
	} else if (is_array($typeId)) {
	    JArrayHelper::toInteger($typeId);
	    $typeId = implode(',', $typeId);
	    $query->where('r.type_id IN (' . $typeId . ')');
	}

	// Filter by category.
	$cat_Id = $this->getState('filter.cat_id');
	if ($cat_Id && is_numeric($cat_Id)) {
	    $query->where('r.cat_id = ' . (int) $cat_Id);
	}

	// Filter by city.
	$cityFilter = $this->getState('filter.city');
	if ($cityFilter) {
	    $query->where('r.city = ' . $db->Quote($cityFilter));
	}
	// Filter by country.
	$countryFilter = $this->getState('filter.country_id');
	if ($countryFilter) {
	    $query->where('r.country_id = ' . $db->Quote($countryFilter));
	}

	// Filter by category.
	$category_id = $this->getState('filter.category_id');
	if ($category_id && is_numeric($category_id)) {
	    $query->where('r.cat_id = ' . (int) $category_id);
	}

	// Filter by agent.
	$agentId = $this->getState('filter.agent_id');
	if ($agentId && is_numeric($agentId)) {
	    $query->where('r.agent_id = ' . (int) $agentId);
	}

	// Filter by beds.
	$bedsId = $this->getState('filter.beds');
	if ($bedsId && is_numeric($bedsId)) {
	    $query->where('r.beds >= ' . (int) $bedsId);
	}

	// Filter by baths.
	$bathsId = $this->getState('filter.baths');
	if ($bathsId && is_numeric($bathsId)) {
	    $query->where('r.baths >= ' . (int) $bathsId);
	}

	// Filter on the language.
	if ($language = $this->getState('filter.language')) {
	    $query->where('r.language = ' . $db->quote($language));
	}

	// Filter by access level.
	if ($access = $this->getState('filter.access')) {
	    $query->where('r.access = ' . (int) $access);
	}

	// Implement View Level Access
	if (!$user->authorise('core.admin')) {
	    $groups = implode(',', $user->getAuthorisedViewLevels());
	    $query->where('r.access IN (' . $groups . ')');
	}

	$query->group('r.id');

	// Add the list ordering clause.
	$orderCol = $this->state->get('list.ordering');

	$orderDirn = $this->state->get('list.direction');
	if ($orderCol == 'r.ordering') {
	    $orderCol = 'r.ordering';
	}
	$query->order($db->escape($orderCol . ' ' . $orderDirn));
	
	return $query;
    }

}
