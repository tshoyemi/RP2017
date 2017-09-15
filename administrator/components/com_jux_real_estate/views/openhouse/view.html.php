<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class JUX_Real_EstateViewOpenhouse extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;
    protected $configs;

    public function display($tpl = null) {
        // Initialiase variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->configs = JUX_Real_EstateFactory::getConfigs();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);

        $isNew = ($this->item->id == 0);
        $canDo = JUX_Real_EstateHelper::getActions();

        $title = $isNew ? JText::_('COM_JUX_REAL_ESTATE_ADD_OPEN_HOUSE') : JText::_('COM_JUX_REAL_ESTATE_EDIT_OPEN_HOUSE');
        $document = JFactory::getDocument();
        $document->setTitle($title);
        JToolBarHelper::title($title, 'addopenhouse.png');

        // If not checked out, can save the item.
        if (($canDo->get('core.edit') || $canDo->get('core.create'))) {
            JToolBarHelper::apply('openhouse.apply');
            JToolBarHelper::save('openhouse.save');
        }
        if ($canDo->get('core.create')) {

            JToolBarHelper::save2new('openhouse.save2new');
        }

        if (empty($this->item->id)) {
            JToolBarHelper::cancel('openhouse.cancel');
        } else {
            JToolBarHelper::cancel('openhouse.cancel', 'JTOOLBAR_CLOSE');
        }
    }

}

?>