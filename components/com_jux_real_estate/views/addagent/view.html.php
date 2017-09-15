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
 * JUX_Real_Estate Component - Addagent View
 * @package		JUX_Real_Estate
 * @subpackage	View
 */
class JUX_Real_EstateViewAddAgent extends JViewLegacy {

    protected $form;
    protected $params;
    protected $lists;
    protected $abc;

    /**
     * Display.
     */
    function display($tpl = null) {
	$task = JRequest::getVar('task', 'agent');
	switch ($task) {
	    case 'agent':
		$this->_displayForm($tpl);
		break;
	    case 'confirm_agent':
		$this->_confirmAgent($tpl);
		break;
	    default:
		break;
	}
    }

    function _displayForm($tpl = null) {
	global $mainframe, $option;
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$post = JRequest::get('post');
	$config = JUX_Real_EstateFactory::getConfiguration();
	$form = $this->get('Form');
	$editor = JFactory::getEditor();
	// handle the metadata

	$menus = $app->getMenu();
	$menu = $menus->getActive();
	$document = JFactory::getDocument();

	// get page layout
	$layout = $this->getLayout();

	// check permission
	$user = JFactory::getUser();
	$permission = JUX_Real_EstatePermission::userCanAddagent($user->get('id'));
	if ($permission == JUX_REAL_ESTATE_PERM_GUEST) {
//	    $url = JUX_Real_EstateRoute::getURI();
//	    $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK'));
//	    return false;
	} else if ($permission == JUX_REAL_ESTATE_PERM_AGENT_ADDED) {
	    sleep(3);
	    $urlmyproperty = JUX_Real_EstateHelperRoute::getMyProperty();
	    $app->redirect(JRoute::_($urlmyproperty, false));
	    return false;
	} elseif($permission == JUX_REAL_ESTATE_PERM_AGENT_ADDED_EXTRA){
            
        }
	// get the page/component configuration
	$params = $app->getParams();

	$plan_id = JRequest::getVar('plan_id');

	$plan = JUX_Real_EstateHelperQuery::getAgentPlan($plan_id);
	foreach ($plan as $plan) {
	    if (!$plan->id) {
		$app->redirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate', false), JText::_('COM_JUX_REAL_ESTATE_PLAN_NOT_FOUND'));
		return;
	    }
	}
	if (is_object($menu) && isset($menu->query['option']) && $menu->query['option'] == 'com_jux_real_estate' && isset($menu->query['view']) && $menu->query['view'] == 'addagent') {
	    //$menu_params = new JParameter($menu->params);  		
	    $params->set('page_title', $menu->title);
	} else {
	    $params->set('page_title', JText::_('COM_JUX_REAL_ESTATE_ADD_AGENT'));
	}
        
	$document->setTitle($params->get('page_title'));
	$lists['agent_type'] = JHTML::_('select.booleanlist', 'agent_type', ' class="inputbox" ', JText::_('COM_JUX_REAL_ESTATE_PAID_AGENT'), JText::_('COM_JUX_REAL_ESTATE_FREE_AGENT'), 1);
	$this->params = $params;
	$this->lists = $lists;
	$this->form = $form;
	$this->config = $config;
	$this->post = $post;
	$this->user = $user;
	$this->editor = $editor;
	$this->plan = $plan;
	parent::display($tpl);
    }

    function _confirmAgent($tpl = null) {
	$form = $this->get('Form');
	$this->form = $form;

	$plan_id = JRequest::getVar('plan_id');
	$plan = JUX_Real_EstateHelperQuery::getAgentPlan($plan_id);
	foreach ($plan as $plan) {
	    if (!$plan->id) {
		$app->redirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate', false), JText::_('COM_JUX_REAL_ESTATE_PLAN_NOT_FOUND'));
		return;
	    }
	}
	$this->plan = $plan;
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$post = JRequest::get('post');
	$config = JUX_Real_EstateFactory::getConfiguration();
	// handle the metadata

	$menus = $app->getMenu();
	$menu = $menus->getActive();
	$document = JFactory::getDocument();

	// get page layout
	$layout = $this->getLayout();
	
	// check permission
	$user = JFactory::getUser();
//	$permission = JUX_Real_EstatePermission::userCanAddagent($user->get('id'));
//	if ($permission == JUX_REAL_ESTATE_PERM_GUEST) {
//	    $url = JUX_Real_EstateRoute::getURI();
//	    $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK'));
//	    return false;
//	} else if ($permission == JUX_REAL_ESTATE_PERM_AGENT_ADDED) {
//	    $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_HAVE_REGISTERED_AS_AN_AGENT_SO_YOU_DONT_HAVE_PERMISSION_TO_PERFOMR_THIS_TASK'), 'error');
//	    return false;
//	}

	// get the page/component configuration
	$params = $app->getParams();
	$plan_id = JRequest::getVar('plan_id');
	$agent_model = JUX_Real_EstateFactory::getModel('addagent');
	$rows = JUX_Real_EstateHelperQuery::getAgentPlan($plan_id);
	foreach ($rows as $row) {
	    if (!$row->id) {
		$app->redirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate', false), JText::_('COM_JUX_REAL_ESTATE_PLAN_NOT_FOUND'));
		return;
	    }
	}
	if ($menu) {
	    $params->def('page_heading', $params->def('page_title', $menu->title));
	} else {
	    $params->def('page_heading', $row->name);
	}
	if (is_object($menu) && isset($menu->query['view']) && $menu->query['view'] == 'realty' && isset($menu->query['id']) && $menu->query['id'] == $item->id) {
	    if (!$params->get('page_title')) {
		$params->set('page_title', $row->name);
	    }
	} else {
	    $params->set('page_title', $row->name);
	}

	$document->setTitle($params->get('page_title'));
	$pathway->addItem($row->name);
	$this->row = $row;
	$this->config = $config;
	$this->post = $post;
	parent::display($tpl);
    }

}
