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
 * JUX_Real_Estate Component - AgentPlant View
 * @package		JUX_Real_Estate
 * @subpackage	View
 */
class JUX_Real_EstateViewAgentplan extends JViewLegacy {

    /**
     * Display.
     */
    function display($tpl = null) {
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$document = JFactory::getDocument();
	// $tmpl = new JUX_Real_EstateTemplate();
	$config = JUX_Real_EstateFactory::getConfiguration();
	$menus = $app->getMenu();
	$menu = $menus->getActive();
	$layout = $this->getLayout();
	// Get the parameters of the active menu item

	$menu = $app->getMenu()->getActive();
	$form = $this->get('Form');
	// Get some data from the model
	$items = $this->get('Items');

	//$pagination = $this->get('Pagination');
	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseWarning(500, implode("\n", $errors));
	    return false;
	}

	if ($items === false) {
	    JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_AGENT_PLAN_NOT_FOUND'));
	    return false;
	}

	$this->items = $items;
	$this->config = $config;
	$this->form = $form;
	parent::display($tpl);
    }

}
