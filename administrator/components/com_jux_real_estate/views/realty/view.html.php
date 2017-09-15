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
 * View to edit a realty.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 * @since        1.6
 */
class JUX_Real_EstateViewRealty extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;
    protected $configs;
    protected $gallery;

    /**
     * Display the view
     */
    public function display($tpl = null) {
	// Initialiase variables.
	$this->item = $this->get('Item');
	$this->form = $this->get('Form');
	$this->state = $this->get('State');
	$this->gallery = $this->get('Images');
	$this->configs = JUX_Real_EstateFactory::getConfigs();
	$editor = JFactory::getEditor();
	
	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}

	$this->addToolbar();

	$this->assignRef('editor', $editor);
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
	$title = JText::_('COM_JUX_REAL_ESTATE_REALTY_MANAGER_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT')));
	$document = JFactory::getDocument();
	$document->setTitle($title);
	JToolBarHelper::title($title, 'realty.png');

	// For new records, check the create permission.
	if ($isNew) {
	    JToolBarHelper::apply('realty.apply');
	    JToolBarHelper::save('realty.save');
	    JToolBarHelper::save2new('realty.save2new');
	    JToolBarHelper::cancel('realty.cancel');
	} else {
	    // Can't save the record if it's checked out.
	    if (!$checkedOut) {
		// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
		if ($canDo->get('core.edit')) {
		    JToolBarHelper::apply('realty.apply');
		    JToolBarHelper::save('realty.save');

		    // We can save this record, but check the create permission to see if we can return to make a new one.
		    if ($canDo->get('core.create')) {
			JToolBarHelper::save2new('realty.save2new');
		    }
		}
	    }

	    // If checked out, we can still save
	    if ($canDo->get('core.create')) {
		JToolBarHelper::save2copy('realty.save2copy');
	    }

	    JToolBarHelper::cancel('realty.cancel', 'JTOOLBAR_CLOSE');
	}
    }

}
