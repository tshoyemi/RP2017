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
 * @subpackage	com_jp_donatoin
 */
class JUX_Real_EstateTableConfigs extends JTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     * @since 1.0
     */
    public function __construct(&$db) {
	parent::__construct('#__re_configs', 'key', $db);
    }

    /**
     * Method override to store a config row.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     *
     * @link	http://docs.joomla.org/JTable/store
     * @since   11.1
     */
    public function store($updateNulls = false) {
	// Initialise variables.
	$k = $this->_tbl_key;

	// If a this config item exists update the object, otherwise insert it.
	if ($this->checkExist($this->$k)) {
	    $stored = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
	} else {
	    $stored = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
	}

	// If the store failed return false.
	if (!$stored) {
	    $e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_STORE_FAILED', get_class($this), $this->_db->getErrorMsg()));
	    $this->setError($e);
	    return false;
	}

	if ($this->_locked) {
	    $this->_unlock();
	}

	return true;
    }

    /**
     * Check whether a config item is exist or not.
     *
     * @param   boolean  $key  the item key to be checked.
     *
     * @return  boolean  True on exist.
     */
    protected function checkExist($key = null) {
	if (empty($key)) {
	    return false;
	}

	// Initialise the query.
	$query = $this->_db->getQuery(true);
	$query->select('COUNT(`key`)');
	$query->from($this->_tbl);
	$query->where('`key` = ' . $this->_db->quote($key));

	$this->_db->setQuery($query);

	try {
	    $result = $this->_db->loadResult();
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return false;
	}

	if ($result > 0) {
	    return true;
	}

	return false;
    }

}
