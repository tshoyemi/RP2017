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

jimport('joomla.application.component.modeladmin');

/**
 * Item Model for a Configs.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_events_booking
 * @since		1.6
 */
class JUX_Real_EstateModelConfigs extends JModelAdmin {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_CONFIGS';

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
    public function getTable($type = 'Configs', $prefix = 'JUX_Real_EstateTable', $config = array()) {
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
	$form = $this->loadForm('com_jux_real_estate.configs', 'configs', array('control' => 'jform', 'load_data' => $loadData));
	if (empty($form)) {
	    return false;
	}
	return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.configs.data', array());

	if (empty($data)) {
	    $data = $this->getData();
	}

	return $data;
    }

    /**
     * Override to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success, False on error.
     *
     * @since   11.1
     */
    public function save($data) {


	// Save the rules
	if (isset($data['rules'])) {
	    $rules = new JAccessRules($data['rules']);
	    $asset = JTable::getInstance('asset');

	    if (!$asset->loadByName($this->option)) {
		$root = JTable::getInstance('asset');
		$root->loadByName('root.1');
		$asset->name = $this->option;
		$asset->title = $this->option;
		$asset->setLocation($root->id, 'last-child');
	    }
	    $asset->rules = (string) $rules;

	    if (!$asset->check() || !$asset->store()) {
		$this->setError($asset->getError());
		return false;
	    }

	    unset($data['rules']);
	}

	foreach ($data as $key => $value) {
	    $row = array('key' => $key, 'value' => $value);
	    parent::save($row);
	}

	return true;
    }

    /**
     * Logic for the settings edit screen.
     *
     * @access public
     * @return array
     */
    public function getData() {
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select('*');
	$query->from('#__re_configs');

	$this->_db->setQuery($query);

	try {
	    $configRows = $this->_db->loadObjectList();
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return false;
	}

	$data = new JObject();
	for ($i = 0, $n = count($configRows); $i < $n; $i++) {
	    $row = $configRows[$i];
	    $key = $row->key;
	    $value = $row->value;
	    $data->set($key, $value);
	}

	return $data;
    }

}

?>