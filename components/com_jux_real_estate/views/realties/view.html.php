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
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewRealties extends JViewLegacy {

    /**
     * Display
     *
     */
    protected $state = null;
    protected $item = null;
    protected $items = null;
    protected $pagination = null;
    protected $params;

    public function display($tpl = null) {
	$app = JFactory::getApplication();
	$user = JFactory::getUser();
	$document = JFactory::getDocument();

	$state = $this->get('State');
	$params = $state->params;
       $checktime = $this->get('checkTime');
      

	$configs = JUX_Real_EstateFactory::getConfiguration();

	// Get some data from the model
	$items = $this->get('Items');
	// ID may come from the contact switcher
	$cat_id = $state->get('list.cat_id', '');
	$type_id = $state->get('list.type_id', '');

	$category_title = JUX_Real_EstateHTML::getCategoryTitle($cat_id);
	$type_title = JUX_Real_EstateHTML::getTypeTitle($type_id);

	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself
	if ($type_id && $cat_id) {
	    $title = "$type_title - $category_title";
	} elseif ($type_id) {
	    $title = $type_title;
	} elseif ($cat_id) {
	    $title = $category_title;
	} else {
	    if (!$params->get('page_title')) {
		$params->set('page_title', JText::_('COM_JUX_REAL_ESTATE_ALL_REALTY'));
	    } else {
		$params->set('page_title', $params->get('page_title'));
	    }
	    $title = $params->get('page_title');
	}

	$params->set('page_title', $title);
	$document->setTitle($title);

	$pagination = $this->get('Pagination');

	// Fillter list
	$lists = array();
	$lists['filter_order'] = JUX_Real_EstateHTML::buildRealtySortList('filter_order', $state->get('list.ordering'), 'class="input-medium" onchange="document.adminForm.submit();"');
	$lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="input-medium" onchange="document.adminForm.submit();"');
	$lists['type_id'] = JUX_Real_EstateHelperQuery::getTypesList('type_id', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.type_id'), true);
	$lists['cat_id'] = JUX_Real_EstateHelperQuery::getCategoryList('cat_id', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.cat_id'), true);

	$this->state = $state;
	$this->items = $items;
	$this->params = $params;
	$this->pagination = $pagination;
	$this->configs = $configs;
	$this->lists = $lists;
        $this->checktime=$checktime;
      
	parent::display($tpl);
    }

}

?>