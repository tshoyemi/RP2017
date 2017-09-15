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
 * JUX_Real_Estate Component - Companies view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewCompanies extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $configs;

    function display($tpl = null) {

        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->configs = JUX_Real_EstateFactory::getConfigs();
        JUX_Real_EstateHelper::addSubmenu('companies');
        // Check for errors.
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
        $document->setTitle(JText::_('COM_JUX_REAL_ESTATE_COMPANIES_MANAGEMENT'));

        // create the toolbar
        JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_COMPANIES_MANAGEMENT'), 'company.png');

        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('company.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
            JToolBarHelper::editList('company.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::publish('companies.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('companies.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::custom('companies.featured', 'featured.png', 'featured_f2.png', JText::_('COM_JUX_REAL_ESTATE_FEATURE'), true);
            JToolBarHelper::custom('companies.unfeatured', 'remove.png', 'remove_f2.png', JText::_('COM_JUX_REAL_ESTATE_UNFEATURE'), true);
            JToolBarHelper::divider();
            JToolBarHelper::checkin('companies.checkin');
        }

        if ($canDo->get('core.delete')) {
            JToolBarHelper::divider();
            JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_TO_REMOVE_SELECTED_COMPANIES'), 'companies.delete', 'JTOOLBAR_DELETE');
        }


        JToolbarHelper::help('JHELP_COMPONENTS_TYPES');
        if (JVERSION >= '3.0.0') {
            JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=companies');

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
            'c.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'c.published' => JText::_('JSTATUS'),
            'c.name' => JText::_('COM_JUX_REAL_ESTATE_COMPANY_NAME'),
            'c.featured' => JText::_('JFEATURED'),
            'c.access' => JText::_('JGRID_HEADING_ACCESS'),
            'c.language' => JText::_('JGRID_HEADING_LANGUAGE'),
            'c.id' => JText::_('JGRID_HEADING_ID')
        );
    }

}