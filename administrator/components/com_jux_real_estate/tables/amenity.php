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

class JUX_Real_EstateTableAmenity extends JTable {

    function __construct(&$_db) {
	parent::__construct('#__re_amenities', 'id', $_db);
    }

    function check() {
	jimport('joomla.filter.output');

	// Set name
	$this->title = htmlspecialchars_decode($this->title, ENT_QUOTES);
	return true;
    }

    public function bind($array, $ignore = '') {
	return parent::bind($array, $ignore);
    }

    public function delete($pks = NULL) {
	
	// Initialise variables.
	$k = $this->_tbl_key;

	// Sanitize input.
	JArrayHelper::toInteger($pks);

	// If there are no primary keys set check to see if the instance key is set.
	if (empty($pks)) {
	    if ($this->$k) {
		$pks = array($this->$k);
	    }
	    // Nothing to set publishing state on, return false.
	    else {
		$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
		return false;
	    }
	}

	try {
	    // delete from amenities table
	    $query = $this->_db->getQuery(true);
	    $query->delete();
	    $query->from('#__re_amenities');
	    $query->where('id IN (' . implode(',', $pks) . ')');
	    $this->_db->setQuery($query);

	    // Check for a database error.
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }

	    // delete from realty mid table
	    $query = $this->_db->getQuery(true);
	    $query->delete();
	    $query->from('#__re_realtyamid');
	    $query->where('amen_id IN (' . implode(',', $pks) . ')');
	    $this->_db->setQuery($query);

	    // Check for a database error.
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }
	} catch (Exception $e) {
	    $this->setError($e->getMessage());
	    return false;
	}
	return count($this->getErrors()) == 0;
    }

    public function saveCats($pks, $post) {
	// Initialise variables.
	$k = $this->_tbl_key;

	// Sanitize input.
	JArrayHelper::toInteger($pks);

	// If there are no primary keys set check to see if the instance key is set.
	if (empty($pks)) {
	    if ($this->$k) {
		$pks = array($this->$k);
	    }
	    // Nothing to set publishing state on, return false.
	    else {
		$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
		return false;
	    }
	}

	try {
	    foreach ($pks as $p) {
		// change categories
		$query = 'UPDATE #__re_amenities SET cat = ' . (int) $post['amen_cat_' . $p] . ' WHERE id = ' . (int) $p;
		$this->_db->setQuery($query);

		// Check for a database error.
		if (!$this->_db->query()) {
		    throw new Exception($this->_db->getErrorMsg());
		}
	    }
	} catch (Exception $e) {
	    $this->setError($e->getMessage());
	    return false;
	}
	echo $query;
	return count($this->getErrors()) == 0;
    }

}

?>