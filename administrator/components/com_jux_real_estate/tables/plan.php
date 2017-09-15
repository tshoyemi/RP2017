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
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 */
class JUX_Real_EstateTablePlan extends JTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     * @since 1.0
     */
    public function __construct(&$db) {
	parent::__construct('#__re_plans', 'id', $db);
    }

    /**
     * Overloaded bind function
     *
     * @param	array		Named array
     * @return	null|string	null is operation was satisfactory, otherwise returns an error
     * @since	1.6
     */
    public function bind($array, $ignore = '') {
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
	if (trim($this->name) == '') {
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_REALTY_ERROR_NAME_REQUIRED'));
	    return false;
	}

	return parent::check();
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
	$table = JTable::getInstance('Plan', 'JUX_Real_EstateTable');

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

}
