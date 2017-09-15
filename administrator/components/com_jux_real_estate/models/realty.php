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

jimport('joomla.filesystem.folder');

/**
 * JUX_Real_Estate Component - Realty ModelAdmin
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelRealty extends JModelAdmin {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_REALTY';

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
    public function getTable($type = 'Realty', $prefix = 'JUX_Real_EstateTable', $config = array()) {
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
	$app = JFactory::getApplication();
	$form = $this->loadForm('com_jux_real_estate.realty', 'realty', array('control' => 'jform', 'load_data' => $loadData));
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
	    return $user->authorise('core.delete', 'com_jux_real_estate.realty.' . (int) $record->id);
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
	    return $user->authorise('core.edit.state', 'com_jux_real_estate.realty.' . (int) $record->id);
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
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.realty.data', array());

	if (empty($data)) {
	    $data = $this->getItem();

	    // Prime some default values.
	    if ($this->getState('realty.id') == 0) {
		
	    } else {

		//populate multiple select lists
		$data->categories = $this->_getCategories($data->id);
		$data->agents = $this->_getAgents($data->id);

		//populate amenities checkbox lists
		$amenities = $this->_getAmenities($data->id);

		$data->general_amenies = $amenities;
		$data->interior_amenies = $amenities;
		$data->exterior_amenies = $amenities;
	    }
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

	$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);

	if ($table->published == 1 && intval($table->publish_up) == 0) {
	    $table->publish_up = JFactory::getDate()->toSql();
	}

	if (empty($table->id)) {

	    // Set ordering to the last item if not set
	    if (!$table->ordering) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT MAX(ordering) FROM #__re_realties');
		$max = $db->loadResult();

		$table->ordering = $max + 1;
	    }
	} else {

	    // Set value for manage column such as modified_date modified_by.
	}
    }

    /**
     * Method to toggle the approve setting of realties.
     *
     * @param    array    $pks    The ids of the items to toggle.
     * @param    int        $value    The value to toggle to.
     *
     * @return    boolean    True on success.
     * @since    3.0
     */
    public function approved($pks, $value = 0) {

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

	    $db->setQuery('UPDATE #__re_realties' . ' SET approved = ' . (int) $value . ' WHERE id IN (' . implode(',', $pks) . ')');
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
     * Method to toggle the feature setting of realties.
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
     * Method to toggle the sold setting of realties.
     *
     * @param    array    $pks    The ids of the items to toggle.
     * @param    int        $value    The value to toggle to.
     *
     * @return    boolean    True on success.
     * @since    3.0
     */
    public function sale($pks, $value = 0) {

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

	    $db->setQuery('UPDATE #__re_realties' . ' SET sale = ' . (int) $value . ' WHERE id IN (' . implode(',', $pks) . ')');
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
    public function getOtherProductName($item) {
	if (!empty($item->related_Realtys)) {
	    $item->related_products = json_decode($item->related_products);
	    if (!empty($item->related_products->product_id) && is_array($item->related_products->product_id)) {
		$item->related_products->product_name = array();
		foreach ($item->related_products->product_id as $index => $product_id) {
		    $item->related_products->product_name[$index] = $this->getProductName($product_id);
		}
	    }
	    $item->related_products = json_encode($item->related_products);
	}

	return $item;
    }

    public function save($data) {
	$extra_field = array();
	foreach ($_REQUEST as $key => $val) {
	    if (strpos($key, 'jp_') !== false) {
		$extra_field[$key] = $val;
	    }
	}
	foreach ($extra_field as $key => $val) {
	    $extra_field[$key] = $key . ':' . $val;
	}
	$data['extra_field'] = implode(',', $extra_field);
	$app = JFactory::getApplication();
	$post = JRequest::get('post');

	// Initialise variables.
	$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('realty.id');
	$table = $this->getTable();

	if (isset($post['gallery'])) {
	    $data['gallery'] = $post['gallery'];
	}

	if ($app->isAdmin()) {
	    $configs = JUX_Real_EstateFactory::getConfigs();
	} else {
	    $configs = JUX_Real_EstateFactory::getConfiguration();
	}

	// Alter the title for save as copy
	if (JRequest::getVar('task') == 'save2copy') {
	    list($title, $alias) = $this->generateNewTitle($data['alias'], $data['title']);
	    $data['title'] = $title;
	    $data['alias'] = $alias;
	}

	if (!$table->bind($data)) {
	    $this->setError($table->getError());

	    return false;
	}

	$this->prepareTable($table);

	if (!$table->check()) {
	    $this->setError($table->getError());

	    return false;
	}

	if (!$table->store()) {
	    $this->setError($table->getError());

	    return false;
	}

	// saving extra fields
	$JUXFields = new JUX_Real_EstateFields();
	$JUXFields->saveFieldValues($table->id);
	$this->setState('realty.id', $table->id);
	$this->storeImages($data, $table->id);

	return true;
    }

    public function store($data) {
	parent::save($data);
	$row = parent::getItem($this->getState($this->getName() . '.id'));
	$data['id'] = (int) $row->id;

	// save files
	$this->storeFiles($row->id);
	return $data;
    }

    public function saveRealtyAmid($validData) {

	$realty_id = $this->getState($this->getName() . '.id');

	$categories = $validData['categories'];

	// Agents
	$agents = $validData['agents'];

	// Amenities
	$amens = array();
	if ($general_amenies = $validData['general_amenies']) {
	    $amens = array_merge($amens, $general_amenies);
	}
	if ($interior_amenies = $validData['interior_amenies']) {
	    $amens = array_merge($amens, $interior_amenies);
	}
	if ($exterior_amenies = $validData['exterior_amenies']) {
	    $amens = array_merge($amens, $exterior_amenies);
	}
	$table = $this->getTable();

	// Attempt to clear Realty amid table in order to save new item

	if (!$table->deleteRealtyAmid($realty_id)) {
	    $this->setError($table->getError());
	    return false;
	}

	// Attemp to save categories in Realty amid table
	if (!$table->storeCatRealtyAmid($realty_id, $categories)) {
	    $this->setError($table->getError());
	    return false;
	}

	// If the Agents array is not empty, clear agent the agent amid table
	// and save new results for agents array
	if (!empty($agents) && $agents[0] != '') {
	    if (!$table->deleteAgentAmid($$realty_id)) {
		$this->setError($table->getError());
		return false;
	    }

	    if (!$table->storeAgentAmid($realty_id, $agents)) {
		$this->setError($table->getError());
		return false;
	    }
	}

	if (!$table->storeAmenities($realty_id, $amens)) {
	    $this->setError($table->getError());
	    return false;
	}
	return true;
    }

    public function getImages() {

	//get id
	$id = JRequest::getVar('id');

	// let load the content if it doesn't already exist
	if (empty($this->_images)) {
	    $db = $this->getDbo();
	    $query = $db->getQuery(true);
	    $query->select('a.*');
	    $query->from('#__re_realtie_images AS a');
	    $query->where('(a.realty_id =  ' . (int) $id . ')');

	    $db->setQuery($query);
	    $this->_images = $db->loadObjectList();
	}

	return $this->_images;
    }

    public function deleteImage($realty_id) {

	$affected_rows = 0;
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	if ($realty_id) {
	    $query->delete('#__re_realtie_images');
	    if (is_int($realty_id)) {
		$query->where('realty_id = ' . $realty_id);
	    } else if (is_array($realty_id)) {
		$realty_id = implode(',', $realty_id);
		$query->where('realty_id IN(' . $realty_id . ')');
	    }

	    // delete image
	    $this->_db->setQuery($query);
	    if (!$this->_db->query()) {
		$this->setError($this->_db->getErrorMsg());
		return false;
	    } else {
		$affected_rows = $this->_db->getAffectedRows();
	    }
	}
	return $affected_rows;
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

	if (JVERSION >= '3.0.0') {
	    foreach ($pks as $pk) {
		$dir = JPATH_ROOT . '/' . 'media' . '/' . 'com_jux_real_estate' . '/' . 'realty_image' . '/' . $pk . '/';
		echo $dir;
		JFolder::delete($dir);
	    }
	}
	if (!parent::delete($pks)) {
	    throw new Exception($this->getError());
	} else {

	    $pks = implode(",", $pks);
	    $query = $this->_db->getQuery(true);
	    $query->delete();
	    $query->from('#__re_field_value  ');
	    $query->where('realty_id IN (' . (int) $pks . ')');
	    $this->_db->setQuery((string) $query);

	    // Check for a database error.
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }

	    // delete from agent realty table
	    $query = $this->_db->getQuery(true);
	    $query->delete();
	    $query->from('#__re_agentrealty');
	    $query->where('realty_id IN (' . $pks . ')');
	    $this->_db->setQuery($query);

	    // Check for a database error.
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }

	    // Check for a database error.
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }

	    // Check for a database error.
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }

	    // delete from file table
	    $query = $this->_db->getQuery(true);
	    $query->delete();
	    $query->from('#__re_realtie_images');
	    $query->where('realty_id IN (' . $pks . ')');
	    $this->_db->setQuery($query);

	    // check for a database error
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }

	    if (JVERSION >= '3.0.0') {

		// delete from file table
		$query = $this->_db->getQuery(true);
		$query->delete();
		$query->from('#__re_realtie_images');
		$query->where('realty_id IN (' . $pks . ')');
		$this->_db->setQuery($query);
	    }

	    // check for a database error
	    if (!$this->_db->query()) {
		throw new Exception($this->_db->getErrorMsg());
	    }
	}

	// Clear modules cache
	$this->cleanCache();

	return true;
    }

    protected function storeImages($realty, $tableid) {

	// delete files
	$this->deleteImage($realty['id']);
	$images = $realty['gallery'];
	if (isset($images) && is_array($images)) {
	    foreach ($images as $image) {

		$image['realty_id'] = $tableid;
		$image['title'] = trim($image['title']);
		$image['path_image'] = trim($image['path_image']);
		$image['main_image'] = isset($image['main_image']) ? trim($image['main_image']) : '0';
		if (!isset($image['title']) && empty($image['path_image'])) {
		    continue;
		}

		$row = $this->getTable('image');

		// bind the form fields to the version table
		if (!$row->bind($image)) {
		    $this->setError($this->_db->getErrorMsg());
		    continue;
		}

		// make sure the album is valid
		if (!$row->check()) {
		    $this->setError($this->_db->getErrorMsg());

		    continue;
		}
		if (!$row->store()) {
		    $this->setError($this->_db->getErrorMsg());

		    continue;
		}
	    }
	}
    }

    protected function _getAmenities($realty_id) {
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select('amen_id')->from('#__re_realtyamid')->where('realty_id = ' . (int) $realty_id)->where('amen_id != 0')->group('amen_id');

	$db->setQuery($query);
	return $db->loadColumn();
    }

    protected function _getCategories($realty_id) {
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select('cat_id')->from('#__re_realtyamid')->where('realty_id = ' . (int) $realty_id)
		->group('cat_id');
	$db->setQuery($query);
	return $db->loadColumn();
    }

    protected function _getAgents($realty_id) {
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select('agent_id')->from('#__re_agentrealty')->where('realty_id = ' . (int) $realty_id)->group('agent_id');

	$db->setQuery($query);
	return $db->loadColumn();
    }

    /**
     * Method to change the title & alias.
     * @param   string   $alias        The alias.
     * @param   string   $title        The title.
     *
     * @return  array  Contains the modified title and alias.
     *
     * @since   11.1
     */
    protected function generateNewTitle($alias, $title, $category_id = null) {

	// Alter the title & alias
	$table = $this->getTable();
	while ($table->load(array('alias' => $alias))) {
	    $title = JString::increment($title);
	    $alias = JString::increment($alias, 'dash');
	}

	return array($title, $alias);
    }

    /**
     * Custom clean the cache of com_jux_real_estate and  modules
     *
     * @since    3.0
     */
    protected function cleanCache($group = null, $client_id = 0) {
	parent::cleanCache('com_jux_real_estate');
	parent::cleanCache('mod_jux_real_estate');
	parent::cleanCache('mod_jux_real_estate_banner');
	parent::cleanCache('mod_jux_real_estate_categories');
	parent::cleanCache('mod_jux_real_estate_slideshow');
	parent::cleanCache('mod_jux_real_estate_types');
    }

}

