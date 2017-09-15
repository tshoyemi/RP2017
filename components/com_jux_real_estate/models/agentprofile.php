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

// import Joomla modelform library
jimport('joomla.application.component.modelform');

/**
 * JUX_Real_Estate Component - AddAgent Model
 *
 * @package     Joomla.Site
 * @subpackage  JUX_Real_Estate
 * @since 1.5
 */
class JUX_Real_EstateModelAgentProfile extends JModelForm {

    /** @var int agent id */
    var $_id = null;
    protected $view_item = 'form';
    protected $_item = null;

    /** @var agent object */
    var $_data = null;

    /** @var plan object */
    var $_plan = null;
    protected $_fields;
    protected $_recurringType;
    protected $item;

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
     * @param   type    $type   The table type to instantiate
     * @param   string  $prefix A prefix for the table class name. Optional.
     * @param   array   $config Configuration array for model. Optional.
     *
     * @return  JTable  A database object
     * @since   1.6
     */
    public function getTable($type = 'Agent', $prefix = 'JUX_Real_EstateTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.6
     */
    public function getData() {
        if ($this->data === null) {

            $userId = $this->getState('user.id');

            // Initialise the table with JUser.
            $this->data = new JUser($userId);



            // Override the base user data with any data in the session.
            $temp = (array) JFactory::getApplication()->getUserState('com_jux_real_estate.edit.agentprofile.data', array());

            foreach ($temp as $k => $v) {
                $this->data->$k = $v;
            }



            $registry = new Registry($this->data->params);
            $this->data->params = $registry->toArray();

            // Get the dispatcher and load the users plugins.
            $dispatcher = JEventDispatcher::getInstance();
            JPluginHelper::importPlugin('user');

            // Trigger the data preparation event.
            $results = $dispatcher->trigger('onContentPrepareData', array('com_jux_real_estate.agentprofile', $this->data));

            // Check for errors encountered while preparing the data.
            if (count($results) && in_array(false, $results, true)) {
                $this->setError($dispatcher->getError());
                $this->data = false;
            }
        }

        return $this->data;
    }

    protected function populateState() {
        $app = JFactory::getApplication('site');
        $params = JFactory::getApplication()->getParams('com_jux_real_estate');
        $userId = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.agent.id');

        $userId = !empty($userId) ? $userId : (int) JFactory::getUser()->get('id');

        $user = JFactory::getUser();
        $this->setState('user.id', $user->id);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);
    }

    protected function loadFormData() {
        // Check the session for previously entered form data.
//        $data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.agentprofile.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * Method to get the row form.
     *
     * @param   array   $data       Data for the form.
     * @param   boolean $loadData   True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed   A JForm object on success, false on failure
     * @since   1.6.
     */
    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_jux_real_estate.agentprofile', 'agentprofile', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getAgent() {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('user.id');
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
        $query->where('a.user_id = ' . (int) $pk);
        $query->where('a.published = 1');
        $db->setQuery($query);
        $results = $db->loadObject();

        return $results;
    }

    public function getDay() {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('user.id');
        $date_now = date("Y/m/d");
        $query->select("a.id,DATEDIFF ( DATE_ADD(p.date_created,INTERVAL p.days DAY),'" . $date_now . "') AS TongSoNgay");
        $query->from('#__re_agents AS a');
        $query->join('LEFT', '#__re_plans AS p ON p.id = a.plan_id');
        $query->where('a.user_id =' . (int) $pk);
        $db->setQuery($query);
        $results = $db->loadObject();
        return $results;
    }

    /*     * D
     * Method to get a single rDecord.
     *
     * @param   integer The id of the primary key.
     *
     * @return  mixed   Object on success, false on failure.
     */

