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

jimport('joomla.application.component.view');

/**
 * JUX_Real_Estate Component - Message View
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewMessage extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {
	$app = JFactory::getApplication();
	$user = JFactory::getUser();
	$permission = JUX_Real_EstatePermission::userCanAddagent($user->get('id'));
	if ($permission == 9) {
	    $itemIdPlan = JUX_Real_EstateHelperRoute::getAgentPlan();
	    $url = JUX_Real_EstateRoute::getURI();
	    $app->redirect(JRoute::_($itemIdPlan, false), JText::_('COM_JUX_REAL_ESTATE_PLEASE_SIGNUP_TO_PERFORM_THIS_TASK'));
	    return false;
	}

	$task = JRequest::getVar('task', '');
	switch ($task) {
	    case 'display':
		$this->_displayMessage($tpl);
		break;
	    default:
		$this->_defaultMessage($tpl);
		break;
	}
    }

    function _defaultMessage($tpl = null) {

	$app = JFactory::getApplication();
	$user = JFactory::getUser();
	$db = JFactory::getDBO();
	$pathway = $app->getPathway();
	$document = JFactory::getDocument();
	$model = $this->getModel();
	$agent = $this->get('Agent');
	$permissions = new JUX_Real_EstatePermission();
	// get page layout
	$layout = $this->getLayout();

	// Get the parameters of the active menu item
	$menus = $app->getMenu();
	$menu = $menus->getActive();

	if ($user->get('id') == 0) {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK');
	    $uri = JRequest::getVar('REQUEST_URI', '', 'server', 'string');
	    $url = base64_encode($uri);
	    $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), $msg);
	}
	// Get some data from the model
	$messages = $this->get('Items');
	$state = $this->get('State');
	$params = $state->params;
	$config = JUX_Real_EstateFactory::getConfiguration();

	$isAgent = $permissions->getUagentId();

	// Default email
	if ($config->get('agent_send') && $isAgent) {
	    $subject = $config->get('email_agent_to_user_subject');
	    $body = $config->get('email_agent_to_user_body');
	} else if ($config->get('user_send') && !$isAgent) {
	    $subject = $config->get('email_user_to_agent_subject');
	    $body = $config->get('email_user_to_agent_body');
	} else {
	    $subject = '';
	    $body = '';
	}
	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself
	if (isset($menu->query['view']) && $menu->query['view'] == 'message' && JRequest::getVar('layout') == 'message') {
	    if (count($messages)) {
		$params->set('page_title', $messages[0]->subject);
	    }
	} elseif (is_object($menu) && isset($menu->query['view']) && $menu->query['view'] == 'message' && isset($menu->query['layout']) && $menu->query['layout'] == 'list') {

	    if (!$params->get('page_title')) {
		$params->set('page_title', Jtext::_('COM_JUX_REAL_ESTATE_YOUR_MESSAGES'));
	    }
	}

	if (JRequest::getVar('layout') == 'message') {
	    if (!count($messages)) {
		JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_MESSAGE_NOT_FOUND'));
		return;
	    }
	}

	$document->setTitle($params->get('page_title'));

	$pagination = $this->get('Pagination');

	$this->params = $params;
	$this->messages = $messages;
	$this->agent = $agent;
	$this->user = $user;
	$this->config = $config;
	$this->subject = $subject;
	$this->body = $body;
	$this->pagination = $pagination;
	// echo $tmpl->fetch('message = $layout);
	parent::display($tpl);
    }

    function _displayMessage($tpl = null) {
	// for payment
	//$tmpl = new JUX_Real_EstateTemplate();
	$layout = $this->getLayout();
	$type = JRequest::getCmd('type');
	$config = &JUX_Real_EstateFactory::getConfiguration();
	if ($type == 'cancel') {
	    $title = $config->get('page_cancel_title');
	    $content = $config->get('page_cancel_msg');
	} else if ($type == 'thankyou') {
	    $title = $config->get('page_thanks_title');
	    $content = $config->get('page_thanks_msg');
	} else {
	    $title = $config->get('page_paylater_thanks_title');
	    $content = $config->get('page_paylater_thanks_msg');
	}

	$this->title = $title;
	$this->content = $content;

	parent::display($tpl);
    }

    function sendmessage($msg) {
	$this->setLayout('sendmessage');
	parent::display();
    }

    function deletemessage($msg) {
	$this->setLayout('deletemessage');
	parent::display();
    }

}

?>