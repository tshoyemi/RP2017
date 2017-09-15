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
 * JUX_Real_Estate Component - Agent realties
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewAgentrealties extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {

	$app = JFactory::getApplication();
	$document = JFactory::getDocument();
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	$configs = JUX_Real_EstateFactory::getConfiguration();
	$permissions = new JUX_Real_EstatePermission();
	$featured = '';
	// get page layout
	$layout = $this->getLayout();

	// Get the parameters of the active menu item
	$menu = $app->getMenu()->getActive();

	// Get some data from the model
	$state = $this->get('State');
	$items = $this->get('Items');
	$total = $this->get('Total');
	$agent = $this->get('Agent');

	$pagination = $this->get('Pagination');
	if ($configs->get('show_featured'))
	    $featured = $this->get('Featured');

	$extra = array();
	$extra['id'] = JRequest::getVar('id');
	$extra['permissions'] = $permissions;
	echo JUX_Real_EstateHTML::buildToolbar('agentrealties', $extra);

	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseWarning(500, implode("\n", $errors));
	    return false;
	}
	if (!$agent) {
	    JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_AGENTS_NOT_FOUND'));
	    return false;
	}

	$params = $state->params;

	$params->def('num_leading_realties', $configs->get('num_leading_realties'));
	$params->def('num_intro_realties', $configs->get('num_intro_realties'));
	$params->def('num_columns_realties', $configs->get('num_columns_realties', 2));

	if ($menu) {
	    $params->def('page_heading', $params->def('page_title', $menu->title));
	} else {
	    $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JOOM_PROPERTY'));
	}

	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself

	$agent_name = $this->get('Agent')->username;
	$title = '';
	if ($agent_name) {
	    $title = "$agent_name";
	} else {
	    if (!$params->get('page_title')) {
		$params->set('page_title', $menu->name);
	    } else {
		$params->set('page_title', $params->get('page_title'));
	    }
	    $title = $params->get('page_title');
	}
	$params->set('page_title', $title);

	$document->setTitle($title);

	$agent_image_width = ($configs->get('agent_image_width')) ? $configs->get('agent_image_width') : '90';
	$agents_folder = JURI::root(true) . '/images/joom_property/agents/';
	$thumb_width = ($configs->get('thumbwidth')) ? $configs->get('thumbwidth') . 'px' : '200px';
	$thumb_height = round((($thumb_width) / 1.5), 0) . 'px';
	$enable_featured = $configs->get('show_featured');
	$picfolder = JURI::root(true) . $configs->get('imgpath');
	$lists = array();
	$lists['filter_order'] = JUX_Real_EstateHTML::buildRealtySortList('filter_order', $state->get('list.ordering'), 'class="input-medium" onchange="document.adminForm.submit();"');
	$lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="input-medium" onchange="document.adminForm.submit();"');
	$lists['type_id'] = JUX_Real_EstateHelperQuery::getTypesList('type_id', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.type_id'), true);
	$lists['cat_id'] = JUX_Real_EstateHelperQuery::getCategoryList('cat_id', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.cat_id'), true);
	$this->permissions = $permissions;
	$this->items = $items;
	$this->agent = $agent;
	$this->params = $params;
	$this->featured = $featured;
	$this->total = $total;
	$this->state = $state;
	$this->pagination = $pagination;
	$this->configs = $configs;
	$this->lists = $lists;
	$this->agent_image_width = $agent_image_width;
	$this->agents_folder = $agents_folder;
	$this->thumb_width = $thumb_width;
	$this->thumb_height = $thumb_height;
	$this->enable_featured = $enable_featured;
	$this->picfolder = $picfolder;

	parent::display($tpl);
    }

}

?>