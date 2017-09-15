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

/**
 * View to set up configuration.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @since		1.6
 */
class JUX_Real_EstateViewConfigs extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
	// Initialiase variables.
	$this->item = $this->get('Data');
	$this->form = $this->get('Form');
	$this->state = $this->get('State');

	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}

	$this->addToolBar();
	
	// Include the component HTML helpers.
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

	$this->sidebar = JHtmlSidebar::render();
	parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
	$document = JFactory::getDocument();
	// set page title
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_CONFIGURATION'));
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_CONFIGURATION'), 'config.png');

	$user = JFactory::getUser();
	$canDo = JUX_Real_EstateHelper::getActions();

	if ($canDo->get('core.admin')) {
	    JToolBarHelper::apply('configs.apply');
	    JToolBarHelper::save('configs.save');
	}

	JToolBarHelper::spacer();
	JToolBarHelper::back(JText::_('COM_JUX_REAL_ESTATE_BACK'), 'index.php?option=com_jux_real_estate');
    }

}
