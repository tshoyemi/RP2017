<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */


defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JUX_Real_EstateViewImageuploader extends JViewLegacy
{
	public function display($tpl = null)
	{
		$app        = JFactory::getApplication();
        $option     = JRequest::getCmd('option');
		$document   = JFactory::getDocument();
        JHTML::_('behavior.mootools', true);

		if($this->getLayout() == 'uploadimage') {
			$this->_uploadimage($tpl);
			return;
		}

		//get vars
		$task 		= JRequest::getVar( 'task' );
		$search 	= $app->getUserStateFromRequest( $option.'.imageuploader.search', 'search', '', 'string' );
		$search 	= trim(JString::strtolower( $search ) );

		//set variables
        switch($task){
            case 'selectcompaniesimg':
                $folder = 'companies';
                $task 	= 'companiesimg';
                $redi	= 'selectcompaniesimg';
            break;

            case 'selectagentsimg':
                $folder = 'agents';
                $task 	= 'agentsimg';
                $redi	= 'selectagentsimg';
            break;

            case 'selectcategoriesimg':
                $folder = 'categories';
                $task 	= 'categoriesimg';
                $redi	= 'selectcategoriesimg';
            break;
		}
		JRequest::setVar( 'folder', $folder );

		// Do not allow cache
		JResponse::allowCache(false);

		//get images
		$images     = $this->get('images');
		$pagination    = $this->get( 'Pagination' );

		if (count($images) > 0 || $search) {
			$this->assignRef('images', 	$images);
			$this->assignRef('folder', 	$folder);
			$this->assignRef('task', 	$redi);
			$this->assignRef('search', 	$search);
			$this->assignRef('state', 	$this->get('state'));
			$this->assignRef('pagination', $pagination);
			parent::display($tpl);
		} else {
			//no images in the folder, redirect to uploadscreen and raise notice
			JError::raiseNotice('SOME_ERROR_CODE', JText::_( 'COM_JUX_REAL_ESTATE_NO_IMAGES_AVAILABLE' ));
			$this->setLayout('uploadimage');
			JRequest::setVar( 'task', $task );
			$this->_uploadimage($tpl);
			return;
		}
	}

	public function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_icon = $this->images[$index];
		} else {
			$this->_tmp_icon = new JObject;
		}
	}

	protected function _uploadimage($tpl = null)
	{
		//initialise variables
		$document	= JFactory::getDocument();
		$configs   = JUX_Real_EstateFactory::getConfigs();

		//get vars
		$task 		= JRequest::getVar( 'task' );

		//assign data to template
		$this->assignRef('task'      	, $task);
		$this->assignRef('configs'  	, $configs);

		parent::display($tpl);
	}
}
?>