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
 * JUX_Real_Estate Component - Currencies View
 * @package		JUX_Real_Estate
 * @subpackage	View
 */
class JUX_Real_EstateViewCurrencies extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display
     */
    function display($tpl = null) {
	$this->items = $this->get('Items');
	$this->state = $this->get('State');
	$this->pagination = $this->get('Pagination');
	$this->configs = JUX_Real_EstateFactory::getConfigs();
	
	JUX_Real_EstateHelper::addSubmenu('currencies');
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
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_CURRENCY_MANAGEMENT'));

	// create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_CURRENCY_MANAGEMENT'), 'currencies.png');

	if ($canDo->get('core.create')) {
	    JToolBarHelper::addNew('currency.add');
	}

	if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
	    JToolBarHelper::editList('currency.edit');
	}

	if ($canDo->get('core.edit.state')) {
	    JToolBarHelper::publishList('currencies.publish', 'JTOOLBAR_PUBLISH', true);
	    JToolBarHelper::unpublishList('currencies.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	    JToolBarHelper::divider();
	}

	if ($canDo->get('core.delete')) {
	    JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_CURRENCIES'), 'currencies.delete', 'JTOOLBAR_DELETE');
	    JToolBarHelper::divider();
	}
	if (JVERSION >= '3.0.0') {
	    JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=types');

	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
	    );
	}
    }

    protected function getSortFields() {
	return array(
	    'a.title' => JText::_('JGLOBAL_TITLE'),
	);
    }

}

?>