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
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
require_once JPATH_COMPONENT . '/models/fields/juxcountry.php';

/**
 * JUX_Real_Estate Component - Type view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewStates extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {

	$this->items = $this->get('Items');
	$this->state = $this->get('State');
	$this->pagination = $this->get('Pagination');
	
	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}
	
	JUX_Real_EstateHelper::addSubmenu('states');
	$this->addToolBar();
	
	// Include the component HTML helpers.
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

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
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_STATES_MANAGEMENT'));

	// create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_STATES_MANAGEMENT'), 'states.png');

	if ($canDo->get('core.create')) {
	    JToolBarHelper::addNew('state.add');
	}

	if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
	    JToolBarHelper::editList('state.edit');
	}

	if ($canDo->get('core.delete')) {
	    JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_STATES'), 'states.delete', 'JTOOLBAR_DELETE');
	    JToolBarHelper::divider();
	}
	JToolbarHelper::help('JHELP_COMPONENTS_TYPES');
	if (JVERSION >= '3.0.0') {
	    JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=types');

	    JHtmlSidebar::addFilter(
		    JText::_('COM_JUX_REAL_ESTATE_SELECT_COUNTRY'), 'filter_country_id', JHtml::_('select.options', JHtml::_('jux_real_estate.country', '', '', '', '', '', true), 'value', 'text', $this->state->get('filter.country_id'))
	    );
	}
    }

    protected function getSortFields() {
	return array(
	    'a.name' => JText::_('JGLOBAL_NAME'),
	    'a.country_name' => JText::_('COM_JUX_REAL_ESTATE_COUNTRY'),
	    'a.code' => JText::_('COM_JUX_REAL_ESTATE_CODE'),
	    'a.id' => JText::_('JGRID_HEADING_ID')
	);
    }

}