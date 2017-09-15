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
class JUX_Real_EstateTableCountry extends JTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     * @since 1.0
     */
    public function __construct(&$db) {
	parent::__construct('#__re_countries', 'id', $db);
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
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_COUTRY_ERROR_NAME_REQUIRED'));
	    return false;
	}

	// alias
	if (empty($this->code)) {
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_COUNTRY_ERROR_CODE_REQUIRED'));
	    return false;
	}

	return parent::check();
    }

}
