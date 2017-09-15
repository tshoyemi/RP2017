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
class JUX_Real_EstateViewAgentProfile extends JViewLegacy {

    protected $form;
    protected $params;
    protected $lists;
    protected $abc;

    /**
     * Display.
     */
    function display($tpl = null) {
	global $mainframe, $option;
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$post = JRequest::get('post');
	$config = JUX_Real_EstateFactory::getConfiguration();
	$form = $this->get('Form');

	$item = $this->get('Item');
	
	$agent = $this->get('Agent');
        
	$editor = JFactory::getEditor();
	$day = $this->get('Day');


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
	    $url = JUX_Real_EstateRoute::getURI();
	    $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK'));
	    return false;
	} elseif ($permission == 9) {
	    $itemIdPlan = JUX_Real_EstateHelperRoute::getAgentPlan();
	    $url = JUX_Real_EstateRoute::getURI();
	    $app->redirect(JRoute::_($itemIdPlan, false), JText::_('COM_JUX_REAL_ESTATE_PLEASE_SIGNUP_TO_PERFORM_THIS_TASK'));
	    return false;
	}
	// get the page/component configuration

	
	$params = $app->getParams();
       $agentplan = $this->get('Agentplan');
	$plan_id = JRequest::getVar('plan_id');

	$plan = JUX_Real_EstateHelperQuery::getAgentPlan($plan_id);
	
	foreach ($plan as $plan) {
	    if (!$plan->id) {
		$app->redirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate', false), JText::_('COM_JUX_REAL_ESTATE_PLAN_NOT_FOUND'));
		return;
	    }
	}
	if (is_object($menu) && isset($menu->query['option']) && $menu->query['option'] == 'com_jux_real_estate' && isset($menu->query['view']) && $menu->query['view'] == 'addagent') {
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
	$this->item = $item;
       
	$this->agent = $agent;
       
	$this->day = $day;


	parent::display($tpl);
    }

}
