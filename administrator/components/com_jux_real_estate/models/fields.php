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
 * JUX_Real_Estate Component - Fields Model
 * @package		JUX_Real_Estate
 * @subpackage	Model
 * @since		1.0
 */
class JUX_Real_EstateModelFields extends JModelList {

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
		'name', 'a.name',
		
		'field_type', 'a.field_type',
		'required', 'a.required',
		'field_type', 'a.field_type',
		'access', 'a.access',
		'search_field', 'a.search_field',
		'description', 'a.description',
		'published', 'a.published',
		'checked_out', 'a.checked_out',
		'checked_out_time', 'a.checked_out_time',
		'ordering', 'a.ordering',
		'language', 'a.language'
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
	// Initialise variables.
	$app = JFactory::getApplication();
	$session = JFactory::getSession();

	// Adjust the context to support modal layouts.
	if ($layout = JRequest::getVar('layout')) {
	    $this->context .= '.' . $layout;
	}

	// Load the filter state.
	$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
	$this->setState('filter.search', $search);

	$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
	$this->setState('filter.published', $published);

	$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
	$this->setState('filter.access', $access);

	$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
	$this->setState('filter.language', $language);

	// Load the parameters.
	$params = JComponentHelper::getParams('com_jux_real_estate');
	$this->setState('params', $params);


	// List state information.
	parent::populateState('a.ordering', 'asc');
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

	// Select the required fields from the table.
	$query->select(
		$this->getState(
			'list.select', 'a.*'
		)
	);
	$query->from('#__re_fields AS a');

	// Join over the users for the checked out user.
	$query->select('uc.name AS editor');
	$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

	// Join over the language
	$query->select('l.title AS language_title');
	$query->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

	// Filter by published state
	$published = $this->getState('filter.published');
	if (is_numeric($published)) {
	    $query->where('a.published = ' . (int) $published);
	} else if ($published === '') {
	    $query->where('(a.published = 0 OR a.published = 1)');
	}

	// Filter by search in title.
	$search = $this->getState('filter.search');
	if (!empty($search)) {
	    if (stripos($search, 'id:') === 0) {
		$query->where('a.id = ' . (int) substr($search, 3));
	    } else {
		$search = $db->quote('%' . $db->escape($search, true) . '%');
		$query->where('(a.title LIKE ' . $search . ')');
	    }
	}


	// Filter on the language.
	if ($language = $this->getState('filter.language')) {
	    $query->where('a.language = ' . $db->quote($language));
	}

	// Add the list ordering clause.
	$orderCol = $this->state->get('list.ordering');

	$orderDirn = $this->state->get('list.direction');
	if ($orderCol == 'a.ordering') {
	    $orderCol = 'a.ordering';
	}

	$query->order($db->escape($orderCol . ' ' . $orderDirn));

	return $query;
    }

}