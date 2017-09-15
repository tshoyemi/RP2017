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
 * JUX_Real_Estate Component - Realty table
 * @package		JUX_Real_Estate
 * @subpackage	Table
 * @since		3.0
 *
 */
class JUX_Real_EstateTableCurrency extends JTable {

    /**
     * @param $db A database connector object
     */
    function __construct(&$db) {
	parent::__construct('#__re_currencies', 'id', $db);
    }

    /**
     * Binds an array to the object.
     * @access	public
     */
    function bind($array, $ignore = '') {
	$result = parent::bind($array);
	// cast properties
	$this->id = (int) $this->id;
	$this->position = (int) $this->position;

	return $result;
    }

    /**
     * Overloaded check function.
     * @access	public
     */
    function check() {
	// check for valid name
	if (trim($this->title) == '') {
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_CURRENCY_MUST_CONTAIN_THE_TITLE'));
	    return false;
	}

	// check for valid currency code
	if (trim($this->code) == '') {
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_CURRENCY_MUST_CONTAIN_THE_CODE'));
	    return false;
	}

	return parent::check();
    }

}

?>