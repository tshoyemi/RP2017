<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');

class JUX_Real_EstateControllerImageuploader extends JControllerForm
{
	function __construct()
	{
		parent::__construct();

		// Register Extra task
		$this->registerTask( 'companiesimgup', 	'uploadimage' );
        $this->registerTask( 'agentsimgup', 	'uploadimage' );
        $this->registerTask( 'categoriesimgup', 	'uploadimage' );
	}

	public function uploadimage()
	{        
		JRequest::checkToken() or die( 'Invalid Token' );
        
		$app        = JFactory::getApplication();
        $configs 	= 	JUX_Real_EstateFactory::getConfigs();
		$file 		= JRequest::getVar( 'userfile', '', 'files', 'array' );
		$task 		= JRequest::getVar( 'task' );       

		//set the target directory
        switch($task){
            case 'companiesimgup':
                $imgwidth = $configs->get('company_image_width');
                $base_Dir = JPATH_SITE.DS.'images'.DS.'jux_real_estate'.DS.'companies'.DS;
            break;

            case 'agentsimgup':
                $imgwidth = $configs->get('agent_image_width');
                $base_Dir = JPATH_SITE.DS.'images'.DS.'jux_real_estate'.DS.'agents'.DS;
            break;

            case 'categoriesimgup':
                $imgwidth = $configs->get('cat_image_width');
                $base_Dir = JPATH_SITE.DS.'images'.DS.'jux_real_estate'.DS.'categories'.DS;
            break;
        }

		//do we have an upload?
		if (empty($file['name'])) {
			echo "<script> alert('".JText::_( 'COM_JUX_REAL_ESTATE_IMAGE_EMPTY' )."'); window.history.go(-1); </script>\n";
			$app->close();
		}

		//check the image
		if (JUX_Real_EstateClassImage::check($file, $configs->images_zise) === false) {
            echo "<script> alert('".htmlspecialchars(JText::_( 'COM_JUX_REAL_ESTATE_CANNOT_CHECK_ICON' ))."'); window.history.go(-1); </script>\n";
			$app->redirect($_SERVER['HTTP_REFERER']);
		}

		//sanitize the image filename
		$filename = JUX_Real_EstateClassImage::sanitize($base_Dir, $file['name']);
		$filepath = $base_Dir.$filename;

        if(!JUX_Real_EstateClassImage::resize($file['tmp_name'], $filepath, $imgwidth, 9999)){
        //if (!JFile::upload($file['tmp_name'], $filepath) ) {
            echo "<script> alert('".htmlspecialchars(JText::_( 'COM_JUX_REAL_ESTATE_UPLOAD_FAILED' ))."'); window.history.go(-1); </script>\n";
			$app->close();
        }else{
			echo "<script>window.history.go(-1); window.parent.jp_SwitchImage('$filename');</script>\n";
			$app->close();
        }
	}

	public function delete()
	{
		$app  = JFactory::getApplication();
        $option     = JRequest::getCmd('option');
        
		$images	= JRequest::getVar( 'rm', array(), '', 'array' );
		$folder = JRequest::getVar( 'folder');

		$successful = 0;
        if (count($images)) {
			foreach ($images as $image)
			{
				if ($image !== JFilterInput::clean($image, 'path')) {
					JError::raiseWarning(100, JText::_( 'COM_JUX_REAL_ESTATE_UNABLE_TO_DELETE' ).' '.htmlspecialchars($image, ENT_COMPAT, 'UTF-8'));
					continue;
				}elseif($image == 'noimage.png'){
                    JError::raiseWarning(100, JText::_('COM_JUX_REAL_ESTATE_CANNOT_DELETE_DEFAULT_IMG').' '.htmlspecialchars($image, ENT_COMPAT, 'UTF-8'));
					continue;
                }

				$fullPath = JPath::clean(JPATH_SITE.DS.'images'.DS.'jux_real_estate'.DS.$folder.DS.$image);
				if (is_file($fullPath)) {
					if(JFile::delete($fullPath)) $successful++;
				}
			}
		}

        switch($folder){
            case 'companies':
                $task = 'selectcompaniesimg';
            break;

            case 'agents':
                $task = 'selectagentsimg';
            break;

            case 'categories':
                $task = 'selectcategoriesimg';
            break;
		}
        
        if($successful > 0){
            $this->setMessage(JText::plural('COM_JUX_REAL_ESTATE_N_ITEMS_DELETED', $successful));
        }
        $this->setRedirect('index.php?option=com_jux_real_estate&view=imageuploader&task='.$task.'&tmpl=component');
	}
}
?>