    public function getItem($pk = null) {
        // Initialise variables.
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('user.id');

        $agentInfo = $this->getAgent($pk);

        $pk = $agentInfo->id;


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

        if (empty($item->id)) {
            $user = JFactory::getUser();

            $item->email = $user->get('email');
            $item->email_confirm = $user->get('email');
        }

        return $item;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */

    /**
     * get Plan object
     */
    function getPlans($id = null) {

        $db = $this->_db;
        $date = JFactory::getDate();
        $now = $date->toSql();
        $nullDate = $db->getNullDate();

        $where = array();
        $where[] = 'published = 1';
        $where[] = '( publish_up = ' . $db->Quote($nullDate) . ' OR publish_up <= ' . $db->Quote($now) . ' )';
        $where[] = '( publish_down = ' . $db->Quote($nullDate) . ' OR publish_down >= ' . $db->Quote($now) . ' )';

        $query = 'SELECT DISTINCT *'
                . ' FROM #__re_plans'
                //. $where
                . ' ORDER BY name';
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
     * Save the agent.
     */
    function store($post) {
        // get agent table instance
        $row = $this->getTable();

        // bind the form fields to the version table
        if (!$row->bind($post)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $user = JFactory::getUser();
        $date = JFactory::getDate();

        $row->id = 0;
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
        $row = $this->getTable();
        $row->load($data['order_id']);

        if ($row->id == 0) {
            $this->setError(JText::_('COM_JUX_REAL_ESTATE_AGENT_NOT_FOUND'));
            return false;
        }

        $date = JFactory::getDate();
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
     * @return boolean  True on success
     */
    function delete($id) {
        if ($id) {
            $query = 'DELETE FROM #__re_agents'
                    . ' WHERE id = ' . $id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    public function save($data) {
        // Save image to server
        if (isset($_FILES) && $_FILES['avatar']['name'] != null) {
            $data['avatar'] = $this->saveImage($_FILES, 'avatar');
        }

        $app = JFactory::getApplication();
        $post = JRequest::get('post');
        // Initialise variables.
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('agent.id');
        $table = $this->getTable();
        $isNew = empty($pk);
        $configs = JUX_Real_EstateFactory::getConfiguration();

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
        $this->setState('agent.id', $table->id);
        return true;
    }

    public function update($data) {
        $userId = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('user.id');

        $user = new JUser($userId);

        // Prepare the data for the user object.
        $data['email'] = JStringPunycode::emailToPunycode($data['email1']);
        $data['password'] = $data['oldpassword'];

        // Unset the username if it should not be overwritten
        $username = $data['username'];
        $isUsernameCompliant = $this->getState('user.username.compliant');

        if (!JComponentHelper::getParams('com_jux_real_estate')->get('change_login_name') && $isUsernameCompliant) {
            unset($data['username']);
        }

        // Unset the block so it does not get overwritten
        unset($data['block']);

        // Unset the sendEmail so it does not get overwritten
        unset($data['sendEmail']);

        // Handle the two factor authentication setup
        if (array_key_exists('twofactor', $data)) {
            $model = new JUX_Real_EstateModelUser;

            $twoFactorMethod = $data['twofactor']['method'];

            // Get the current One Time Password (two factor auth) configuration
            $otpConfig = $model->getOtpConfig($userId);

            if ($twoFactorMethod != 'none') {
                // Run the plugins
                FOFPlatform::getInstance()->importPlugin('twofactorauth');
                $otpConfigReplies = FOFPlatform::getInstance()->runPlugins('onUserTwofactorApplyConfiguration', array($twoFactorMethod));

                // Look for a valid reply
                foreach ($otpConfigReplies as $reply) {
                    if (!is_object($reply) || empty($reply->method) || ($reply->method != $twoFactorMethod)) {
                        continue;
                    }

                    $otpConfig->method = $reply->method;
                    $otpConfig->config = $reply->config;

                    break;
                }

                // Save OTP configuration.
                $model->setOtpConfig($userId, $otpConfig);

                // Generate one time emergency passwords if required (depleted or not set)
                if (empty($otpConfig->otep)) {
                    $oteps = $model->generateOteps($userId);
                }
            } else {
                $otpConfig->method = 'none';
                $otpConfig->config = array();
                $model->setOtpConfig($userId, $otpConfig);
            }

            // Unset the raw data
            unset($data['twofactor']);

            // Reload the user record with the updated OTP configuration
            $user->load($userId);
        }

        // Bind the data.
        if (!$user->bind($data)) {
            $this->setError(JText::sprintf('COM_USERS_PROFILE_BIND_FAILED', $user->getError()));

            return false;
        }

        // Load the users plugin group.
        JPluginHelper::importPlugin('user');

        // Null the user groups so they don't get overwritten
        $user->groups = null;

        // Store the data.
        if (!$user->save()) {
            $this->setError($user->getError());

            return false;
        }

        $user->tags = new JHelperTags;
        $user->tags->getTagIds($user->id, 'com_jux_real_estate.user');

        return $user->id;
    }

    //save image to server
    public function saveImage($images, $name_arr) {
        $target_dir = 'images/jux_real_estate/agents/';
        $target_file = $target_dir . basename($_FILES[$name_arr]['name']);

        if (move_uploaded_file($_FILES[$name_arr]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES[$name_arr]['name']) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }

        return $target_file;
    }

}
