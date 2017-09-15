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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modeladmin');

/**
 * JUX_Real_Estate Component - State ModelAdmin
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelState extends JModelAdmin {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_STATE';

    /**
     * Returns a Table object, always creating it
     *
     * @param    type    $type    The table type to instantiate
     * @param    string    $prefix    A prefix for the table class name. Optional.
     * @param    array    $config    Configuration array for model. Optional.
     *
     * @return    JTable    A database object
     * @since    1.6
     */
    public function getTable($type = 'State', $prefix = 'JUX_Real_EstateTable', $config = array()) {
	return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the row form.
     *
     * @param    array    $data        Data for the form.
     * @param    boolean    $loadData    True if the form is to load its own data (default case), false if not.
     *
     * @return    mixed    A JForm object on success, false on failure
     * @since    1.6
     */
    public function getForm($data = array(), $loadData = true) {

	$form = $this->loadForm('com_jux_real_estate.state', 'state', array('control' => 'jform', 'load_data' => $loadData));
	if (empty($form)) {
	    return false;
	}
	return $form;
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param    object    $record    A record object.
     *
     * @return    boolean    True if allowed to delete the record. Defaults to the permission set in the component.
     * @since    1.6
     */
    protected function canDelete($record) {
	$user = JFactory::getUser();

	if ($record->id) {
	    return $user->authorise('core.delete', 'com_jux_real_estate.state.' . (int) $record->id);
	} else {
	    return parent::canDelete($record);
	}
    }

    /**
     * Method to test whether a record can have its state edited.
     *
     * @param    object    $record    A record object.
     *
     * @return    boolean    True if allowed to change the state of the record. Defaults to the permission set in the component.
     * @since    1.6
     */
    protected function canEditState($record) {
	$user = JFactory::getUser();

	if ($record->id) {
	    return $user->authorise('core.edit.state', 'com_jux_real_estate.state.' . (int) $record->id);
	} else {
	    return parent::canEditState($record);
	}
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     * @since    1.6
     */
    protected function loadFormData() {
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.state.data', array());

	if (empty($data)) {
	    $data = $this->getItem();
	}

	return $data;
    }

}