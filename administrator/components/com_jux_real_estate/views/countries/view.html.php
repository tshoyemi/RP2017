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
class JUX_Real_EstateViewCountries extends JViewLegacy {

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
	
	JUX_Real_EstateHelper::addSubmenu('countries');
	$this->addToolBar();
	
	// Include the component HTML helpers.
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

	if (JVERSION >= '3.0.0') {
	    $this->sidebar = JHtmlSidebar::render();
	}

	parent::display($tpl);
    }

    function addToolBar() {
	$canDo = JUX_Real_EstateHelper::getActions();

	// set page title
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_COUNTRIES_MANAGEMENT'));

	// create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_COUNTRIES_MANAGEMENT'), 'states.png');

	if ($canDo->get('core.create')) {
	    JToolBarHelper::addNew('country.add');
	}

	if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
	    JToolBarHelper::editList('country.edit');
	}

	if ($canDo->get('core.delete')) {
	    JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_STATES'), 'countries.delete', 'JTOOLBAR_DELETE');
	    JToolBarHelper::divider();
	}
	if ($canDo->get('core.edit.state')) {
	    JToolBarHelper::divider();
	    JToolBarHelper::publishList('countries.publish', 'JTOOLBAR_PUBLISH', true);
	    JToolBarHelper::unpublishList('countries.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
    }

    protected function getSortFields() {
	return array(
	    'a.name' => JText::_('JGLOBAL_NAME'),
	    'a.code' => JText::_('COM_JUX_REAL_ESTATE_CODE'),
	    'a.id' => JText::_('JGRID_HEADING_ID'),
	    'a.published' => JText::_('JSTATUS')
	);
    }

}