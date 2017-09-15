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
 * JUX_Real_Estate Component - Type view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewTypes extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {

	$this->items = $this->get('Items');
	$this->state = $this->get('State');
	$this->pagination = $this->get('Pagination');

	JUX_Real_EstateHelper::addSubmenu('types');

	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}
	
	$this->addToolBar();
	
	// Include the component HTML helpers.
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	$this->sidebar = JHtmlSidebar::render();
	parent::display($tpl);
    }

    function addToolBar() {
	$canDo = JUX_Real_EstateHelper::getActions();

	$user = JFactory::getUser();
	// Get the toolbar object instance
	$bar = JToolBar::getInstance('toolbar');

	JToolbarHelper::title(JText::_('COM_JUX_REAL_ESTATE_TYPE_MANAGEMENT'), 'types.png');
	if (count($user->getAuthorisedCategories('com_jux_real_estate', 'core.create')) >= 0) {
	    JToolbarHelper::addNew('type.add');
	}

	if (($canDo->get('core.edit'))) {
	    JToolbarHelper::editList('type.edit');
	}

	if ($canDo->get('core.edit.state')) {
	    if ($this->state->get('filter.published') != 2) {
		JToolbarHelper::publish('types.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('types.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	    }

	    if ($this->state->get('filter.published') != -1) {
		if ($this->state->get('filter.published') != 2) {
		    JToolbarHelper::archiveList('types.archive');
		} elseif ($this->state->get('filter.published') == 2) {
		    JToolbarHelper::unarchiveList('types.publish');
		}
	    }
	}

	if ($canDo->get('core.edit.state')) {
	    JToolbarHelper::checkin('types.checkin');
	}

	if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
	    JToolbarHelper::deleteList('', 'types.delete', 'JTOOLBAR_EMPTY_TRASH');
	} elseif ($canDo->get('core.edit.state')) {
	    JToolbarHelper::trash('types.trash');
	}

	JToolbarHelper::help('JHELP_COMPONENTS_TYPES');

	JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=types');

	JHtmlSidebar::addFilter(
		JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
	);

	JHtmlSidebar::addFilter(
		JText::_('JOPTION_SELECT_ACCESS'), 'filter_access', JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
	);

	JHtmlSidebar::addFilter(
		JText::_('JOPTION_SELECT_LANGUAGE'), 'filter_language', JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
	);
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
