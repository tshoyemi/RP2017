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
 * JUX_Real_Estate Component - States Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelStates extends JModelList {

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
		'id', 'a.id',
		'name', 'a.state_name',
		'code', 'a.state_code',
		'country_name', 'c.name'
	    );
	}

	parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param    string    An optional ordering field.
     * @param    string    An optional direction (asc|desc).
     *
     * @return    void
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null) {

	// Initialise variables.
	$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
	$this->setState('filter.search', $search);

	$country = $this->getUserStateFromRequest($this->context . '.filter.country_id', 'filter_country_id');
	$this->setState('filter.country_id', $country);

	// List state information.
	parent::populateState('a.state_name', 'asc');
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
	$id .= ':' . $this->getState('filter.country_id');
	$id .= ':' . $this->getState('filter.públished');
	return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     * @since    1.6
     */
    protected function getListQuery() {
	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	// Select the required fields from the table.
	$query->select(
		$this->getState(
			'list.select', 'a.*, c.name AS `country_name`'
		)
	);
	$query->from('#__re_states AS a');
	$query->join('INNER', '#__re_countries AS c ON c.id = a.country_id');
	// Filter by search in title.
	$search = $this->getState('filter.search');
	if (!empty($search)) {
	    if (stripos($search, 'id:') === 0) {
		$query->where('a.id = ' . (int) substr($search, 3));
	    } else {
		$search = $db->quote('%' . $db->escape($search, true) . '%');
		$query->where('(a.state_name LIKE ' . $search . ')');
	    }
	}
	// Filter by country
	$country = $this->getState('filter.country_id');
	if (is_numeric($country)) {
	    $query->where('a.country_id = ' . (int) $country);
	} else if (is_array($country)) {
	    JArrayHelper::toInteger($country);
	    $country = implode(',', $country);
	    $query->where('a.country_id IN (' . $country . ')');
	}

	// Add the list ordering clause.
	$orderCol = $this->state->get('list.ordering', 'a.state_name');
	$orderDirn = $this->state->get('list.direction', 'asc');

	$query->order($db->escape($orderCol . ' ' . $orderDirn));

	return $query;
    }

}
