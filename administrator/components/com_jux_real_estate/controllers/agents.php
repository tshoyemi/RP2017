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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Agents list controller class.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 * @since    1.6
 */
class JUX_Real_EstateControllerAgents extends JControllerAdmin {

    /**
     * @var        string    The prefix to use with controller messages.
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_AGENTS';

    /**
     * Constructor.
     *
     * @param    array    $config    An optional associative array of configuration settings.
     * @return    JUX_Real_EstateControllerAgents
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
	parent::__construct($config);

	$this->registerTask('unapprove', 'approve');
	$this->registerTask('unfeatured', 'featured');
    }

    /**
     * Method to toggle the featured setting of a list of agents.
     *
     * @return    void
     * @since    1.6
     */
    function featured() {
	// Check for request forgeries
	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	// Initialise variables.
	$user = JFactory::getUser();
	$ids = JRequest::getVar('cid', array(), '', 'array');
	$values = array('featured' => 1, 'unfeatured' => -1);
	$task = $this->getTask();
	$value = JArrayHelper::getValue($values, $task, 1, 'int');

	// Access checks.
	foreach ($ids as $i => $id) {
	    if (!$user->authorise('core.edit.state', 'com_jux_real_estate.agent.' . (int) $id)) {
		// Prune items that you can't change.
		unset($ids[$i]);
		JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
	    }
	}

	if (empty($ids)) {
	    JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
	} else {
	    // Get the model.
	    $model = $this->getModel();

	    // approved the items.
	    if (!$model->feature($ids, $value)) {
		JError::raiseWarning(500, $model->getError());
	    } else {
		if ($value == 1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_FEATURED';
		} else if ($value == -1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_UNFEATURED';
		}
		$this->setMessage(JText::plural($ntext, count($ids)));
	    }
	}

	$this->setRedirect('index.php?option=com_jux_real_estate&view=agents');
    }

    /**
     * Method to toggle the approved setting of a list of agents.
     *
     * @return    void
     * @since    1.6
     */
    function approve() {
	// Check for request forgeries
	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	// Initialise variables.
	$user = JFactory::getUser();
	$ids = JRequest::getVar('cid', array(), '', 'array');
	$values = array('approve' => 1, 'unapprove' => 0);
	$task = $this->getTask();
	$value = JArrayHelper::getValue($values, $task, 1, 'int');

	// Access checks.
	foreach ($ids as $i => $id) {
	    if (!$user->authorise('core.edit.state', 'com_jux_real_estate.agent.' . (int) $id)) {
		// Prune items that you can't change.
		unset($ids[$i]);
		JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
	    }
	}

	if (empty($ids)) {
	    JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
	} else {
	    // Get the model.
	    $model = $this->getModel();

	    // Publish the items.
	    if (!$model->approve($ids, $value)) {
		JError::raiseWarning(500, $model->getError());
	    } else {
		if ($value == 1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_APPROVED';
		} else if ($value == 0) {
		    $ntext = $this->text_prefix . '_N_ITEMS_UNAPPROVED';
		}
		$this->setMessage(JText::plural($ntext, count($ids)));
	    }
	}
        
        //send mail to user:
        $id_agent = JRequest::getVar('cid');
        $email_agent = $this->getEmailAgent($id_agent['0']);
        $config = JUX_Real_EstateFactory::getConfigs();
        $mailer = JFactory::getMailer();
	$sender = array(
	    $config->get('notify_email'),
	    $config->get('store_email_name')
	);
	$mailer->setSender($sender);
	$recipient = $email_agent;
	$mailer->addRecipient($recipient);
	$body = str_replace('{email}', $recipient, $config->email_approved_body);

	$mailer->setSubject($config->email_approved_subject);
	$mailer->isHTML(true);
	$mailer->Encoding = 'base64';
	$mailer->setBody($body);
	$send = $mailer->Send();
        if ( $send !== true ) {
            echo 'Error sending email: ' . $send->__toString();
        }

	$this->setRedirect('index.php?option=com_jux_real_estate&view=agents');
    }

    /**
     * Proxy for getModel.
     *
     * @param    string    $name    The name of the model.
     * @param    string    $prefix    The prefix for the PHP class name.
     *
     * @return    JModel
     * @since    3.0
     */
    public function getModel($name = 'Agent', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true)) {
	$model = parent::getModel($name, $prefix, $config);

	return $model;
    }

    public function saveOrderAjax() {
	// Get the input
	$pks = $this->input->post->get('cid', array(), 'array');
	$order = $this->input->post->get('order', array(), 'array');

	// Sanitize the input
	JArrayHelper::toInteger($pks);
	JArrayHelper::toInteger($order);

	// Get the model
	$model = $this->getModel();

	// Save the ordering
	$return = $model->saveorder($pks, $order);

	if ($return) {
	    echo "1";
	}

	// Close the application
	JFactory::getApplication()->close();
    }
    
    // get email in table: #__re_agents
    function getEmailAgent($id = null){
        $results = '';
        if($id){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('email');
            $query->from('#__re_agents');
            $query->where('id = ' . $id);
            $db->setQuery($query);
            $results = $db->loadResult();
        }
        
        return $results;
    }

}
