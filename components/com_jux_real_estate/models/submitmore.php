<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modelform');

/**
 * JUX_Real_Estate Component - AddAgent Model
 *
 * @package		Joomla.Site
 * @subpackage	JUX_Real_Estate
 * @since 1.5
 */
class JUX_Real_EstateModelSubmitmore extends JModelForm {

    /** @var int agent id */
    var $_id = null;

    /** @var agent object */
    var $_data = null;

    /** @var plan object */
    var $_plan = null;
    protected $_fields;
    protected $_recurringType;

    /**
     * Constructor
     *
     * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
     *
     * @since   11.1
     */
    public function __construct($config = array()) {
        parent::__construct($config = array());
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
    public function getTable($type = '', $prefix = 'JUX_Real_EstateTable', $config = array()) {
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
        $form = $this->loadForm('com_jux_real_estate.submitmore', 'submitmore', array('control' => '', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     */
    public function getItem($pk = null) {
        // Initialise variables.
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('list.user_id');
        $table = $this->getTable('agent');

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

        if (empty($item->id)) {
            $user = JFactory::getUser();

            $item->email = $user->get('email');
            $item->email_confirm = $user->get('email');
        }

        return $item;
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {

        $app = JFactory::getApplication('site');
        $user = &JFactory::getUser();
        // Load state from the request.
        $pk = (int) $user->get('id');
        $this->setState('list.user_id', $pk);
        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.submitmore.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * get Plan object
     */
    function getPlans($id = null) {

        $db = $this->_db;
        $date = &JFactory::getDate();
        $now = $date->toSql();
        $nullDate = $db->getNullDate();

        $where = array();

        $where[] = 'published = 1';
        $where[] = '( publish_up = ' . $db->Quote($nullDate) . ' OR publish_up <= ' . $db->Quote($now) . ' )';
        $where[] = '( publish_down = ' . $db->Quote($nullDate) . ' OR publish_down >= ' . $db->Quote($now) . ' )';

        if (isset($id) && $id) {
            $where[] = 'id = ' . $id;
        }

        $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
        $query = 'SELECT DISTINCT *'
                . ' FROM #__re_plans'
                . $where
                . ' ORDER BY name';
        //echo $query;
        if (empty($this->_plans)) {
            $db->setQuery($query);
            if (isset($id) && $id) {
                $this->_plans = $db->loadObject();
            } else {
                $this->_plans = $db->loadObjectList();
            }
        }

        return $this->_plans;
    }

    /**
     *  Get Agent information
     */
    function getAgent() {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('a.*, c.id AS companyid, c.name AS companyname, CONCAT_WS(" ",first_name,last_name) AS name, a.alias as alias, c.alias as co_alias')
                ->from('#__re_agents as a')
                ->leftJoin('#__re_companies as c on c.id = a.company_id')
                ->where('a.user_id = ' . (int) $this->getState('list.user_id'))
                ->where('a.published = 1');

        $db->setQuery($query, 0, 1);
        return $db->loadObject();
    }

    /**
     * Save the agent.
     */
    function store($post) {
        // get agent table instance
        $row = &$this->getTable('agent');

        // bind the form fields to the version table
        if (!$row->bind($post)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $user = &JFactory::getUser();
        $date = &JFactory::getDate();
//        
//        $this->unpublishedAgentOld($user->get('id'));
//        
        // update ngay su dung, so luong order cho agent
        $row->id = (int) $post['table_agent_id'];
        $row->user_id = (int) $user->get('id');
        $row->plan_id = (int) $post['id'];
        $row->date_created = $date->toSql();
        $row->token = JApplication::getHash($row->date_created);
        $row->published = 1;
        $row->ordering = $row->getNextOrder();
        // make sure the agent is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // store the agent table to the database
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $row;
    }

    /**
     * Approve the agent.
     */
    function approve($data) {
        $row = &$this->getTable('agent');
        $row->load($data['order_id']);

        if ($row->id == 0) {
            $this->setError(JText::_('COM_JUX_REAL_ESTATE_AGENT_NOT_FOUND'));
            return false;
        }

        $date = &JFactory::getDate();
        $row->date_paid = $date->toSql();
        $row->transaction_id = ($row->transaction_id != '') ? $row->transaction_id : $data['transaction_id'];
        $row->approved = 1;
        if (!$row->store()) {
            $this->setError(JText::_('COM_JUX_REAL_ESTATE_ERROR_WHILE_SAVING_AGENT'));
            return false;
        }

        return $row;
    }

    /**
     * Method to remove a agent
     *
     * @access public
     * @param $cid
     * @return boolean	True on success
     */
    function delete($id) {
        if ($id) {
            $query = 'DELETE FROM #__re_agents'
                    . ' WHERE id = ' . $id
            ;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

   
   /*
    * function set agent status to unpublished
    */
    function unpublishedAgentOld($user_id) {
        if ($user_id) {
            $query = 'Update #__re_agents' .
                    ' SET published = 0' .
                    ' Where user_id =' . (int) $user_id;
            $this->_db->setQuery($query);

            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

}
?>