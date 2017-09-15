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
 * JUX_Real_Estate Component - List View
 * @package		JUX_Real_Estate
 * @subpackage	View
 * @since		3.0
 */
class JUX_Real_EstateViewMyrealty extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {
	$app = JFactory::getApplication();
	$user = JFactory::getUser();
	$pathway = $app->getPathway();
	$document = JFactory::getDocument();

	// get page layout
	$layout = $this->getLayout();

	// Selected Request vars
	// ID may come from the contact switcher
	$catid = JRequest::getInt('cat_id', 0);
	$typeid = JRequest::getInt('typeid', 0);

	$tab = JRequest::getVar('tab', '');
	$model = $this->getModel();

	// Get the parameters of the active menu item
	$menus = $app->getMenu();
	$menu = $menus->getActive();
	// check user log in

	$user = JFactory::getUser();
	$permission = JUX_Real_EstatePermission::userCanAddagent($user->get('id'));
	if ($user->get('id') == 0) {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK');
	    $uri = JRequest::getVar('REQUEST_URI', '', 'server', 'string');
	    $url = base64_encode($uri);
	    $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), $msg);
	} elseif ($permission == 9) {
	    $itemIdPlan = JUX_Real_EstateHelperRoute::getAgentPlan();
	    $url = JUX_Real_EstateRoute::getURI();
	    $app->redirect(JRoute::_($itemIdPlan, false), JText::_('COM_JUX_REAL_ESTATE_PLEASE_SIGNUP_TO_PERFORM_THIS_TASK'));
	    return false;
	}

	// Get some data from the model
	$state = $this->get('State');
	$items = $this->get('Items');

	$total = $this->get('Total');
	$category = $this->get('Category');
	$type = $this->get('Type');
	$agent = $this->get('Agent');
	$isOwner = $this->get('Owner');
	$img_src = JURI::base() . 'components/com_jux_real_estate/templates/default/images/';
	// Get the page/component configuration
	$params = $state->params;

	//Get Configuration model
	$model = JModelLegacy::getInstance('configuration', 'JUX_Real_EstateModel'); //& $this->getModel('configuration');
	$config = $model->getData();


	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself
	if ($menu) {
	    $params->def('page_heading', $params->def('page_title', $menu->title));
	} else {
	    $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JOOM_PROPERTY'));
	}
	$title = '';
	if ($typeid && $catid) {
	    $title = "$category - $type";
	} elseif ($typeid) {
	    $title = $type;
	} elseif ($catid) {
	    $title = $category;
	}
	if (!$params->get('page_title')) {
	    if ($title != '') {
		$params->set('page_title', $title);
	    } else {
		$params->set('page_title', $menu->name);
	    }
	} else {
	    $params->set('page_title', $params->get('page_title'));
	}
	$document->setTitle($params->get('page_title'));

	//set breadcrumbs
	if (is_object($menu) && isset($menu->query['view']) && $menu->query['view'] == 'myrealty' && !isset($menu->query['layout'])) {
	    if (isset($menu->query['typeid']) && $menu->query['typeid'] != $typeid) {
		if ($typeid && $catid) {
		    $link = "index.php?option=com_jux_real_estate&view=myrealty&typeid=$typeid&catid=0";
		    $pathway->addItem($type, $link);
		    $pathway->addItem($category);
		} elseif ($typeid) {
		    $pathway->addItem($type);
		} elseif ($catid) {
		    $pathway->addItem($category);
		}
	    } elseif (isset($menu->query['cat_id']) && $menu->query['cat_id'] != $catid) {
		if ($catid) {
		    $pathway->addItem($category);
		}
	    } else {
		
	    }
	} else {
	    if ($typeid && $catid) {
		$link = "index.php?option=com_jux_real_estate&view=myrealty&typeid=$typeid&catid=0";
		$pathway->addItem($type, $link);
		$pathway->addItem($category);
	    } elseif ($typeid) {
		$pathway->addItem($type);
	    } elseif ($catid) {
		$pathway->addItem($category);
	    }
	}
	$pagination = $this->get('Pagination');
	$this->total = $total;
	$this->items = $items;
	$this->params = $params;

	$this->pagination = $pagination;
	$this->config = $config;
        $this->configs = JUX_Real_EstateFactory::getConfiguration();
	$this->agent = $agent;
	$this->isOwner = $isOwner;
	$this->user = $user;
	$this->catid = $catid;
	$this->typeid = $typeid;
	$this->tab = $tab;
	$this->img_src = $img_src;
	parent::display($tpl);
    }

}