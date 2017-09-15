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

jimport('joomla.application.component.controllerform');

/**
 * JUX_Real_Estate controller
 * @package        JUX_Real_Estate
 * @subpackage    Controller
 * @since        3.0
 *
 */
class JUX_Real_EstateControllerAjax extends JControllerForm {

    /**
     * Method to get State from a Country by ID
     */
    public function getStates() {
	$country_id = JRequest::getInt('country_id');
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('id, state_name');
	$query->from('#__re_states');
	if ($country_id) {
	    $query->where('country_id=' . (int) $country_id);
	}
	$query->order('id');
	$db->setQuery($query);

	$state = $db->loadObjectlist();
	echo '<select id="locstate" name="locstate" class="input-medium">';
	echo '<option value="">' . JText::_('COM_JUX_REAL_ESTATE_SELECT_STATE') . '</option>';
	for ($i = 0, $n = count($state); $i < $n; $i++) {
	    $row = &$state[$i];
	    echo '<option value="' . $row->id . '">' . $row->state_name . '</option>';
	}
	echo '</select>';
	exit();
    }

}