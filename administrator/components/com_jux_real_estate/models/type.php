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
 * JUX_Real_Estate Component - Type ModelAdmin
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelType extends JModelAdmin {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_TYPE';

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
    public function getTable($type = 'Type', $prefix = 'JUX_Real_EstateTable', $config = array()) {
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

	$form = $this->loadForm('com_jux_real_estate.type', 'type', array('control' => 'jform', 'load_data' => $loadData));
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
	    return $user->authorise('core.delete', 'com_jux_real_estate.type.' . (int) $record->id);
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
	    return $user->authorise('core.edit.state', 'com_jux_real_estate.type.' . (int) $record->id);
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
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.type.data', array());

	if (empty($data)) {
	    $data = $this->getItem();
	}

	return $data;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param    JTable    $table
     *
     * @return    void
     * @since    1.6
     */
    protected function prepareTable($table) {

	$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
	if (empty($table->id)) {

	    // Set ordering to the last item if not set
	    if (empty($table->ordering)) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT MAX(ordering) FROM #__re_types');
		$max = $db->loadResult();

		$table->ordering = $max + 1;
	    }
	} else {
	    // Set value for manage column such as modified_date modified_by.
	}
    }

    /**
     * Method to toggle the showbanner setting of types.
     *
     * @param    array    $pks    The ids of the items to toggle.
     * @param    int        $value    The value to toggle to.
     *
     * @return    boolean    True on success.
     * @since    3.0
     */
    function showbanner($pks, $value = 0) {
	// Initialise variables.
	$table = $this->getTable();
	$pks = (array) $pks;

	// Access checks.
	foreach ($pks as $i => $pk) {
	    if ($table->load($pk)) {
		if (!$this->canEditState($table)) {
		    // Prune items that you can't change.
		    unset($pks[$i]);
		    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
		} else {
		    // Attempt to change the state of the records.
		    if (!$table->showbanner($pk, $value)) {
			$this->setError($table->getError());
			return false;
		    }
		}
	    }
	}
	return true;
    }

}