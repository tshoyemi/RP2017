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
 * JUX_Real_Estate Component - Utility Library.
 * @package		JUX_Real_Estate
 * @subpackage	Library
 */
class JUX_Real_EstateUtils {

    /**
     * Format price.
     */
    public static function formatPrice($price, $display = null, $thousand_separator = ',', $number_only = false) {
	if ($price == 0 && $number_only == false) {
	    return JText::_('COM_JUX_REAL_ESTATE_CONTACT_FOR_REQUEST');
	}
	$db = JFactory::getDBO();
	$result = number_format($price, 0, '.', $thousand_separator);

	$query = 'SELECT a.*'
		. ' FROM #__re_currencies AS a'
		. ' WHERE a.id =' . $display
	;
	$db->setQuery($query);
	$row = $db->loadObject();
	if (is_object($row)) {
	    if ($row->position == 0) {
		$result = $row->sign . $result;
	    } else {
		$result = $result . ' ' . $row->code;
	    }
	}
	return $result;
    }

    public static function getCurrency($id = null) {

	$db = JFactory::getDBO();
	$query = 'SELECT a.*'
		. ' FROM #__re_currencies AS a'
		. ' WHERE a.id =' . $id
	;
	$db->setQuery($query);
	$row = $db->loadObject();
	return $row;
    }

}