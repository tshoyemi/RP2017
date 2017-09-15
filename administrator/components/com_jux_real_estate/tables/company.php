<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 */
class JUX_Real_EstateTableCompany extends JTable {

	function __construct(&$_db)
	{
		parent::__construct('#__re_companies', 'id', $_db);
	}

    function check()
	{
        // check for valid name
        if (trim($this->name) == '') {
            $this->setError(JText::_('COM_JUX_REAL_ESTATE_COMPANY_ERROR_NAME_REQUIRED'));
            return false;
        }

		// Set name
		$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);
        // Set alias
		if (empty($this->alias)) {
			$this->alias    = JApplication::stringURLSafe($this->name);
		}

        return parent::check();
	}
    
    public function bind($array, $ignore = array())
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}

        $this->id = (int) $this->id;

		return parent::bind($array, $ignore);
	}

	function store($updateNulls = false)
	{
		if (empty($this->id)){
			parent::store($updateNulls);
		}
		else
		{
			// Get the old row
            $table = JTable::getInstance('Company', 'JUX_Real_EstateTable');
			if (!$table->load($this->id) && $table->getError()){
				$this->setError($table->getError());
			}
            // Verify that the alias is unique
            if ($table->load(array('alias'=>$this->alias)) && ($table->id != $this->id || $this->id==0)) {
                $this->setError(JText::_('COM_JUX_REAL_ESTATE_ERROR_UNIQUE_ALIAS'));
                return false;
            }
			// Store the new row
			parent::store($updateNulls);

			// Need to reorder ?
			if ($table->published >= 0){
				// Reorder the oldrow
				$this->reorder('published >= 0');
			}

		}

		return count($this->getErrors())==0;
	}

    
    public function feature($pks = null, $state = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$state      = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
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
        $table = JTable::getInstance('Company', 'JUX_Real_EstateTable');

		// For all keys
		foreach ($pks as $pk)
		{
            // Load the banner
            if(!$table->load($pk)){
                $this->setError($table->getError());
            }

            // Change the state
            $table->featured = $state;

            // Check the row
            $table->check();

            // Store the row
            if (!$table->store()){
                $this->setError($table->getError());
            }
		}
		return count($this->getErrors())==0;
	}
    
    public function delete($pks = null)
	{
        // Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		try
		{			
			// delete from companies table
            $query = $this->_db->getQuery(true);
            $query->delete();
            $query->from('#__re_companies');
            $query->where('id IN ('.implode(',', $pks).')');
            $this->_db->setQuery($query);
            
			// Check for a database error.
            if (!$this->_db->query()) {
				throw new Exception($this->_db->getErrorMsg());
			}
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
		return count($this->getErrors())==0;
	}
}
?>