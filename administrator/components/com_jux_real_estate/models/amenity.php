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
jimport('joomla.application.component.modeladmin');

class JUX_Real_EstateModelAmenity extends JModelAdmin {

    protected $text_prefix = 'COM_JUX_REAL_ESTATE';

    public function getTable($type = 'Amenity', $prefix = 'JUX_Real_EstateTable', $config = array()) {
	return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
	// Get the form.
	$form = $this->loadForm('com_jux_real_estate.amenity', 'amenity', array('control' => 'jform', 'load_data' => $loadData));
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
	    return $user->authorise('core.delete', 'com_jux_real_estate.amenity.' . (int) $record->id);
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
	    return $user->authorise('core.edit.state', 'com_jux_real_estate.amenity.' . (int) $record->id);
	} else {
	    return parent::canEditState($record);
	}
    }

    protected function loadFormData() {
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.amenity.data', array());

	if (empty($data)) {
	    $data = $this->getItem();
	}
	return $data;
    }

    function delete(&$pks) {
	// Initialise variables.
	$table = $this->getTable();
	$pks = (array) $pks;
	$user = JFactory::getUser();
	$successful = 0;
	
	// Access checks.
	foreach ($pks as $i => $pk) {
	    if ($table->load($pk)) {
		if (!$user->authorise('core.admin')) {
		    // Prune items that you can't change.
		    unset($pks[$i]);
		    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
		} else {
		    $successful++;
		}
	    }
	}

	// Attempt to change the state of the records.
	if (!$table->delete($pks)) {
	    $this->setError($table->getError());
	    return false;
	}

	return $successful;
    }

    public function saveCats($pks, $post) {
	// Initialise variables.
	$table = $this->getTable();
	$pks = (array) $pks;
	$user = JFactory::getUser();
	$successful = 0;
	
	// Access checks.
	foreach ($pks as $i => $pk) {
	    if ($table->load($pk)) {
		if (!$user->authorise('core.admin')) {
		    // Prune items that you can't change.
		    unset($pks[$i]);
		    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
		} else {
		    $successful++;
		}
	    }
	}

	// Attempt to change the state of the records.
	if (!$table->saveCats($pks, $post)) {
	    $this->setError($table->getError());
	    return false;
	}

	return $successful;
    }

}

?>