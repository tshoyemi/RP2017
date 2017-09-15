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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * JUX_Real_Estate Component - Plans view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewPlans extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $configs;

    function display($tpl = null) {
	$path_image = JUX_REAL_ESTATE_IMG . "?width=100&amp;height=100&amp;image=/";
	$path_no_image = JURI::base() . 'components/com_jux_real_estate/assets/img/';

	$this->items = $this->get('Items');
	$this->state = $this->get('State');
	$this->pagination = $this->get('Pagination');
	$this->configs = JUX_Real_EstateFactory::getConfigs();

	$this->assignRef('path_image', $path_image);
	$this->assignRef('path_no_image', $path_no_image);

	JUX_Real_EstateHelper::addSubmenu('plans');
	
	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}

	$this->addToolbar();

	if (JVERSION >= '3.0.0') {
	    $this->sidebar = JHtmlSidebar::render();
	}

	if (JVERSION < '3.0.0') {
	    $this->setLayout($this->getLayout() . '25');
	}

	parent::display($tpl);
    }

    function addToolbar() {

	$canDo = JUX_Real_EstateHelper::getActions();

	// set page title
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_PLANS_MANAGEMENT'));

	// create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_PLANS_MANAGEMENT'), 'plans.png');

	if ($canDo->get('core.create')) {
	    JToolBarHelper::addNew('plan.add');
	}

	if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
	    JToolBarHelper::editList('plan.edit');
	}

	if ($canDo->get('core.edit.state')) {
	    JToolBarHelper::divider();
	    JToolBarHelper::custom('plans.featured', 'featured.png', 'featured_f2.png', JText::_('COM_JUX_REAL_ESTATE_FEATURE'), true);
	    JToolBarHelper::custom('plans.unfeatured', 'remove.png', 'remove_f2.png', JText::_('COM_JUX_REAL_ESTATE_UNFEATURE'), true);
	    JToolBarHelper::divider();
	    JToolBarHelper::publishList('plans.publish', 'JTOOLBAR_PUBLISH', true);
	    JToolBarHelper::unpublishList('plans.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	    JToolBarHelper::divider();
	    JToolBarHelper::checkin('plans.checkin');
	}

	if ($canDo->get('core.delete')) {
	    JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_PLANS'), 'plans.delete', 'JTOOLBAR_DELETE');
	    JToolBarHelper::divider();
	}

	JToolbarHelper::help('JHELP_COMPONENTS_TYPES');
	if (JVERSION >= '3.0.0') {
	    JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=plans');

	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
	    );
	}
    }

    protected function getSortFields() {
	return array(
	    'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
	    'a.published' => JText::_('JSTATUS'),
	    'a.name' => JText::_('COM_JUX_REAL_ESTATE_PLAN_NAME'),
	    'a.featured' => JText::_('JFEATURED'),
	    'a.id' => JText::_('JGRID_HEADING_ID')
	);
    }

}