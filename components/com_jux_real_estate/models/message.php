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


jimport('joomla.application.component.modellist');

/**
 * JUX_Real_Estate Component - Message JModelList
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelMessage extends JModelList {

    /** @var int */
    var $_id = null;

    /**
     * Constructor.
     *
     * @param	array	An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array()) {
	if (empty($config['filter_fields'])) {
	    $config['filter_fields'] = array(
		'id', 'a.id',
		'subjects', 'a.subjects',
		'status', 'a.status',
		'ordering', 'a.ordering'
	    );
	}

	parent::__construct($config);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     *
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
	// Compile the store id.
	$id .= ':' . $this->getState('filter.status');

	return parent::getStoreId($id);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
	// Initialise variables.
	$app = JFactory::getApplication();
	$params = $app->getParams();

	// Get the pagination request variables
	$limit = $params->get('list_limit', $app->getCfg('list_limit'));

	$limit = $app->getUserStateFromRequest('com_jux_real_estate.message.limit', 'limit', $limit);

	$this->setState('list.limit', $limit);

	$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

	$this->setState('list.start', $limitstart);

	$orderCol = $app->getUserStateFromRequest('com_jux_real_estate.message.list.filter_order', 'filter_order', '', 'string');
	if (!in_array($orderCol, $this->filter_fields)) {
	    $orderCol = 'a.status, a.date_created DESC';
	    $listOrder = '';
	} else {
	    $listOrder = $app->getUserStateFromRequest('com_jux_real_estate.message.list.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
	    if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
		$listOrder = 'ASC';
	    }
	}

	$this->setState('list.ordering', $orderCol);

	$this->setState('list.direction', $listOrder);

	$this->setState('filter.status');

	// Optional filter text
	$this->setState('list.filter', JRequest::getString('filter-search'));

	$this->_id = (int) JRequest::getVar('id', 0);

	// Load the parameters.
	$this->setState('params', $params);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return	string	An SQL query
     * @since	1.6
     */
    protected function getListQuery() {
	$user = JFactory::getUser();
	$user->get('id');

	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query->select($this->getState('list.select', 'a.*'));
	$query->from($db->quoteName('#__re_messages') . ' AS a');
	$query->select('r.title AS realty, r.description AS description, r.alias AS realty_alias');
	$query->join('LEFT', '#__re_realties AS r ON r.id = a.realty_id');
	$query->join('LEFT', '#__re_agentrealty AS ar ON ar.agent_id = r.id');
	$query->select('m.username AS `agent`');
	$query->join('LEFT', '#__re_agents AS m ON m.id = ar.agent_id');

	if ($this->_id && JRequest::getVar('layout') == 'message') {
	    $query->where('a.id = ' . $this->_id . ' AND a.user_id = ' . $user->id);
	    //Update status when read message
	    $query2 = $db->getQuery(true);
	    $query2->update('#__re_messages');
	    $query2->set('`status` = 1');
	    $query2->where('id = ' . $this->_id);
	    $query2->setQuery($query);
	    $query2->query();
	} elseif ($user->id && JRequest::getVar('layout') == 'list') {
	    $query->where('a.user_id = ' . $user->id);
	}
	else
	    $query->where('a.id =' . $this->_id);

	// Filter by state
	$state = $this->getState('filter.status');
	if (is_numeric($state)) {
	    $query->where('a.status = ' . (int) $state);
	}

	// Filter by client search. Title.
	if ($filter = $this->getState('list.filter')) {
	    // clean filter variable
	    $filter = JString::strtolower($filter);
	    $filter = $db->Quote('%' . $db->escape($filter, true) . '%', false);

	    // filter by subjects
	    $query->where('LOWER( a.subject ) LIKE ' . $filter);
	}

	return $query;
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
    public function getTable($type = 'Message', $prefix = 'JUX_Real_EstateTable', $config = array()) {
	return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to store the Message
     *
     * @access public
     * @param $data
     * @return boolean True on success
     */
    function store($data) {
	$row = $this->getTable();

	// bind the form fields to the version table
	if (!$row->bind($data)) {
	    $this->setError($this->_db->getErrorMsg());
	    return false;
	}

	// if new item, order last in appropriate group
	if (!$row->id) {
	    $date = JFactory::getDate();
	    $row->date_created = $date->toSql();
	}

	// make sure the version is valid
	if (!$row->check()) {
	    $this->setError($this->_db->getErrorMsg());
	    return false;
	}

	// store the version table to the database
	if (!$row->store()) {
	    $this->setError($this->_db->getErrorMsg());
	    return false;
	}

	return $row;
    }

    /**
     * Method to remove a Message
     *
     * @access public
     * @param $cid
     * @return boolean	True on success
     */
    function delete($id = 0) {
	if (!empty($id)) {
	    $query = 'DELETE FROM #__re_messages'
		    . ' WHERE id = ' . $id;
	    ;
	    $this->_db->setQuery($query);
	    if (!$this->_db->query()) {
		JError::raiseError(500, $this->_db->stderr());
		return false;
	    }
	}
	return true;
    }

    /**
     * Send message email
     *
     * @param object $post
     */
    function sendEmails($post, $row) {
	$config_email = JUX_Real_EstateFactory::getConfiguration();
        
	//send email to agent:
	$mailer = JFactory::getMailer();
	$config = JFactory::getConfig();
	$sender = array(
	    $config->get('mailfrom'),
	    $config->get('fromname')
	);
	$mailer->setSender($sender);
	$recipient = $post['toemail'];
	$mailer->addRecipient($recipient);
	$body = str_replace('{email}', $post['email'], $config_email->email_user_to_agent_body);

	$mailer->setSubject($config_email->email_user_to_agent_subject);
	$mailer->isHTML(true);
	$mailer->Encoding = 'base64';
	$mailer->setBody($body);
	$send = $mailer->Send();

	//send email to user:
	$mailer2 = JFactory::getMailer();
	$mailer2->setSender($sender);
	$recipient2 = $post['email'];
	$mailer2->addRecipient($recipient2);
	$config_email->email_agent_to_user_body = $post['name'] . '<br/>' . $post['content'] . '<br/>' . $config_email->email_agent_to_user_body;
	$body2 = str_replace('{email}', $post['toemail'], $config_email->email_agent_to_user_body);

	$mailer2->setSubject($config_email->email_agent_to_user_subject);
	$mailer2->isHTML(true);
	$mailer2->Encoding = 'base64';
	$mailer2->setBody($body2);
	$send2 = $mailer2->Send();
	if ($send2 !== true || $send !== true) {
	    $app = JFactory::getApplication();
	    $url = 'index.php?option=com_jux_real_estate&view=' . $post['view_page'] . '&id=' . (int) $post['id_page'] . '&Itemid=' . $post['menu_itemid'];
	    $_SESSION['sentmail'] = 'no';
	    $app->redirect($url);
	} else {
	    $app = JFactory::getApplication();
	    $url = 'index.php?option=com_jux_real_estate&view=' . $post['view_page'] . '&id=' . (int) $post['id_page'] . '&Itemid=' . $post['menu_itemid'];
	    $_SESSION['sentmail'] = 'yes';
	    $app->redirect($url);
	}
       
    }

    function sendEmails_old($post, $row) {
	if (empty($post['toemail'])) {
	    $query = "SELECT email, name FROM #__users WHERE id = " . $post['receive_id'];
	    $this->_db->setQuery($query);
	    $item = $this->_db->loadObject();
	    $post['toemail'] = $item->email;
	}

	$site_name = JURI::base();
	//Notification email send to user
	//$subject = $post['subject'];
	$body = '<table cellspacing="5" cellpadding="5" ><tr>';
	$body .= '<td colspan="2">' . JText::sprintf('COM_JUX_REAL_ESTATE_HELLO_S_YOU_HAVE_GOT_A_MESSAGE_FROM_S_ABOUT_THIS_REALTY', (!empty($item->name)) ? $item->name : JRequest::getString('toname'), $site_name)
		. '<a href="' . $site_name . '/index.php?option=com_jux_real_estate&view=realty&id=' . $post['realty_id'] . '&Itemid=' . $_REQUEST['Itemid'] . '">'
		. $site_name . '/index.php?option=com_jux_real_estate&view=realty&id=' . $post['realty_id'] . '&Itemid=' . $_REQUEST['Itemid'] . '</a><br/><br/>';
	$body .= JText::_('COM_JUX_REAL_ESTATE_THE_DETAILS_MESSAGE_IS_BELLOW');
	$body .= nl2br($post['content']) . '<br/>';
	$body .= JText::_('COM_JUX_REAL_ESTATE_TO_REPLAY_THIS_MESSAGE_YOU_CAN_JUST_CLICK_TO_REPLAY_OR_FOLLOW_THIS_LINK_TO_GET_YOUR_MESSAGE_BOARD');
	$body .= '<br/><a href="' . $site_name . '/index.php?option=com_jux_real_estate&view=message&layout=message&id=' . $row->id . '">' . $site_name . '/index.php?option=com_jux_real_estate&view=message&layout=message&id=' . $row->id . '</a>';
	$body .= '</td></tr></table>';
	$email = new JMail();

	$email->sendMail($post['email'], $post['name'], $post['toemail'], $body, 1);
    }

    public function getAgent() {
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$user = JFactory::getUser();
	$user->get('id');
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
        $query->where('a.user_id = ' . (int) $user->id);
        $query->where('a.published = 1');
        $db->setQuery($query);
        $results = $db->loadObject();

        return $results;
    }

}
