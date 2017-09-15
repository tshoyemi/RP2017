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
 * JUX_Real_Estate Component - Fields view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewFields extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {

	$this->items = $this->get('Items');
	$this->state = $this->get('State');
	$this->pagination = $this->get('Pagination');
	JUX_Real_EstateHelper::addSubmenu('fields');
	
	// check for errors
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}
	
	$this->addToolBar();
	if (JVERSION >= '3.0.0') {
	    $this->sidebar = JHtmlSidebar::render();
	}

	if (JVERSION < '3.0.0') {
	    $this->setLayout($this->getLayout() . '25');
	}

	parent::display($tpl);
    }

    function addToolBar() {

	$canDo = JUX_Real_EstateHelper::getActions();

	// set page title
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_FIELDS_MANAGEMENT'));

	// create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_FIELDS_MANAGEMENT'), 'fields.png');

	if ($canDo->get('core.create')) {
	    JToolBarHelper::addNew('field.add');
	}

	if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
	    JToolBarHelper::editList('field.edit');
	}

	if ($canDo->get('core.edit.state')) {
	    JToolBarHelper::publishList('fields.publish', 'JTOOLBAR_PUBLISH', true);
	    JToolBarHelper::unpublishList('fields.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	    JToolBarHelper::custom('fields.search', 'default.png', '', 'COM_JUX_REAL_ESTATE_SEARCH', true);
	    JToolBarHelper::custom('fields.unsearch', 'default.png', '', 'COM_JUX_REAL_ESTATE_UNSEARCH', true);
	    JToolBarHelper::divider();
	}

	if ($canDo->get('core.delete')) {
	    JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_FIELDS'), 'fields.delete', 'JTOOLBAR_DELETE');
	    JToolBarHelper::divider();
	}

	JToolbarHelper::help('JHELP_COMPONENTS_TYPES');
	if (JVERSION >= '3.0.0') {
	    JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=fields');

	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
	    );
	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_LANGUAGE'), 'filter_language', JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
	    );
	}
    }

    protected function getSortFields() {
	return array(
	    'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
	    'a.published' => JText::_('JSTATUS'),
	    'a.title' => JText::_('JGLOBAL_TITLE'),
	    'a.access' => JText::_('JGRID_HEADING_ACCESS'),
	    'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
	    'a.id' => JText::_('JGRID_HEADING_ID')
	);
    }

}