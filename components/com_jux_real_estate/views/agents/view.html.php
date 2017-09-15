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
 * JUX_Real_Estate Component - Agent list
 * @package		JUX_Real_Estate
 * @subpackage	View
 * @since		1.0
 */
class JUX_Real_EstateViewAgents extends JViewLegacy {

    /**
     * Display     
     */
    protected $state = null;
    protected $item = null;
    protected $items = null;
    protected $pagination = null;
    protected $list_style;

    function display($tpl = null) {
	if (isset($_GET['list_style'])) {
	    $this->list_style = $_GET['list_style'];
	}
	$app = JFactory::getApplication();
	$document = JFactory::getDocument();
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

	$pagination = $this->get('Pagination');

	if ($configs->get('agent_show_featured', 1))
	    $featured = $this->get('Featured');
	$extra = array();
	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseWarning(500, implode("\n", $errors));
	    return false;
	}

	$params = $state->params;
	if ($menu) {
	    $params->def('page_heading', $params->def('page_title', $menu->title));
	} else {
	    $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JOOM_PROPERTY'));
	}

	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself

	if (!$params->get('page_title')) {
	    $params->set('page_title', $menu->name);
	} else {
	    $params->set('page_title', $params->get('page_title'));
	}
	$document->setTitle($params->get('page_title'));

	$extra = array();
	//$extra['permissions'] = $permissions;
	echo JUX_Real_EstateHTML::buildToolbar('agents', $extra);

	$agent_image_width = ( $configs->get('agent_image_width')) ? $configs->get('agent_image_width') : '90';
	$agents_folder = JURI::root(true) . '/images/joom_property/agents/';
	$thumb_width = ( $configs->get('thumbwidth') ) ? $configs->get('thumbwidth') . 'px' : '200px';
	$thumb_height = round((( $thumb_width ) / 1.5), 0) . 'px';
	$enable_featured = $configs->get('show_featured');

	$lists = array();
	$lists['filter_order'] = JUX_Real_EstateHTML::buildAgentSortList('filter_order', $state->get('list.ordering'), 'class="input-medium" onchange="document.adminForm.submit();" title="' . JText::_('COM_JUX_REAL_ESTATE_AGENT_SEARCH_DESC') . '"');
	$lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="input-medium" onchange="document.adminForm.submit();"');
	$this->lists = $lists;
	$this->permissions = $permissions;
	$this->items = $items;
	$this->params = $params;
	$this->featured = $featured;
	$this->total = $total;
	$this->state = $state;
	$this->pagination = $pagination;
	$this->configs = $configs;
	$this->agent_image_width = $agent_image_width;
	$this->agents_folder = $agents_folder;
	$this->thumb_width = $thumb_width;
	$this->thumb_heigh = $thumb_height;
	$this->enable_featured = $enable_featured;

	parent::display($tpl);
    }

}

?>