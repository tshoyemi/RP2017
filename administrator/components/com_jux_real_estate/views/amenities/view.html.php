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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/models/fields/juxamenitycat.php';

class JUX_Real_EstateViewAmenities extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $configs;

    public function display($tpl = null) {
        $user = JFactory::getUser();
	
        // Initialise variables.
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->configs = JUX_Real_EstateFactory::getConfigs();
	
        JUX_Real_EstateHelper::addSubmenu('amenities');
	
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $hstyle = 'div.pagination .limit{display: none !important;}';
        $this->document->addStyleDeclaration($hstyle);
        $this->addToolBar();
	
        if (JVERSION >= '3.0.0') {
            $this->sidebar = JHtmlSidebar::render();
        }

        if (JVERSION < '3.0.0') {
            $this->setLayout($this->getLayout() . '25');
        }

        parent::display($tpl);
    }

    protected function addToolbar() {
        $canDo = JUX_Real_EstateHelper::getActions();

        // set page title
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JUX_REAL_ESTATE_AMENITIES_MANAGEMENT'));

        // create the toolbar
        JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_AMENITIES_MANAGEMENT'), 'amenities.png');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::custom('amenities.saveCats', 'save.png', 'save_f2.png', 'JTOOLBAR_APPLY', false);
        }
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('amenity.add');
        }
        if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
            JToolBarHelper::editList('amenity.edit');
            JToolBarHelper::divider();
        }

        if ($canDo->get('core.delete')) {
            JToolBarHelper::deleteList(JText::_('COM_JUX_REAL_ESTATE_DO_YOU_WANT_REMOVE_SELECTED_AMENITIES'), 'amenities.delete', 'JTOOLBAR_DELETE');
            JToolBarHelper::divider();
        }

        JToolbarHelper::help('JHELP_COMPONENTS_TYPES');
        if (JVERSION >= '3.0.0') {
            JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=amenities');

            JHtmlSidebar::addFilter(
                    JText::_('COM_JUX_REAL_ESTATE_SELECT_CAT_AMENITIES'), 'filter_cat_id', JHtml::_('select.options', JFormFieldJUXAmenityCat::getOptions(TRUE), 'value', 'text', $this->state->get('filter.cat_id'))
            );
        }
    }

    protected function getSortFields() {
        return array(
            'a.title' => JText::_('JGLOBAL_TITLE'),
            'a.cat' => JText::_('COM_JUX_REAL_ESTATE_CATEGORIES'),
        );
    }

}

?>