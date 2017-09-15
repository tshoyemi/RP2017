<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JUX_Real_EstateViewSubmitmore extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {

        $task = JRequest::getVar('task', 'agent');
        switch ($task) {
            case 'agent':
                $this->_displayForm($tpl);
                break;
            case 'confirm_submitmore':
                $this->_confirmSubmitmore($tpl);
                break;
            default:
                break;
        }
    }

    function _displayForm($tpl = null) {
        global $mainframe, $option;
        $app = JFactory::getApplication();
        $pathway = &$app->getPathway();
        $post = JRequest::get('post');
        $config = &JUX_Real_EstateFactory::getConfiguration();
        $form = $this->get('Form');
        // handle the metadata

        $menus = &JSite::getMenu();
        $menu = $menus->getActive();
        $document = JFactory::getDocument();

        // get page layout
        $layout = $this->getLayout();
        $tmpl = new JUX_Real_EstateTemplate();
        // check permission
        $user = JFactory::getUser();
        $permission = JUX_Real_EstatePermission::userIsAgent($user->get('id'));

        if ($permission == JUX_REAL_ESTATE_PERM_GUEST) {
            $url = JUX_Real_EstateRoute::getURI();
            $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK'));
            return false;
        } else if ($permission == JUX_REAL_ESTATE_PERM_FAIL) {
            $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_IS_NOT_AGENT'), 'error');
            return false;
        }
        // get the page/component configuration
        $params = $app->getParams();
        $plans = &$this->get('Plans');

        if (is_object($menu) && isset($menu->query['option']) && $menu->query['option'] == 'com_jux_real_estate' && isset($menu->query['view']) && $menu->query['view'] == 'addagent') {
            //$menu_params = new JParameter($menu->params);  		
            $params->set('page_title', $menu->title);
        } else {
            $params->set('page_title', JText::_('COM_JUX_REAL_ESTATE_ADD_AGENT'));
        }
        $document->setTitle($params->get('page_title'));

        $tmpl->set('params', $params);
        $tmpl->set('form', $form);
        $tmpl->set('post', $post);
        $tmpl->set('config', $config);
        $tmpl->set('plans', $plans);

        echo $tmpl->fetch('submitmore', $layout);
    }

    function _confirmSubmitmore($tpl = null) {
        $app = JFactory::getApplication();
        $pathway = &$app->getPathway();
        $post = JRequest::get('post');
        $config = &JUX_Real_EstateFactory::getConfiguration();
        // handle the metadata

        $menus = &JSite::getMenu();
        $menu = $menus->getActive();
        $document = JFactory::getDocument();

        // get page layout
        $layout = $this->getLayout();
        $tmpl = new JUX_Real_EstateTemplate();

        // get Data from model
        $state = &$this->get('State');
        $items = &$this->get('Items');
        $agent = &$this->get('Agent');
        // check permission
        $user = JFactory::getUser();
        $permission = JUX_Real_EstatePermission::userIsAgent($user->get('id'));
        if ($permission == JUX_REAL_ESTATE_PERM_GUEST) {
            $url = JUX_Real_EstateRoute::getURI();
            $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK'));
            return false;
        } else if ($permission == JUX_REAL_ESTATE_PERM_FAIL) {
            $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_IS_NOT_AGENT_SO_YOU_DONT_HAVE_PERMISSION_TO_PERFOMR_THIS_TASK'), 'error');
            return false;
        }

        // get the page/component configuration
        $params = $app->getParams();

        $agent_model = &JUX_Real_EstateFactory::getModel('addagent');
        $row = &$agent_model->getPlans($post['id']);
        if (!$row->id) {
            $app->redirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate', false), JText::_('COM_JUX_REAL_ESTATE_PLAN_NOT_FOUND'));
            return;
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
        $tmpl->set('params', $params);
        $tmpl->set('row', $row);
        $tmpl->set('config', $config);
        $tmpl->set('agent', $agent);
        $tmpl->set('post', $post);

        echo $tmpl->fetch('confirmsubmitmore', $layout);
    }

}

?>
