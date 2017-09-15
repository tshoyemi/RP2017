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

jimport('joomla.filesystem.file');

/**
 * FileHandler list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @since	1.6
 */
class JUX_Real_EstateControllerMass_Upload extends JControllerForm {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_FILE';
    protected $view_list = 'files';

    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JControllerLegacy
     * @since   12.2
     * @throws  Exception
     */
    public function __construct($config = array()) {
	parent::__construct($config);

	$this->registerTask('clean', 'cleannclose');
    }

    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowAdd($data = array()) {
	$user = JFactory::getUser();
	$allow = null;
	$allow = $user->authorise('core.create', 'com_jux_real_estate');
	if ($allow === null) {
	    return parent::allowAdd($data);
	} else {
	    return $allow;
	}
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowEdit($data = array(), $key = 'id') {
	$user = JFactory::getUser();
	$allow = null;
	$allow = $user->authorise('core.edit', 'com_jux_real_estate');
	if ($allow === null) {
	    return parent::allowEdit($data, $key);
	} else {
	    return $allow;
	}
    }

    /**
     * Override method to get folder
     * @param type $name
     * @param type $prefix
     * @param type $config
     */
    public function getModel($name = 'file', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true)) {
	return parent::getModel($name, $prefix, $config);
    }

    /**
     * Method to add a new record.
     *
     * @return  mixed  True if the record can be added, a error object if not.
     *
     * @since   12.2
     */
    public function mass_upload() {
	$app = JFactory::getApplication();
	$context = "$this->option.mass_upload.$this->context";

	// Access check.
	if (!$this->allowAdd()) {
	    // Set the internal error and also the redirect error.
	    $this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
	    $this->setMessage($this->getError(), 'error');

	    $this->setRedirect(
		    JRoute::_(
			    'index.php?option=' . $this->option . '&view=' . $this->view_list
			    . $this->getRedirectToListAppend(), false
		    )
	    );

	    return false;
	}

	// Clear the record edit information from the session.
	$app->setUserState($context . '.data', null);

	// Redirect to the edit screen.
	$this->setRedirect(
		JRoute::_(
			'index.php?option=' . $this->option . '&view=' . $this->view_item, false
		)
	);

	return true;
    }

    /**
     * Method to clean and close mass upload files.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     *
     */
    public function cleannclose() {
	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	$app = JFactory::getApplication();
	$context = "$this->option.mass_upload.$this->context";

	$app->setUserState($context . '.data', null);

	// TODO: clean database
	$model = $this->getModel();
	$model->cleanUploadFiles();

	$task = $this->getTask();

	$this->setRedirect(
		JRoute::_(
			'index.php?option=' . $this->option . '&view=' . ($task == 'clean' ? $this->view_item : $this->view_list)
			. $this->getRedirectToListAppend(), false
		)
	);

	return true;
    }

    /**
     * Function to handler upload files.
     * Call by AJAX.
     *
     */
    public function upload() {
	// Get the uploaded file information
	$files = $this->input->files->get('files', null, 'array');
	$files_title = $this->input->post->get('files-title', null, 'array');
	$files_realty = $this->input->post->get('realty_id', null);
	$responses = array();
	if (is_array($files)) {
	    foreach ($files as $index => $file) {
		if (empty($files_title) || !isset($files_title[$file['name']])) {
		    $file['title'] = substr($file['name'], 0, strrpos($file['name'], '.'));
		} else {
		    $file['title'] = $files_title[$file['name']];
		}

		$response = new stdClass();
		$response->title = $file['title'];
		$response->name = $file['name'];
		$response->size = $file['size'];

		// If there is uploaded file, process it
		if (is_array($file) && isset($file['name']) && !empty($file['name'])) {
		    if ($this->_uploadFile($file)) {
			$data['title'] = $file['title'];
			$data['file_name'] = $file['name'];
			$data['size'] = $file['size'];
			$data['ext'] = $file['ext'];
			$data['temp_upload'] = 1;
			$data['changelogs'] = '';
			$data['realty_id'] = $files_realty['realty_id'];
			// Attempt to save the data.
			$model = $this->getModel();
			if ($model->save($data)) {
			    $response->delete_url = JURI::base() . 'index.php?option=com_jux_real_estate&task=mass_upload.delete&id=' . $model->getState($model->getName() . '.id', '');
			} else {
			    if (count($errors = $model->get('Errors'))) {
				$response->error = implode("\n", $errors);
			    }
			}
		    } else {
			if ($file['error']) {
			    $response->error = $file['error'];
			}
		    }
		}
		$responses[] = $response;
	    }
	}

	$this->generate_response(array('files' => $responses));
	jexit();
    }

    /**
     * Function to get existed files.
     * Call by AJAX.
     */
    public function getFiles() {
	if (!$this->allowEdit()) {
	    $this->generate_response(array());

	    jexit();
	}

	$model = $this->getModel();
	$files = $model->getUploadFiles();

	$this->generate_response(array('files' => $files));
	jexit();
    }

    /**
     * Function to delete uploaded files.
     * Call by AJAX.
     */
    public function delete() {
	$id = $this->input->getInt('id', null);

	if (empty($id)) {
	    jexit();
	}

	$model = $this->getModel();
	$files = $model->delete($id);

	jexit();
    }

    /**
     * Works out an installation package from a HTTP upload
     *
     * @return package definition or false on failure
     */
    protected function _uploadFile(&$file) {
	// Make sure that file uploads are enabled in php
	if (!(bool) ini_get('file_uploads')) {
	    JError::raiseWarning('', JText::_('COM_JUX_REAL_ESTATE_MSG_UPLOAD_WARN'));
	    return false;
	}

	// Check if there was a problem uploading the file.
	if ($file['error']) {
	    return false;
	}

	if ($file['size'] < 1) {
	    $file['error'] = JText::_('COM_JUX_REAL_ESTATE_MSG_UPLOAD_WARNUPLOADERROR');
	    return false;
	}

	$params = JComponentHelper::getParams('com_jux_real_estate');

	// Check extensions:
	$file['ext'] = substr($file['name'], strrpos($file['name'], '.') + 1);
	$allowed_exts = explode(',', str_replace(' ', '', $params->get('file_allowed_ext')));

	$base_path = '/www/jux_real_estate/Ver1/media/jux_real_estate/files';
	$base_path = str_replace('{root}', JPATH_ROOT, $base_path);

	$file_dest = $base_path . '/' . $file['name'];
	$file_src = $file['tmp_name'];

	// Move uploaded file
	jimport('joomla.filesystem.file');
	$uploaded = JFile::upload($file_src, $file_dest);

	// Unpack the downloaded package file
	if ($uploaded) {
	    return true;
	}

	$file['error'] = JText::_('COM_JUX_REAL_ESTATE_MSG_UPLOAD_WARNUPLOADERROR');
	return false;
    }

    protected function generate_response($content) {
	echo json_encode($content);
    }

}

?>