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

// No direct access
defined('_JEXEC') or die;
// Base this model on the backend version.
jimport('joomla.application.component.modelform');

/**
 * @package        Joomla.Site
 * @subpackage    com_jux_real_estate
 * @since 3.0
 */
class JUX_Real_EstateModelForm extends JModelForm {

    /**
     * @since	1.6
     */
    protected $view_item = 'form';
    protected $_item = null;

    /**
     * Model context string.
     *
     * @var		string
     */
    protected $_context = 'com_jux_real_estate.form';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
	$app = JFactory::getApplication('site');

	// Load state from the request.
	$pk = JRequest::getInt('id');
	$this->setState('realty.id', $pk);

	// Load the parameters.
	$params = $app->getParams();
	$this->setState('params', $params);

	$user = JFactory::getUser();
	if ((!$user->authorise('core.edit.state', 'com_jux_real_estate')) && (!$user->authorise('core.edit', 'com_jux_real_estate'))) {
	    $this->setState('filter.published', 1);
	}
    }

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
	$form = $this->loadForm('com_jux_real_estate.form', 'form', array('control' => 'jform', 'load_data' => $loadData));
	if (empty($form)) {
	    return false;
	}

	return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     * @since    3.0
     */
    protected function loadFormData() {
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.form.data', array());

	if (empty($data)) {
	    $data = $this->getItem();

	    //populate amenities checkbox lists
	    if ($data->id) {
		$amenities = $this->_getAmenities($data->id);
		$data->general_amenies = $amenities;
		$data->interior_amenies = $amenities;
		$data->exterior_amenies = $amenities;
	    }
	}

	return $data;
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
     *
     * @since   11.1
     */
    protected function canDelete($record) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('count(user_id)')
                ->from('#__re_agents')
                ->where("user_id = ".$record->user_id .' AND approved = 1 AND published = 1');
        
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
     *
     * @since   11.1
     */
    protected function canEditState($record) {
	$user = JFactory::getUser();
	return $user->authorise('core.edit.state', $this->option);
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  mixed    Object on success, false on failure.
     *
     * @since   11.1
     */
    public function getItem($pk = null) {
	// Initialise variables.
	$pk = (!empty($pk)) ? $pk : (int) $this->getState('realty.id');
	$table = $this->getTable();

	if ($pk > 0) {
	    // Attempt to load the row.
	    $return = $table->load($pk);

	    // Check for a table object error.
	    if ($return === false && $table->getError()) {
		$this->setError($table->getError());
		return false;
	    }
	}

	// Convert to the JObject before adding other data.
	$properties = $table->getProperties(1);
	$item = JArrayHelper::toObject($properties, 'JObject');

	if (property_exists($item, 'params')) {
	    $registry = new JRegistry;
	    $registry->loadString($item->params);
	    $item->params = $registry->toArray();
	}

	return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param    JTable    $table
     *
     * @return    void
     * @since    3.0
     */
    protected function prepareTable(&$table) {

	$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);

	if ($table->published == 1 && intval($table->publish_up) == 0) {
	    $table->publish_up = JFactory::getDate()->toSql();
	}

	if (empty($table->id)) {
	    // Set ordering to the last item if not set
	    if (empty($table->ordering)) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT MAX(ordering) FROM #__re_realties');
		$max = $db->loadResult();

		$table->ordering = $max + 1;
	    }
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

	    $db->setQuery(
		    'UPDATE #__re_realties' .
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
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   3.0
     */
    public function save($data) {
	//process image: slider & preview image
        
	$data['gallery'] = array();
	if (isset($_FILES) && $_FILES['preview_image']['name'] != null) {
	    $data['preview_image'] = $this->saveImage($_FILES, 'preview_image');
	    if ($data['preview_image'] == 'ext_error') {
		$this->setError('Preview Image: Image extension not allow');
		return false;
	    } elseif ($data['preview_image'] == 'error_upload_image') {
		$this->setError('Sorry, there are an error uploading your file');
		return false;
	    } elseif ($data['preview_image'] == 'size_img') {
		$this->setError('Size image too big');
		return false;
	    }
	}
	if (isset($_FILES)) {
	    foreach ($_FILES as $key => $val) {
		if ($key != 'preview_image' && isset($val['name']) && $val['name'] != null) {
		    $data['tmp'][$key] = $this->saveImage($val, $key);
		    if ($data['tmp'][$key] == 'ext_error') {
			$this->setError('Slider Image: Image extension not allow');
			return false;
		    } elseif ($data['tmp'][$key] == 'error_upload_image') {
			$this->setError('Sorry, there are an error uploading your file');
			return false;
		    } elseif ($data['tmp'][$key] == 'size_img') {
			$this->setError('Size image too big');
			return false;
		    }
		}
	    }
	}
	foreach ($_POST['gallery'] as $key => $val) {
	    if (isset($data['tmp'])) {
		foreach ($data['tmp'] as $k => $v) {
		    if ($key == $k) {
			$_POST['gallery'][$key]['path_image'] = $v;
			$data['gallery'][$key] = $_POST['gallery'][$key];
		    } elseif ($val['path_image'] != '') {
			$data['gallery'][$key] = $_POST['gallery'][$key];
		    }
		}
	    } elseif ($val['path_image'] != '') {
		$data['gallery'][$key] = $_POST['gallery'][$key];
	    }
	}
	//end process image

	$app = JFactory::getApplication();
	$post = JRequest::get('post');
	// Initialise variables.

	$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('realty.id');

	$table = $this->getTable();

	$isNew = empty($pk);
	$configs = JUX_Real_EstateFactory::getConfiguration();

	if (!$data) {
	    $this->setError(JText::_('COM_JUX_REAL_ESTATE_ERROR_WHILE_UPLOADING_IMAGE'));
	    return false;
	}

        if(!isset($data['published'])){
            $data['published'] = 1;
        }
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

	// saving extra fields
	$JUXFields = new JUX_Real_EstateFields();
	$this->storeImages($data, $table->id);
	$JUXFields->saveFieldValues($table->id);
	$this->setState('realty.id', $table->id);
	return true;
    }

    public function saveRealtyAmid($validData) {

	$realty_id = $this->getState('realty.id');

	$categories = $validData['catid'];
	// Agents
	$agents = $validData['agent_id'];
	
	// Amenities
	$amens = array();
	$general_amenies = $validData['general_amenies'];
	if ($general_amenies) {
	    $amens = array_merge($amens, $general_amenies);
	}
	$interior_amenies = $validData['interior_amenies'];
	if ($interior_amenies) {
	    $amens = array_merge($amens, $interior_amenies);
	}
	$exterior_amenies = $validData['exterior_amenies'];
	if ($exterior_amenies) {
	    $amens = array_merge($amens, $exterior_amenies);
	}
	$table = $this->getTable();
	// Attempt to clear Realty amid table in order to save new item

	if (!$table->deleteRealtyAmid($realty_id)) {
	    $this->setError($table->getError());
	    return false;
	}
	// If the Agents array is not empty, clear agent the agent amid table
	// and save new results for agents array
	if (!empty($agents) && $agents[0] != '') {
	    if (!$table->deleteAgentAmid($realty_id)) {
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

    /**
     * Method to delete item.
     *
     * @param   array  &$pks  An array of item ids.
     *
     * @return  boolean  Returns true on success, false on failure.
     *
     * @since   3.0
     * 
     */
    public function delete(&$pks) {
	// Initialise variables.
	$dispatcher = JDispatcher::getInstance();
	$pks = (array) $pks;
	JArrayHelper::toInteger($pks);
	$table = $this->getTable();
	// Include the content plugins for the on delete events.
	JPluginHelper::importPlugin('content');
       
	$images = array();
 
	foreach ($pks AS $i => $pk) {
           
	    if ($table->load($pk)) {
          
		$images[] = $table->images;
                
	    } else {
                
		throw new Exception($table->getError());
	    }
	}
	if ($this->canDelete($table)) {
	    $context = $this->option . '.' . $this->name;

	    // Trigger the onContentBeforeDelete event.
	    $result = $dispatcher->trigger($this->event_before_delete, array($context, $table));
	    if (in_array(false, $result, true)) {
		$this->setError($table->getError());
		return false;
	    }

	    if (!$table->delete($pk)) {
                
		$this->setError($table->getError());
		return false;
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

		// delete from realty amid table
		$query = $this->_db->getQuery(true);
		$query->delete();
		$query->from('#__re_realtyamid');
		$query->where('realty_id IN (' . $pks . ')');
		$this->_db->setQuery($query);

		// Check for a database error.
		if (!$this->_db->query()) {
		    throw new Exception($this->_db->getErrorMsg());
		}

		// delete from openhouse table
		// Check for a database error.
		if (!$this->_db->query()) {
		    throw new Exception($this->_db->getErrorMsg());
		}

		// delete image
		// delete from openhouse table
		// Check for a database error.
		if (!$this->_db->query()) {
		    throw new Exception($this->_db->getErrorMsg());
		}
	    }

	    // Trigger the onContentAfterDelete event.
	    $dispatcher->trigger($this->event_after_delete, array($context, $table));
	} else {

	    // Prune items that you can't change.
	    unset($pks[$i]);
	    $error = $this->getError();
	    if ($error) {
		JError::raiseWarning(500, $error);
		return false;
	    } else {
		JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));
		return false;
	    }
	}

	// Clear modules cache
	$this->cleanCache();

	return true;
    }

    protected function _getImageSlide($realty_id) {
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select('*')
		->from('#__re_files')
		->where('realty_id= ' . (int) $realty_id)
		->group('id');

	$db->setQuery($query);
	return $db->loadColumn();
    }

    protected function _getAmenities($realty_id) {

	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select('amen_id')
		->from('#__re_realtyamid')
		->where('realty_id = ' . (int) $realty_id)
		->where('amen_id != 0')
		->group('amen_id');
	//	echo "Amen" . $query;
	$db->setQuery($query);
	return $db->loadColumn();
    }

    protected function _getCategories($realty_id) {
	$query = $this->_db->getQuery(true);

	$query->select('cat_id')
		->from('#__realties')
		->where('id = ' . (int) $realty_id)
		->where('cat_id != 0')
		->group('cat_id');

	$this->_db->setQuery($query);
	return $this->_db->loadResultArray();
    }

    protected function _getAgents($realty_id) {
	$query = $this->_db->getQuery(true);

	$query->select('agent_id')
		->from('#__re_agentrealty')
		->where('realty_id = ' . (int) $realty_id)
		->group('agent_id');

	$this->_db->setQuery($query);
	return $this->_db->loadResultArray();
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

    function _sendEmailNewProperty($item = array()) {
	$user = JFactory::getUser();
	$description = substr(strip_tags($item->description), 0, 300) . '...';
	$details = '<b>' . JText::_('COM_JUX_REAL_ESTATE_TITLE') . ':</b>&nbsp;&nbsp;' . $item->title . '<br />'
		. '<b>' . JText::_('COM_JUX_REAL_ESTATE_PRICE') . ':</b>&nbsp;&nbsp;' . $item->price . '<br />'
		. '<b>' . JText::_('COM_JUX_REAL_ESTATE_DESCRIPTION') . ':</b>&nbsp;&nbsp;' . $description . '<br />'
		. '<br/>';

	$link = '<a href="' . JURI::base() . '/index.php?option=com_jux_real_estate&view=realty&id=' . $item->id . '&Itemid=' . $_REQUEST['Itemid'] . '">'
		. JURI::base() . '/index.php?option=com_jux_real_estate&view=realty&id=' . $item->id . '&Itemid=' . $_REQUEST['Itemid'] . '</a>';
	$ret['search'] = array('{customer}', '{details}', '{link}');
	$ret['replace'] = array($user->name, $details, $link);
	return $ret;
    }

    /**
     * Method to (un)publish a realty
     *
     * @access	public
     * @return	boolean	True on success
     */
    function publish($id = null, $publish = 1) {
	$user = JFactory::getUser();
	if (!empty($id)) {
	    $query = 'UPDATE #__re_realties'
		    . ' SET published = ' . (int) $publish
		    . ' WHERE id = ' . (int) $id
		    . ' AND (checked_out = 0 OR (checked_out = ' . (int) $user->get('id') . ' ))'
	    ;
	    $this->_db->setQuery($query);

	    if (!$this->_db->query()) {
		$this->setError($this->_db->getErrorMsg());
		return false;
	    }
	}

	return true;
    }

    function sold($id = null, $sold = 1) {
	$user = JFactory::getUser();

	if (!empty($id)) {
	    $query = 'UPDATE #__re_realties'
		    . ' SET sale = ' . (int) $sold
		    . ' WHERE id = ' . (int) $id
		    . ' AND (checked_out = 0 OR (checked_out = ' . (int) $user->get('id') . ' ))'
	    ;
	    $this->_db->setQuery($query);
	    if (!$this->_db->query()) {
		$this->setError($this->_db->getErrorMsg());
		return false;
	    }
	}
	return true;
    }

    /**
     * Method to (un)approve a realty
     *
     * @access	public
     * @return	boolean	True on success
     */
    function approve($id = null, $publish = 1) {
	$user = & JFactory::getUser();

	if (!empty($id)) {
	    $query = 'UPDATE #__re_realties'
		    . ' SET approved = ' . (int) $publish
		    . ' WHERE id = ' . (int) $id
		    . ' AND (checked_out = 0 OR (checked_out = ' . (int) $user->get('id') . ' ))'
	    ;
	    $this->_db->setQuery($query);
	    if (!$this->_db->query()) {
		$this->setError($this->_db->getErrorMsg());
		return false;
	    }
	}

	return true;
    }

    /** set count item for agent */
    function updateCount() {
	$user = &JFactory::getUser();
	$query = "SELECT count FROM #__re_agents WHERE user_id = " . (int) $user->get('id') . " LIMIT 1";
	$this->_db->setQuery($query);
	$count = $this->_db->loadResult();
	if ($count > 0) {
	    $query = 'UPDATE #__re_agents'
		    . ' SET `count` = (`count` + 1)'
		    . ', `count_limit` = (`count_limit` - 1)'
		    . ' WHERE user_id = ' . (int) $user->get('id');
	} else {
	    $query = 'UPDATE #__re_agents'
		    . ' SET `count` = 1'
		    . ', `count_limit` = (`count_limit` - 1 )'
		    . ' WHERE user_id = ' . (int) $user->get('id');
	}

	$this->_db->setQuery($query);
	$this->_db->query();
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

    public function getAgent() {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$ur = JFactory::getUser();
	$ur->get('id');
	$date_current = JHTML:: date(time(), 'Y-m-d');
        $query->select(
		$this->getState(
			'list.select', ' DISTINCT a.*, count(m.id) AS `count`, p.name AS `plan`,  p.days AS `days`, p.count_limit AS `plan_countlimit`'
			. ', (CASE WHEN p.days_type="day" '
			. ' THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days DAY),"%Y-%m-%d"), "' . $date_current . '")'
			. ' WHEN p.days_type="month" '
			. ' THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days MONTH),"%Y-%m-%d"), "' . $date_current . '")'
			. ' END) AS `sub_days`'
		)
	);
       // $query->select('DISTINCT a.*, count(m.id) AS `count`, p.name AS `plan`, p.count_limit AS `plan_countlimit`');
        $query->from('#__re_agents as a');
        $query->join('LEFT', '#__re_realties AS m ON (m.agent_id = a.id OR m.user_id = a.user_id)');
        $query->join('LEFT', '#__re_plans AS p ON p.id = a.plan_id');
        $query->where('a.user_id = ' . (int) $ur->id);
        $query->where('a.published = 1');
        $db->setQuery($query);
        $results = $db->loadObject();

        return $results;
    }

    //save image to server
    public function saveImage($images, $name_arr) {
	$target_dir = 'images/jux_real_estate/realties/';
	$target_file = $target_dir . basename($_FILES[$name_arr]['name']);
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	$ext_tmp = JUX_Real_EstateFactory::getConfiguration();
	$ext = explode(',', $ext_tmp->image_exts);
	if (!in_array($imageFileType, $ext)) {
	    return 'ext_error';
	}
	if ($_FILES[$name_arr]['size'] / (1024 * 1024) > $ext_tmp->images_zise) {
	    return 'size_img';
	}
	if (move_uploaded_file($_FILES[$name_arr]["tmp_name"], $target_file)) {
	    return $target_file;
	} else {
	    return 'error_upload_image';
	}
    }

}
