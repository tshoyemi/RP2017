<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * JUX_Real_Estate Component - Openhouses view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewOpenhouses extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {

        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        JUX_Real_EstateHelper::addSubmenu('openhouses');
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

    function addToolBar() {

        $canDo = JUX_Real_EstateHelper::getActions();

        // set page title
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JUX_REAL_ESTATE_OPENHOUSES_MANAGEMENT'));

        // create the toolbar
        JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_OPENHOUSES_MANAGEMENT'), 'openhouses.png');

        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('openhouse.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
            JToolBarHelper::editList('openhouse.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::publishList('openhouses.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublishList('openhouses.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::checkin('openhouses.checkin');
        }

        if ($canDo->get('core.delete')) {
            JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_OPENHOUSES'), 'openhouses.delete', 'JTOOLBAR_DELETE');
            JToolBarHelper::divider();
        }
        JToolbarHelper::help('JHELP_COMPONENTS_TYPES');
        if (JVERSION >= '3.0.0') {
            JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=types');

            JHtmlSidebar::addFilter(
                    JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
            );
            JHtmlSidebar::addFilter(
                    JText::_('JOPTION_SELECT_ACCESS'), 'filter_access', JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
            );
        }
    }

    protected function getSortFields() {
        return array(
            'a.published' => JText::_('JSTATUS'),
            'a.title' => JText::_('JGLOBAL_TITLE'),
            'a.id' => JText::_('JGRID_HEADING_ID')
        );
    }

}