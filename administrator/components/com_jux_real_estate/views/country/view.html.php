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
 * View to edit a state.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 * @since        3.0
 */
class JUX_Real_EstateViewCountry extends JViewLegacy {

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
	$isNew = ($this->item->id == 0);
	// set page title
	$title = $isNew ? JText::_('COM_JUX_REAL_ESTATE_ADD_COUNTRY') : JText::_('COM_JUX_REAL_ESTATE_EDIT_COUNTRY');
	$document = JFactory::getDocument();
	$document->setTitle($title);
	JToolBarHelper::title($title, 'addstate.png');

	// For new records, check the create permission.
	if ($isNew) {
	    JToolBarHelper::apply('country.apply');
	    JToolBarHelper::save('country.save');
	    JToolBarHelper::save2new('country.save2new');
	    JToolBarHelper::cancel('country.cancel');
	} else {
	    JToolBarHelper::apply('country.apply');
	    JToolBarHelper::save('country.save');
	    JToolBarHelper::save2new('country.save2new');
	    JToolBarHelper::cancel('country.cancel', 'JTOOLBAR_CLOSE');
	}
    }

}
