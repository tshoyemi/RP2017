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

/**
 * JUX_Real_Estate Component - Agents view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewAgents extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {

        $path_avatar = JUX_REAL_ESTATE_IMG . "?width=100&amp;height=100&amp;image=/";
        $path_no_avatar = JURI::base() . 'components/com_jux_real_estate/assets/img/';
        $this->items = $this->get('Items');

        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');

        $this->assignRef('path_avatar', $path_avatar);
        $this->assignRef('path_no_avatar', $path_no_avatar);

        JUX_Real_EstateHelper::addSubmenu('agents');

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

        if (JVERSION < '3.0.0') {
            $this->setLayout($this->getLayout() . '25');
        }

        parent::display($tpl);
    }

    function addToolbar() {

        $canDo = JUX_Real_EstateHelper::getActions();
        $user = JFactory::getUser();

        // set page title
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JUX_REAL_ESTATE_AGENT_MANAGEMENT'));

        // create the toolbar
        JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_AGENT_MANAGEMENT'), 'agents.png');

        if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_jux_real_estate', 'core.create')) >= 0) {
            JToolbarHelper::addNew('agent.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
            JToolBarHelper::editList('agent.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::custom('agents.featured', 'featured.png', 'featured_f2.png', JText::_('COM_JUX_REAL_ESTATE_FEATURE'), true);
            JToolBarHelper::custom('agents.unfeatured', 'remove.png', 'remove_f2.png', JText::_('COM_JUX_REAL_ESTATE_UNFEATURE'), true);
            JToolBarHelper::divider();
            JToolBarHelper::publishList('agents.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublishList('agents.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::custom('agents.approve', 'default.png', '', 'COM_JUX_REAL_ESTATE_APPROVE', true);
            JToolBarHelper::custom('agents.unapprove', 'default.png', '', 'COM_JUX_REAL_ESTATE_UNAPPROVE', true);
            JToolBarHelper::divider();
            JToolBarHelper::checkin('agents.checkin');
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            JToolbarHelper::deleteList('', 'agents.delete', 'JTOOLBAR_EMPTY_TRASH');
        } elseif ($canDo->get('core.edit.state')) {
            JToolbarHelper::trash('agents.trash');
        }

        JToolbarHelper::help('JHELP_JUX_REAL_ESTATE_AGENT_MANAGER');

        //sidebar		
        if (JVERSION >= '3.0.0') {
            JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=agents');

            JHtmlSidebar::addFilter(
                    JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
            );
        }
    }

    protected function getSortFields() {
        return array(
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.published' => JText::_('JSTATUS'),
            'a.featured' => JText::_('JFEATURED'),
            'a.id' => JText::_('JGRID_HEADING_ID')
        );
    }

}
