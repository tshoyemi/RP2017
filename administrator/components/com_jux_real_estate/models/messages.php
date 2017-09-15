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
 * JUX_Real_Estate Component - Message Model
 * @package		JUX_Real_Estate
 * @subpackage	Model
 * @since		3.0
 */
class JUX_Real_EstateModelMessages extends JModelList {

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
		'id', 'a.id',
		'email', 'm.email',
		'name', 'm.name',
		'content', 'a.content',
		'date_created', 'a.date_created',
		'realty', 'r.title',
		'status', 'a.status',
		'checked_out', 'a.checked_out',
		'checked_out_time', 'a.checked_out_time',
		'ordering', 'r.ordering',
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

	$status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status', '');
	$this->setState('filter.status', $status);

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
	$id .= ':' . $this->getState('filter.status');

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

	// Select the required fields from the table.
	$query->select(
		$this->getState(
			'list.select', 'a.*'
		)
	);
	$query->from('#__re_messages AS a');

	// Join over the users for the checked out user.
	$query->select('uc.name AS editor');
	$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

	// Join over the asset realties.
	$query->select('r.title AS realty');
	$query->join('LEFT', '#__re_realties AS r ON r.id = a.realty_id');

	// Join over the asset agents.
	$query->select('CONCAT_WS(" ", m.username) AS agent');
	$query->join('LEFT', '#__re_agents AS m ON m.id = r.agent_id');

	// Filter by status state
	$published = $this->getState('filter.status');
	if (is_numeric($published)) {
	    $query->where('a.status = ' . (int) $published);
	} else if ($published === '') {
	    $query->where('(a.status = 0 OR a.status = 1)');
	}

	// Filter by search in title.
	$search = $this->getState('filter.search');
	if (!empty($search)) {
	    if (stripos($search, 'id:') === 0) {
		$query->where('a.id = ' . (int) substr($search, 3));
	    } else {
		$search = $db->quote('%' . $db->escape($search, true) . '%');
		$query->where('(a.subject LIKE ' . $search . ' OR a.content LIKE ' . $search . ' OR m.name LIKE ' . $search . ' OR r.title LIKE ' . $search . ' OR m.email LIKE ' . $search . ')');
	    }
	}

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
