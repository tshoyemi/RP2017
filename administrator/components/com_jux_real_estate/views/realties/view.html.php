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
require_once JPATH_COMPONENT . '/models/fields/juxlocstate.php';
require_once JPATH_COMPONENT . '/models/fields/juxagent.php';

/**
 * JUX_Real_Estate Component - Realties view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewRealties extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $configs;

    function display($tpl = null) {

	$this->items = $this->get('Items');
	$this->state = $this->get('State');
	$this->pagination = $this->get('Pagination');
	$this->configs = JUX_Real_EstateFactory::getConfigs();

	JUX_Real_EstateHelper::addSubmenu('realties');

	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}

	// Include the component HTML helpers.
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

	$this->addToolBar();

	if (JVERSION >= '3.0.0') {
	    $this->sidebar = JHtmlSidebar::render();
	}

	parent::display($tpl);
    }

    function addToolBar() {

	$canDo = JUX_Real_EstateHelper::getActions();

	// set page title
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_REALTY_MANAGEMENT'));

	// create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_REALTY_MANAGEMENT'), 'realty.png');

	if ($canDo->get('core.create')) {
	    JToolBarHelper::addNew('realty.add');
	}

	if ($canDo->get('core.edit')) {
	    JToolBarHelper::editList('realty.edit');
	}

	if ($canDo->get('core.edit.state')) {

	    JToolBarHelper::divider();
	    JToolBarHelper::custom('realties.featured', 'featured.png', 'featured_f2.png', JText::_('COM_JUX_REAL_ESTATE_FEATURE'), true);
	    JToolBarHelper::custom('realties.unfeatured', 'remove.png', 'remove_f2.png', JText::_('COM_JUX_REAL_ESTATE_UNFEATURE'), true);
	    JToolBarHelper::divider();
	    JToolBarHelper::publishList('realties.publish', 'JTOOLBAR_PUBLISH', true);
	    JToolBarHelper::unpublishList('realties.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	    JToolBarHelper::divider();
	    JToolBarHelper::custom('realties.approved', 'default.png', '', 'COM_JUX_REAL_ESTATE_APPROVE', true);
	    JToolBarHelper::custom('realties.pedding', 'default.png', '', 'COM_JUX_REAL_ESTATE_PEDDING', true);
	    JToolBarHelper::custom('realties.rejected', 'default.png', '', 'COM_JUX_REAL_ESTATE_REJECT', true);
	    JToolBarHelper::divider();
	    JToolBarHelper::checkin('realties.checkin');
	}

	if ($canDo->get('core.delete')) {
	    JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_REALTY'), 'realties.delete', 'JTOOLBAR_DELETE');
	    JToolBarHelper::divider();
	}
	if (JVERSION >= '3.0.0') {
	    JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=types');

	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
	    );

	    JHtmlSidebar::addFilter(
		    JText::_('COM_JUX_REAL_ESTATE_SELECT_FEATURED'), 'filter_featured', JHtml::_('select.options', JUX_Real_EstateHelper::getFeaturedOptions(), 'value', 'text', $this->state->get('filter.featured'), true)
	    );

	    JHtmlSidebar::addFilter(
		    JText::_('COM_JUX_REAL_ESTATE_SELECT_APPROVED'), 'filter_approved', JHtml::_('select.options', JUX_Real_EstateHelper::getApprovedOptions(), 'value', 'text', $this->state->get('filter.approved'))
	    );


	    JHtmlSidebar::addFilter(
		    JText::_('COM_JUX_REAL_ESTATE_SELECT_A_TYPE'), 'filter_type_id', JHtml::_('select.options', JHtml::_('jux_real_estate.type'), 'value', 'text', $this->state->get('filter.type_id'))
	    );

	    JHtmlSidebar::addFilter(
		    JText::_('COM_JUX_REAL_ESTATE_SELECT_A_CATEGORY'), 'filter_cat_id', JHtml::_('select.options', JHtml::_('jux_real_estate.category'), 'value', 'text', $this->state->get('filter.cat_id'))
	    );

	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_ACCESS'), 'filter_access', JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
	    );

	    JHtmlSidebar::addFilter(
		    JText::_('JOPTION_SELECT_LANGUAGE'), 'filter_language', JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
	    );
	}
    }

    protected function getSortFields() {
	return array(
	    'r.ordering' => JText::_('JGRID_HEADING_ORDERING'),
	    'r.published' => JText::_('JSTATUS'),
	    'r.title' => JText::_('JGLOBAL_TITLE'),
	    'r.featured' => JText::_('JFEATURED'),
	    'r.access' => JText::_('JGRID_HEADING_ACCESS'),
	    'r.language' => JText::_('JGRID_HEADING_LANGUAGE'),
	    'r.id' => JText::_('JGRID_HEADING_ID')
	);
    }

}