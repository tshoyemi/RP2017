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

class JUX_Real_EstateViewAmenity extends JViewLegacy {

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
	if (JVERSION < '3.0.0') {
	    $this->setLayout($this->getLayout() . '25');
	}
	parent::display($tpl);
    }

    protected function addToolbar() {
	JRequest::setVar('hidemainmenu', true);

	$user = JFactory::getUser();
	$isNew = ($this->item->id == 0);
	$canDo = JUX_Real_EstateHelper::getActions();

	$title = $isNew ?
		JText::_('COM_JUX_REAL_ESTATE_AMENITY_MANAGER_ADD') :
		JText::_('COM_JUX_REAL_ESTATE_AMENITY_MANAGER_EDIT');
	$document = JFactory::getDocument();
	$document->setTitle($title);
	JToolBarHelper::title($title, 'addamenity.png');

	if (($canDo->get('core.edit') || $canDo->get('core.create'))) {
	    JToolBarHelper::apply('amenity.apply');
	    JToolBarHelper::save('amenity.save');
	}
	if ($canDo->get('core.create')) {

	    JToolBarHelper::save2new('amenity.save2new');
	}

	if (empty($this->item->id)) {
	    JToolBarHelper::cancel('amenity.cancel');
	} else {
	    JToolBarHelper::cancel('amenity.cancel');
	}
    }

}

?>