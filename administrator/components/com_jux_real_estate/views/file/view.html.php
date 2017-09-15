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

/**
 * View class for upload a file.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @since		1.6
 */
class JUX_Real_EstateViewFile extends JViewLegacy {
	
	protected $form;
	protected $item;
	protected $state;
	protected $params;

	/**
	 * Display.
	 */
	public function display($tpl = null) {
		// Initialise variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->params	= JComponentHelper::getParams('com_jux_real_estate');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

		if (JVERSION < '3.0.0')
		{
			$this->setLayout($this->getLayout() . '25');
		}
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= JUX_Real_EstateHelper::getActions();

		JToolBarHelper::title($isNew ? JText::_('COM_JUX_REAL_ESTATE_MANAGER_FILE_NEW') : JText::_('COM_JUX_REAL_ESTATE_MANAGER_FILE_EDIT'), 'files.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||$canDo->get('core.create'))) {
			JToolBarHelper::apply('file.apply');
			JToolBarHelper::save('file.save');
		}	

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('file.cancel');
		} else {
			JToolBarHelper::cancel('file.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
