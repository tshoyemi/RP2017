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
 * JUX_Real_Estate Component - Messages view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewMessages extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {

	$this->items = $this->get('Items');
	$this->state = $this->get('State');
	$this->pagination = $this->get('Pagination');
	JUX_Real_EstateHelper::addSubmenu('messages');

	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}
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
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_MESSAGES_MANAGEMENT'));

	// create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_MESSAGES_MANAGEMENT'), 'messages.png');

	if ($canDo->get('core.delete')) {
	    JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_MESSAGES'), 'messages.delete', 'JTOOLBAR_DELETE');
	    JToolBarHelper::divider();
	}
	if (JVERSION >= '3.0.0') {
	    JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=types');

	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_status', JHtml::_('select.options', JUX_Real_EstateHelper::getStatusOptions(), 'value', 'text', $this->state->get('filter.status'), true)
	    );
	}
    }

    protected function getSortFields() {
	return array(
	    'm.name' => JText::_('COM_JUX_REAL_ESTATE_MESSAGE_FROM_USER'),
	    'm.email' => JText::_('COM_JUX_REAL_ESTATE_MESSAGE_EMAIL'),
	    'r.title' => JText::_('COM_JUX_REAL_ESTATE_REALTY_TITLE'),
	    'a.id' => JText::_('JGRID_HEADING_ID')
	);
    }

}