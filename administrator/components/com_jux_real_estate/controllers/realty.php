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

jimport('joomla.application.component.controllerform');

/**
 * @package     Joomla.Administrator
 * @subpackage  com_jux_real_estate
 * @since       1.6
 */
class JUX_Real_EstateControllerRealty extends JControllerForm {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_REALTY';

    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowAdd($data = array()) {
	$user = JFactory::getUser();
	$allow = null;
	$allow = $user->authorise('core.create', 'com_jux_real_estate.realty');
	
	if ($allow === null) {
	    return parent::allowAdd($data);
	} else {
	    return $allow;
	}
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowEdit($data = array(), $key = 'id') {
	$user = JFactory::getUser();
	$allow = null;
	$allow = $user->authorise('core.edit', 'com_jux_real_estate.realty');
	if ($allow === null) {
	    return parent::allowEdit($data, $key);
	} else {
	    return $allow;
	}
    }

    protected function postSaveHook(JModelLegacy $model, $validData = array()) {
	$model->saveRealtyAmid($validData);
    }

    //reset count
    public function resetCount() {
	// Check for request forgeries
	JRequest::checkToken() or die('Invalid Token');
	$realty_id = JRequest::getInt('realty_id');

	$db = JFactory::getDbo();
	$query = 'UPDATE #__re_realties SET count = 0 WHERE id = ' . (int) $realty_id . ' LIMIT 1';
	$db->setQuery($query);

	if ($db->Query()) {
	    echo JText::_('COM_JUX_REAL_ESTATE_COUNTER_RESET');
	} else {
	    return false;
	}
    }

    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true)) {
	$model = parent::getModel($name, $prefix, $config);
	return $model;
    }

    protected function generate_response($content) {
	echo json_encode($content);
    }

}
