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

jimport('joomla.application.component.controller');

/**
 * JUX_Real_Estate base controller
 * @package        JUX_Real_Estate
 * @subpackage    Controller
 * @since        3.0
 *
 */
class JUX_Real_EstateController extends JControllerLegacy {

    /**
     * @var        string    The default view.
     * @since    1.6
     */
    protected $default_view = 'dashboard';

    /**
     * Method to display a view.
     *
     * @param    boolean            If true, the view output will be cached
     * @param    array            An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return    JController        This object to support chaining.
     * @since    1.5
     */
    public function display($cachable = false, $urlparams = false) {
	require_once JPATH_COMPONENT . '/helpers/jux_real_estate.php';

	if (JVERSION >= '3.0.0') {
	    $view = $this->input->get('view', 'dashboard');
	    $layout = $this->input->get('layout', 'default');
	    $id = $this->input->getInt('id');
	}

	if (JVERSION < '3.0.0') {
	    $view = JRequest::getCmd('view', 'dashboard');
	    $layout = JRequest::getCmd('layout', 'default');
	    $id = JRequest::getInt('id');
	}

	// Check for edit form.
	if ($this->isIllegaRequest($view, $layout, $id)) {
	    // Somehow the person just went to the form - we don't allow that.
	    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
	    $this->setMessage($this->getError(), 'error');
	    $this->setRedirect(JRoute::_('index.php?option=com_jux_real_estate&view=dashboard', false));

	    return false;
	}

	parent::display();

	return $this;
    }

    /*
     * Function to check if someone trying to access edit form from direct url.
     *
     * @param	string	$view	View
     * @param	string	$layout	Layout.
     * @param	string	$id		id.
     *
     * @return	boolean			True if this's illegal request.
     */
    protected function isIllegaRequest($view, $layout, $id) {
	switch ($view) {
	    case 'type':
	    case 'field':
	    case 'message':
	    case 'plan':
	    case 'realty':
	    case 'agent':
		return $layout == 'edit' && !$this->checkEditId("com_jux_real_estate.edit.$view", $id);
	    default:
		return false;
	}
    }

}

?>