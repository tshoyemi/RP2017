<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * View to edit a type.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 * @since        3.0
 */
class JUX_Real_EstateViewCompany extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        // Initialiase variables.
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->state = $this->get('State');
        $editor = JFactory::getEditor();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->assignRef('editor', $editor);

        $this->addToolbar();
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
        $title = JText::_('COM_JUX_REAL_ESTATE_COMPANY_MANAGER_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT')));
        $document = JFactory::getDocument();
        $document->setTitle($title);
        JToolBarHelper::title($title, 'company.png');

        // For new records, check the create permission.
        if ($isNew) {
            JToolBarHelper::apply('company.apply');
            JToolBarHelper::save('company.save');
            JToolBarHelper::save2new('company.save2new');
            JToolBarHelper::cancel('company.cancel');
        } else {
            // Can't save the record if it's checked out.
            if (!$checkedOut) {
                // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
                if ($canDo->get('core.edit')) {
                    JToolBarHelper::apply('company.apply');
                    JToolBarHelper::save('company.save');

                    // We can save this record, but check the create permission to see if we can return to make a new one.
                    if ($canDo->get('core.create')) {
                        JToolBarHelper::save2new('company.save2new');
                    }
                }
            }

            // If checked out, we can still save
            if ($canDo->get('core.create')) {
                JToolBarHelper::save2copy('company.save2copy');
            }

            JToolBarHelper::cancel('company.cancel', 'JTOOLBAR_CLOSE');
        }
    }

}
