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
 * JUX_Real_Estate Component - Agent ModelAdmin
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelAgent extends JModelAdmin {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_AGENT';

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
    public function getTable($type = 'Agent', $prefix = 'JUX_Real_EstateTable', $config = array()) {
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

	$form = $this->loadForm('com_jux_real_estate.agent', 'agent', array('control' => 'jform', 'load_data' => $loadData));
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
	    return $user->authorise('core.delete', 'com_jux_real_estate.agent.' . (int) $record->id);
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
	    return $user->authorise('core.edit.state', 'com_jux_real_estate.agent.' . (int) $record->id);
	} else {
	    return parent::canEditState($record);
	}
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     * @since    3.0
     */
    protected function loadFormData() {
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.agent.data', array());

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
     * @since    3.0
     */
    protected function prepareTable($table) {

	$table->username = htmlspecialchars_decode($table->username, ENT_QUOTES);
	if (empty($table->id)) {

	    // Set ordering to the last item if not set
	    if (empty($table->ordering)) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT MAX(ordering) FROM #__re_agents');
		$max = $db->loadResult();

		$table->ordering = $max + 1;
	    }
	} else {
	    
	}
    }

    /**
     * Method to toggle the feature setting of agents.
     *
     * @param    array    $pks    The ids of the items to toggle.
     * @param    int        $value    The value to toggle to.
     *
     * @return    boolean    True on success.
     * @since    3.0
     */
    function feature($pks, $value = 0) {
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
		    if (!$table->feature($pk, $value)) {
			$this->setError($table->getError());
			return false;
		    }
		}
	    }
	}
	return true;
    }

    /**
     * Method to toggle the approve setting of agents.
     *
     * @param    array    $pks    The ids of the items to toggle.
     * @param    int        $value    The value to toggle to.
     *
     * @return    boolean    True on success.
     * @since    3.0
     */
    public function approve($pks, $value = 0) {
	// Sanitize the ids.
	$pks = (array) $pks;
	JArrayHelper::toInteger($pks);

	if (empty($pks)) {
	    $this->setError(JText::_('JERROR_NO_ITEMS_SELECTED'));
	    return false;
	}

	$table = $this->getTable();

	try {
	    $db = $this->getDbo();

	    $db->setQuery(
		    'UPDATE #__re_agents' .
		    ' SET approved = ' . (int) $value .
		    ' WHERE id IN (' . implode(',', $pks) . ')'
	    );
	    if (!$db->query()) {
		throw new Exception($db->getErrorMsg());
	    }
	} catch (Exception $e) {
	    $this->setError($e->getMessage());
	    return false;
	}

	$table->reorder();

	// Clean component's cache
	$this->cleanCache();

	return true;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   3.0
     */
    public function save($data) {
	// Initialise variables.
	$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('agent.id');
	$table = $this->getTable();
	$isNew = empty($pk);

	$tableplan = $this->getTable('plan');

	if (!$tableplan->load($data['plan_id'])) {
	    $this->setError($tableplan->getError());
	    return false;
	}

	$data['count_limit'] = $tableplan->count_limit;

	if (!$table->bind($data)) {
	    $this->setError($table->getError());

	    return false;
	}

	if (!$table->check()) {
	    $this->setError($table->getError());

	    return false;
	}

	if (!$table->store()) {
	    $this->setError($table->getError());

	    return false;
	}

	$this->setState('agent.id', $table->id);

	return true;
    }

    /**
     * Method to delete item.
     *
     * @param   array  &$pks  An array of item ids.
     *
     * @return  boolean  Returns true on success, false on failure.
     *
     * @since   3.0
     */
    public function delete(&$pks) {
	// Initialise variables.
	$pks = (array) $pks;
	JArrayHelper::toInteger($pks);
	$table = $this->getTable();

	$images = array();
	foreach ($pks AS $pk) {
	    if ($table->load($pk)) {
		$images[] = $table->avatar;
	    } else {
		throw new Exception($table->getError());
	    }
	}
	if (!parent::delete($pks)) {
	    throw new Exception($this->getError());
	} else {

	    // delete image
	    $dir = JPATH_SITE;

	    if (count($images)) {
		foreach ($images as $image) {
		    jimport('joomla.filesystem.file');
		    if (JFile::exists($dir . $image)) {
			JFile::delete($dir . '/' . $image);
		    }
		}
	    }

	    // Delete the menu assignments
	    $pks = implode(",", $pks);
	    $db = $this->getDbo();
	    $query = $db->getQuery(true);
	    $query->update('#__re_realties');
	    $query->set('agent_id = 0');
	    $query->where('agent_id IN (' . $pks . ') AND (checked_out = 0 OR checked_out = ' . (int) JFactory::getUser()->get('id') . ' )');
	    $db->setQuery((string) $query);
	    $db->query();

	    $query->clear();

	    $query->delete();
	    $query->from('#__re_agents');
	    $query->where('id IN (' . (int) $pks . ')');
	    $db->setQuery((string) $query);
	    $db->query();
	}

	// Clear modules cache
	$this->cleanCache();

	return true;
    }

}