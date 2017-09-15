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
 * JUX_Real_Estate Component - Agent view
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        1.0
 */
class JUX_Real_EstateViewAgent extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;
    protected $configs;

    function display($tpl = null) {
        // Initialiase variables.
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->state = $this->get('State');
        $this->configs = JUX_Real_EstateFactory::getConfigs();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $editor = JFactory::getEditor();
        $path_avatar = JUX_REAL_ESTATE_IMG . "?width=100&amp;height=100&amp;image=/";
        $path_no_avatar = JURI::base() . 'components/com_jux_real_estate/assets/img/';

        $this->addToolbar();

        $this->assignRef('editor', $editor);
        $this->assignRef('lists', $lists);
        $this->assignRef('path_avatar', $path_avatar);
        $this->assignRef('path_no_avatar', $path_no_avatar);
        if (JVERSION < '3.0.0') {
            $this->setLayout($this->getLayout() . '25');
        }
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since    1.6
     */
    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $isNew = ($this->item->id == 0);
        $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
        $canDo = JUX_Real_EstateHelper::getActions();

        // set page title
        $title = JText::_('COM_JUX_REAL_ESTATE_AGENT_MANAGER_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT')));
        $document = JFactory::getDocument();
        $document->setTitle($title);
        JToolBarHelper::title($title, 'addagent.png');

        // For new records, check the create permission.
        if ($isNew) {
            JToolBarHelper::apply('agent.apply');
            JToolBarHelper::save('agent.save');
            JToolBarHelper::save2new('agent.save2new');
            JToolBarHelper::cancel('agent.cancel');
        } else {
            // Can't save the record if it's checked out.
            if (!$checkedOut) {
                // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
                if ($canDo->get('core.edit')) {
                    JToolBarHelper::apply('agent.apply');
                    JToolBarHelper::save('agent.save');

                    // We can save this record, but check the create permission to see if we can return to make a new one.
                    if ($canDo->get('core.create')) {
                        JToolBarHelper::save2new('agent.save2new');
                    }
                }
            }

            JToolBarHelper::cancel('agent.cancel', 'JTOOLBAR_CLOSE');
        }
    }

}
