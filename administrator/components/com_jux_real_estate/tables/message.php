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
class JUX_Real_EstateTableMessage extends JTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     * @since 1.0
     */
    public function __construct(&$db) {
	parent::__construct('#__re_messages', 'id', $db);
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

}
