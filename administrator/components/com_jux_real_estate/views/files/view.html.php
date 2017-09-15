<?php

/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/models/fields/juxrealty.php';

/**
 * View class for a list of files.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @since		1.6
 */
class JUX_Real_EstateViewFiles extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display.
     */
    public function display($tpl = null) {
        if ($this->getLayout() !== 'modal') {
            JUX_Real_EstateHelper::addSubmenu(JRequest::getCmd('view', 'files'));
        }

        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        // We don't need toolbar in the modal window.
        if ($this->getLayout() !== 'modal') {
            $this->addToolbar();
            if (JVERSION >= '3.0.0') {
                $this->sidebar = JHtmlSidebar::render();
            }
        }

        if (JVERSION < '3.0.0') {
            $this->setLayout($this->getLayout() . '25');
        }

        parent::display($tpl);
    }

    protected function addToolBar() {
        $canDo = JUX_Real_EstateHelper::getActions();
        $user = JFactory::getUser();
        $document = &JFactory::getDocument();
        // create the toolbar
        $document->setTitle(JText::_('COM_JUX_REAL_ESTATE_FILES_MANAGEMENT'));
        JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_FILES_MANAGEMENT'), 'files.png');

        if ($canDo->get('core.create')) {
            // Get the toolbar object instance
            $bar = JToolBar::getInstance('toolbar');
            $title = JText::_('COM_JUX_REAL_ESTATE_FILES_MASS_UPLOAD');
            $dhtml = "<button onClick=\"Joomla.submitbutton('massupload.massupload')\" class=\"btn btn-small btn-danger\">
						<i class=\"icon-upload icon-white\" title=\"$title\"></i>
						$title</button>";
            $bar->appendButton('Custom', $dhtml, 'upload');
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::publish('files.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('files.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'files.delete');
            JToolBarHelper::divider();
            JToolBarHelper::checkin('files.checkin');
        }
//		if($canDo->get('core.admin')){
//			JToolBarHelper::preferences('com_jux_real_estate');
//		}

        JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=files');

        JHtmlSidebar::addFilter(
                JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
        );
        JHtmlSidebar::addFilter(
                JText::_('COM_JUX_REAL_ESTATE_SELECT_A_REALTY'), 'filter_realty_id', JHtml::_('select.options', JFormFieldJUXRealty::getOptions(), 'value', 'text', $this->state->get('filter.realty_id'))
        );
    }

}
