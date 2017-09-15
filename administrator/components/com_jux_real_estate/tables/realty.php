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
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 */
class JUX_Real_EstateTableRealty extends JTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     * @since 1.0
     */
    public function __construct(&$db) {
	parent::__construct('#__re_realties', 'id', $db);
    }

    /**
     * Overloaded bind function
     *
     * @param    array        Named array
     * @return    null|string    null is operation was satisfactory, otherwise returns an error
     * @since    1.6
     */
    public function bind($array, $ignore = '') {

	if (isset($array['params']) && is_array($array['params'])) {
	    $registry = new JRegistry();
	    $registry->loadArray($array['params']);
	    $array['params'] = (string) $registry;
	}
	$result = parent::bind($array);

	// cast properties
	$this->id = (int) $this->id;
	return $result;
    }

    /**
     * Overloaded check function
     *
     * @return boolean
     * @see JTable::check
     * @since 1.5
     */
    public function check() {
	// check for valid name
	if (trim($this->title) == '') {
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_REALTY_ERROR_TITLE_REQUIRED'));
	    return false;
	}

	// alias
	if (empty($this->alias)) {
	    $this->alias = JFilterOutput::stringURLSafe($this->title);
	}

	// Clean up keywords -- eliminate extra spaces between phrases
	// and cr (\r) and lf (\n) characters from string
	if (!empty($this->meta_keywords)) {
	    // Only process if not empty
	    $bad_characters = array("\n", "\r", "\"", "<", ">"); // array of characters to remove
	    $after_clean = JString::str_ireplace($bad_characters, "", $this->meta_keywords); // remove bad characters
	    $keys = explode(',', $after_clean); // create array using commas as delimiter
	    $clean_keys = array();

	    foreach ($keys as $key) {
		if (trim($key)) {
		    // Ignore blank keywords
		    $clean_keys[] = trim($key);
		}
	    }
	    $this->meta_keywords = implode(", ", $clean_keys); // put array back together delimited by ", "
	}

	return parent::check();
    }

    /**
     * Overload the store method for the Realties table.
     *
     * @param    boolean    Toggle whether null values should be updated.
     * @return    boolean    True on success, false on failure.
     * @since    1.6
     */
    public function store($updateNulls = false) {
	$date = JFactory::getDate();
	$user = JFactory::getUser();

	// if modified, set modified date and user, else set created date
	if ($this->id) {
	    // Existing item
	    $this->modified = $date->toSql();
	    $this->modified_by = $user->get('id');
	} else {
	    // New realty. A realty created and date_created field can be set by the user,
	    // so we don't touch either of these if they are set.
	    if (!intval($this->date_created)) {
		$this->date_created = $date->toSql();
	    }
	    if (!intval($this->publish_up)) {
		$this->publish_up = $date->toSql();
	    }
	    if (empty($this->user_id)) {
		$this->user_id = $user->get('id');
	    }
	}

	// Verify that the alias is unique
	$table = JTable::getInstance('Realty', 'JUX_Real_EstateTable');
	if ($table->load(array('alias' => $this->alias, 'type_id' => $this->type_id, 'cat_id' => $this->cat_id)) && ($table->id != $this->id || $this->id == 0)) {
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_ERROR_UNIQUE_ALIAS'));
	    return false;
	}
	// Attempt to store the user data.
	return parent::store($updateNulls);
    }

    public function feature($pks = null, $state = 0) {
	// Initialise variables.
	$k = $this->_tbl_key;

	// Sanitize input.
	JArrayHelper::toInteger($pks);
	$state = (int) $state;

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

	// Get an instance of the table
	$table = JTable::getInstance('Realty', 'JUX_Real_EstateTable');

	// For all keys
	foreach ($pks as $pk) {
	    // Load the banner
	    if (!$table->load($pk)) {
		$this->setError($table->getError());
	    }

	    // Change the state
	    $table->featured = $state;

	    // Check the row
	    $table->check();

	    // Store the row
	    if (!$table->store()) {
		$this->setError($table->getError());
	    }
	}
	return count($this->getErrors()) == 0;
    }

    public function deleteRealtyAmid($realty_id) {
	try {
	    // delete from propmid table
	    $query = $this->_db->getQuery(true);
	    $query->delete();
	    $query->from('#__re_realtyamid');
	    $query->where('realty_id = ' . (int) $realty_id);
	    $this->_db->setQuery($query);

	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }
	} catch (Exception $e) {
	    $this->setError($e->getMessage());
	    return false;
	}
	return true;
    }

    public function deleteAgentAmid($realty_id) {
	try {
	    // delete from agentrealty table
	    $query = $this->_db->getQuery(true);
	    $query->delete();
	    $query->from('#__re_agentrealty');
	    $query->where('realty_id = ' . (int) $realty_id);
	    $this->_db->setQuery($query);

	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }
	} catch (Exception $e) {
	    $this->setError($e->getMessage());
	    return false;
	}
	return true;
    }

    public function storeCatRealtyAmid($realty_id, $cats) {

	try {
	    foreach ($cats as $cat) {
		$query = 'INSERT INTO #__re_realtyamid (realty_id, cat_id) VALUES (' . (int) $realty_id . ',' . (int) $cat . ')';
		$this->_db->setQuery($query);

		if (!$this->_db->query()) {
		    throw new Exception($this->_db->getErrorMsg());
		}
	    }
	} catch (Exception $e) {
	    $this->setError($e->getMessage());
	    return false;
	}
	return true;
    }

    public function storeAmenities($realty_id, $amens) {
	try {
	    foreach ($amens as $amen) {
		$query = 'INSERT INTO #__re_realtyamid (realty_id, amen_id) VALUES (' . (int) $realty_id . ',' . (int) $amen . ')';
		$this->_db->setQuery($query);

		if (!$this->_db->query()) {
		    throw new Exception($this->_db->getErrorMsg());
		}
	    }
	} catch (Exception $e) {
	    $this->setError($e->getMessage());
	    return false;
	}
	return true;
    }

}
