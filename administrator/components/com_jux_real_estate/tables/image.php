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
defined('_JEXEC') or die;

/**
 * Product Image table
 * @package		Joomla.Administrator
 * @subpackage	com_jse_digital_store
 */
class JUX_Real_EstateTableImage extends JTable {

    /**
     * @param $db A database connector object
     */
    public function __construct(&$db) {
	parent::__construct('#__re_realtie_images', 'id', $db);
    }

    /**
     * Binds an array to the object.
     * @access	public
     */
    public function bind($array, $ignore = '') {
	if (isset($array['params']) && is_array($array['params'])) {
	    $registry = new JRegistry();
	    $registry->loadArray($array['params']);
	    $array['params'] = (string) $registry;
	}

	return parent::bind($array, $ignore);
    }

    /**
     * Overloaded check function.
     * @access	public
     */
    public function check() {
	return parent::check();
    }

}

?>	