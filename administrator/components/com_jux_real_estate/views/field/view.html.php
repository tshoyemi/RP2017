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
 * View to edit a field.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 * @since        1.6
 */
class JUX_Real_EstateViewField extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    /**
     * Display the view
     */
    function display($tpl = null) {
	// Initialise variables.
	$this->form = $this->get('Form');
	$this->item = $this->get('Item');
	$this->state = $this->get('State');

	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}

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
	$isNew = ($this->item->id == 0);
	$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
	$canDo = JUX_Real_EstateHelper::getActions();

	$title = $isNew ?
		JText::_('COM_JUX_REAL_ESTATE_FIELD_MANAGER_ADD') :
		JText::_('COM_JUX_REAL_ESTATE_FIELD_MANAGER_EDIT');
	$document = JFactory::getDocument();
	$document->setTitle($title);
	JToolBarHelper::title($title, 'addfield.png');

	// If not checked out, can save the item.
	if (!$checkedOut && ($canDo->get('core.edit') || $canDo->get('core.create'))) {
	    JToolBarHelper::apply('field.apply');
	    JToolBarHelper::save('field.save');
	}
	if (!$checkedOut && $canDo->get('core.create')) {

	    JToolBarHelper::save2new('field.save2new');
	}

	if (empty($this->item->id)) {
	    JToolBarHelper::cancel('field.cancel');
	} else {
	    JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CLOSE');
	}
    }

}