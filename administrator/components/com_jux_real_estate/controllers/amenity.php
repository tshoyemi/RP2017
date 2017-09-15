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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

class JUX_Real_EstateControllerAmenity extends JControllerForm {

    protected $text_prefix = 'COM_JUX_REAL_ESTATE';

    protected function allowAdd($data = array()) {
	$user = JFactory::getUser();
	$allow = null;
	$allow = $user->authorise('core.create', 'com_jux_real_estate.amenity');
	if ($allow === null) {
	    return parent::allowAdd($data);
	} else {
	    return $allow;
	}
    }

    protected function allowEdit($data = array(), $key = 'id') {
	$user = JFactory::getUser();
	// Initialise variables.
	$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

	// Check general edit permission first.
	if ($user->authorise('core.edit', 'com_jux_real_estate.amenity.' . $recordId)) {
	    return true;
	}

	// Since there is no asset tracking, revert to the component permissions.
	return parent::allowEdit($data, $key);
    }

}

?>
