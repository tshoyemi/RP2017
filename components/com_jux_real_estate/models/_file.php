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
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Methods supporting a list of files.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 */
class JUX_Real_EstateModelFile extends JModelAdmin {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_FILE';

    /**
     * Method to test whether a record can be deleted.
     *
     * @param	object	$record	A record object.
     *
     * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
     * @since	1.6
     */
    protected function canDelete($record) {
	$user = JFactory::getUser();

	if ($record->id) {
	    return $user->authorise('core.delete', 'com_jux_real_estate.file.' . (int) $record->id);
	} else {
	    return parent::canDelete($record);
	}
    }

    /**
     * Method to test whether a record can have its state edited.
     *
     * @param	object	$record	A record object.
     *
     * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
     * @since	1.6
     */
    protected function canEditState($record) {
	$user = JFactory::getUser();

	if ($record->id) {
	    return $user->authorise('core.edit.state', 'com_jux_real_estate.file.' . (int) $record->id);
	} else {
	    return parent::canEditState($record);
	}
    }

    /**
     * Returns a Table object, always creating it
     *
     * @param	type	$type	The table type to instantiate
     * @param	string	$prefix	A prefix for the table class name. Optional.
     * @param	array	$config	Configuration array for model. Optional.
     *
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'File', $prefix = 'JUX_Real_EstateTable', $config = array()) {
	return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the row form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     *
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true) {
	$app = JFactory::getApplication();
	$form = $this->loadForm('com_jux_real_estate.file', 'file', array('control' => 'jform', 'load_data' => $loadData));
	if (empty($form)) {
	    return false;
	}
	return $form;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	$pk	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     * @since	1.6
     */
    public function getItem($pk = null) {
	if ($item = parent::getItem($pk)) {
	    $registry = new JRegistry;

	    if ($item->id) {
		
	    }
	}

	return $item;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.file.data', array());

	if (empty($data)) {
	    $data = $this->getItem();
	}

	return $data;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param	JTable	$table
     *
     * @return	void
     * @since	1.6
     */
    protected function prepareTable($table) {
	if (empty($table->id)) {
	    // Set the values
	    $date = JFactory::getDate();
	    $table->created = $date->toSql();

	    // Set ordering to the last item if not set
	    if (empty($table->ordering)) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT MAX(ordering) FROM #__re_files');
		$max = $db->loadResult();

		$table->ordering = $max + 1;
	    }
	} else {
	    // Set the values
	    if ($table->created == '0000-00-00 00:00:00') {
		$date = JFactory::getDate();
		$table->created = $date->toSql();
	    }
	}
    }

    public function getUploadFiles($realty_id) {
	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);
	$delete_url = JURI::base() . 'index.php?option=com_jux_real_estate&task=realty.deleteimage&id=';

	// Select the required fields from the table.
	$query->select("a.file_name AS name, a.size, a.realty_id, CONCAT('$delete_url', a.id) AS delete_url, 'DELETE' AS delete_type");
	$query->from('#__re_files AS a');

	// Filter by published state
	$query->where('a.published = 1');

	$query->where('a.realty_id = ' . $realty_id);
	// Filter by temp_upload status
	$query->where('a.temp_upload = 1');

	$query->order('a.file_name ASC');
	$db->setQuery($query);
	$items = array();

	try {
	    $items = $db->loadObjectList();
	} catch (RuntimeException $e) {
	    return array();
	}

	// standardize the result
	foreach ($items AS $item) {
	    $item->size = (int) $item->size;
	}

	return $items;
    }

    /**
     * Override method to add function delete actual files.
     *
     * @param   array  &$pks  An array of record primary keys.
     *
     * @return  boolean  True if successful, false if an error occurs.
     */
    public function delete(&$pks) {
	$dispatcher = JEventDispatcher::getInstance();
	$pks = (array) $pks;
	$table = $this->getTable();

	// Iterate the items to delete each one.
	foreach ($pks as $i => $pk) {

	    if ($table->load($pk)) {
		if ($this->canDelete($table)) {
		    $file_name = $table->file_name;
		    if (!$table->delete($pk)) {
			$this->setError($table->getError());
			return false;
		    }

		    $params = JComponentHelper::getParams('com_jux_real_estate');
		    $base_path = '/www/jux_real_estate/Ver1/media/jux_real_estate/files';
		    $base_path = str_replace('{root}', JPATH_ROOT, $base_path);

		    $file_dest = $base_path . '/' . $file_name;

		    // Move uploaded file
		    jimport('joomla.filesystem.file');
		    if (is_file($file_dest)) {
			JFile::delete($file_dest);
		    }
		} else {
		    // Prune items that you can't change.
		    unset($pks[$i]);
		    $error = $this->getError();
		    if ($error) {
			JLog::add($error, JLog::WARNING, 'jerror');
			return false;
		    } else {
			JLog::add(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
			return false;
		    }
		}
	    } else {
		$this->setError($table->getError());
		return false;
	    }
	}

	// Clear the component's cache
	$this->cleanCache();

	return true;
    }

    /**
     * Override method to add function delete actual files.
     *
     * @param   array  &$pks  An array of record primary keys.
     *
     * @return  boolean  True if successful, false if an error occurs.
     */
    public function deleteimage($id) {
	$user = JFactory::getUser();
	$table = $this->getTable();
	if ($table->load($id)) {
	    $file_name = $table->file_name;
	    $realty_id = $table->realty_id;
	    $base_path = JPATH_ROOT . '/' . 'media' . '/' . 'com_jux_real_estate' . '/' . 'realty_image' . '/' . $realty_id . '/';
	    $base_path = str_replace('{root}', JPATH_ROOT, $base_path);

	    $file_dest = $base_path . $file_name;

	    // Move uploaded file
	    jimport('joomla.filesystem.file');
	    if (is_file($file_dest)) {
		JFile::delete($file_dest);
	    }
	} else {
	    return false;
	}

	$query = $this->_db->getQuery(true);
	$query->delete();
	$query->from('#__re_files');
	$query->where('id = (' . $id . ')');
	echo $query;
	$this->_db->setQuery($query);  // Check for a database error.
	if (!$this->_db->query() || !$user->id) {
	    throw new Exception($this->_db->getErrorMsg());
	}
	// Clear modules cache
	$this->cleanCache();

	return true;
    }

    /**
     * Method to perform batch operations on an item or a set of items.
     *
     * @param   array  $commands  An array of commands to perform.
     * @param   array  $pks       An array of item ids.
     * @param   array  $contexts  An array of item contexts.
     *
     * @return  boolean  Returns true on success, false on failure.
     *
     * @since   12.2
     */
    public function batch($commands, $pks, $contexts) {
	// Sanitize ids.
	$pks = array_unique($pks);
	JArrayHelper::toInteger($pks);

	// Remove any values of zero.
	if (array_search(0, $pks, true)) {
	    unset($pks[array_search(0, $pks, true)]);
	}

	if (empty($pks)) {
	    $this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
	    return false;
	}

	$done = false;

	if (!empty($commands['limit'])) {
	    // Set the variables
	    $user = JFactory::getUser();
	    $table = $this->getTable();

	    foreach ($pks as $pk) {
		if ($user->authorise('core.edit', $contexts[$pk])) {
		    $table->reset();
		    $table->load($pk);
		    $table->limit = $commands['limit'];
		    $table->limit_downloads = $commands['limit_downloads'];
		    $table->limit_days = $commands['limit_days'];

		    if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		    }
		} else {
		    $this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
		    return false;
		}
	    }

	    // Clean the cache
	    $this->cleanCache();
	}

	return true;
    }

}
