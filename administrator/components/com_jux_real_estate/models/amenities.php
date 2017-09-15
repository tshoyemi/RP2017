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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class JUX_Real_EstateModelAmenities extends JModelList {

    public function __construct($config = array()) {
	if (empty($config['filter_fields'])) {
	    $config['filter_fields'] = array(
		'id', 'a.id',
		'title', 'a.title',
		'cat', 'a.cat'
	    );
	}

	parent::__construct($config);
    }

    protected function getStoreId($id = '') {
	// Compile the store id.
	$id .= ':' . $this->getState('filter.search');
	$id .= ':' . $this->getState('filter.cat_id');
	return parent::getStoreId($id);
    }

    public function getTable($type = 'Amenity', $prefix = 'JUX_Real_EstateTable', $config = array()) {
	return JTable::getInstance($type, $prefix, $config);
    }

    protected function populateState($ordering = null, $direction = null) {

	// Load the filter state.
	$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
	$this->setState('filter.search', $search);

	$cat = $this->getUserStateFromRequest($this->context . '.filter.cat_id', 'filter_cat_id', '', '');
	$this->setState('filter.cat_id', $cat);

	// List state information.
	parent::populateState('title', 'asc');
    }

    protected function getListQuery() {
	// Initialise variables.
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	// Select the required fields from the table.
	$query->select(
		$this->getState(
			'list.select', 'a.id AS id,' .
			'a.title AS title,' .
			'a.cat AS cat'
		)
	);
	$query->from('`#__re_amenities` AS a');

	// Filter by category
	$catId = $this->getState('filter.cat_id');
	if (is_numeric($catId)) {
	    $query->where('a.cat = ' . (int) $catId);
	} else if ($catId === '') {
	    $query->where('(a.cat IN (0, 1, 2))');
	}

	// Filter by search in title
	$search = $this->getState('filter.search');
	if (!empty($search)) {
	    if (stripos($search, 'id:') === 0) {
		$query->where('a.id = ' . (int) substr($search, 3));
	    } else {
		$search = JString::strtolower($search);
		$search = explode(' ', $search);
		$searchwhere = array();
		if (is_array($search)) { //more than one search word
		    foreach ($search as $word) {
			$searchwhere[] = 'LOWER(a.title) LIKE ' . $db->quote('%' . $db->escape($word, true) . '%', false);
		    }
		} else {
		    $searchwhere[] = 'LOWER(a.title) LIKE ' . $db->quote('%' . $db->escape($search, true) . '%', false);
		}
		$query->where('(' . implode(' OR ', $searchwhere) . ')');
	    }
	}

	// Add the list ordering clause.
	$orderCol = $this->getState('list.ordering');
	$orderDirn = $this->getState('list.direction');
	$query->order($db->escape($orderCol . ' ' . $orderDirn));


	// Set limit to 20 to keep 2 column layout
	$this->setState('list.limit', 20);
	
	return $query;
    }

}

?>