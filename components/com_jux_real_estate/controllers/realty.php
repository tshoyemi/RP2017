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
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package        Joomla.Site
 * @subpackage    com_jux_real_estate
 */
class JUX_Real_EstateControllerRealty extends JControllerForm {

    /**
     * @var    string  The prefix to use with controller realty.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_REALTY';

    /**
     * @since    1.6
     */
    protected $view_item = 'form';

    /**
     * The URL view list variable.
     *
     * @var    string
     * @since  11.1
     */
    protected $view_list = '';

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
	$allow = $user->authorise('core.create', 'com_jux_real_estate.realty');
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
	// Initialise variables.
	$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
	$user = JFactory::getUser();
	$userId = $user->get('id');
	$asset = 'com_jux_real_estate.form.' . $recordId;
	// Check general edit permission first.
	if ($user->authorise('core.edit', $asset)) {
	    return true;
	}


	// Fallback on edit.own.
	// First test if the permission is available.
	if ($user->authorise('core.edit.own', 'com_jux_real_estate')) {
	    // Now test the owner is the user.
	    $ownerId = (int) isset($data['user_id']) ? $data['user_id'] : 0;
	    if (empty($ownerId) && $recordId) {
		// Need to do a lookup from the model.
		$record = $this->getModel('form')->getItem($recordId);

		if (empty($record)) {
		    return false;
		}

		$ownerId = $record->user_id;
	    }

	    // If the owner matches 'me' then do the test.
	    if ($ownerId == $userId) {
		return true;
	    }
	}

	// Since there is no asset tracking, revert to the component permissions.
	return parent::allowEdit($data, $key);
    }

    protected function getReturnPage() {
	$return = JRequest::getVar('return', null, 'default', 'base64');

	if (empty($return) || !JUri::isInternal(base64_decode($return))) {
	    return JURI::base();
	} else {
	    return base64_decode($return);
	}
    }

    /**
     * Gets the URL arguments to append to a list redirect.
     *
     * @return  string  The arguments to append to the redirect URL.
     *
     * @since   11.1
     */
    protected function getRedirectToListAppend() {
	$tmpl = JRequest::getCmd('tmpl');
	$id = JRequest::getCmd('id');
	$append = '';

	// Setup redirect info.
	if ($tmpl) {
	    $append .= '&tmpl=' . $tmpl;
	}

	if ($id) {
	    $append .= '&id=' . $id;
	}

	return $append;
    }

    public function checkinRealty() {
	JRequest::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
	$model = $this->getModel('form');
	$recordId = JRequest::getInt('id');

	if ($model->checkin($recordId) === false) {
	    // Check-in failed, go back to the record and display a notice.
	    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
	    $this->setMessage($this->getError(), 'error');
	    $this->setRedirect(JRoute::_(
			    $this->getReturnPage())
	    );
	    return false;
	}

	$this->setRedirect(JRoute::_(
			'index.php?option=' . $this->option . '&view=' . $this->view_item
			. $this->getRedirectToItemAppend($recordId, 'id'), false)
	);
	return true;
    }

    /**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   11.1
     */
    function sendmessage() {
	$app = JFactory::getApplication();
        $id = JRequest::getVar('id');
	$menu = $app->getMenu()->getActive()->id;
	$db = JFactory::getDBO();
        $msg = JText::_('You have entered wrong captcha');
	$user = JFactory::getUser();
	$post = JRequest::get('post', JREQUEST_ALLOWHTML);
	$post['menu_itemid'] = $menu;
         if (isset($post['jform']['captcha'])) {
            if (isset($_SESSION["captcha_code"])) {
                
                if ( trim($post['jform']['captcha']) !=  trim($_SESSION["captcha_code"])) {
                    $url = JRoute::_('index.php?option=com_jux_real_estate&view=realty&id=' . (int)$id);
                    $app->redirect($url,$msg);
                } 
            }
        }
	$model = $this->getModel('message');
	$ret = $model->store($post);
 
	$model->sendEmails($post, $ret);
	$document = JFactory::getDocument();
	$script = "window.addEvent('domready',
			function() {
					parent.SqueezeBox.close();
					parent.window.location=parent.window.location;
			});";
	$document->addScriptDeclaration($script);
       
    }
    public function save($key = null, $urlVar = null) {
	// Check for request forgeries.
	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	$user = JFactory::getUser();
	// Initialise variables.
	$app = JFactory::getApplication();
	
	$configs = JUX_Real_EstateFactory::getConfiguration();

	$lang = JFactory::getLanguage();
	$model = $this->getModel('form');
	$table = $model->getTable();
	$data = JRequest::getVar('jform', array(), 'post', 'array');
	$checkin = property_exists($table, 'checked_out');

	$context = "$this->option.edit.$this->context";
	$task = $this->getTask();
     $app = JFactory::getApplication();
	// Determine the name of the primary key for the data.
       
	if (empty($key)) {
	    $key = $table->getKeyName();
	}

	// To avoid data collisions the urlVar may be different from the primary key.
	if (empty($urlVar)) {
	    $urlVar = $key;
	}

	$recordId = JRequest::getInt($urlVar);

	$data[$key] = $recordId;
	$form = $model->getForm($data, false);

	if (!$form) {
	    $app->enqueueMessage($model->getError(), 'error');

	    return false;
	}

	// Test whether the data is valid.
	$validData = $model->validate($form, $data);

	// Check for validation errors.
	if ($validData === false) {
	    // Get the validation messages.
	    $errors = $model->getErrors();

	    // Push up to three validation messages out to the user.
	    for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
		if ($errors[$i] instanceof Exception) {
		    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
		} else {
		    $app->enqueueMessage($errors[$i], 'warning');
		}
	    }

	    // Save the data in the session.
	    $app->setUserState($context . '.data', $data);

	    // Redirect back to the edit screen.
	    $this->setRedirect(
		    JRoute::_(
			    'index.php?option=' . $this->option . '&view=' . $this->view_item
			    . $this->getRedirectToItemAppend($recordId, $key), true
		    )
	    );

	    return false;
	}

	// Attempt to save the data.
	if (!$model->save($validData)) {
	    // Save the data in the session.
	    $app->setUserState($context . '.data', $validData);

	    // Redirect back to the edit screen.
	    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
	    $this->setMessage($this->getError(), 'error');

	    $this->setRedirect(
		    JRoute::_(
			    'index.php?option=' . $this->option . '&view=' . $this->view_item
			    . $this->getRedirectToItemAppend($recordId, $key), true
		    )
	    );

	    return false;
	}

	// Save succeeded, so check-in the record.
	if ($checkin && $model->checkin($validData[$key]) === false) {
	    // Save the data in the session.
	    $app->setUserState($context . '.data', $validData);

	    // Check-in failed, so go back to the record and display a notice.
	    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
	    $this->setMessage($this->getError(), 'error');

	    $this->setRedirect(
		    JRoute::_(
			    'index.php?option=' . $this->option . '&view=' . $this->view_item
			    . $this->getRedirectToItemAppend($recordId, $key), false
		    )
	    );

	    return false;
	}

	$this->setMessage(
		JText::_(
			($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS') ? $this->text_prefix : 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
		)
	);

	switch ($task) {
	    case 'apply':
               
		// Set the record data in the session.
		$recordId = $model->getState($this->context . '.id');
		$this->holdEditId($context, $recordId);
		$app->setUserState($context . '.data', null);
		$model->checkout($recordId);
		// Redirect back to the edit screen.
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=form' . $this->getRedirectToItemAppend($recordId, $key), true
			)
		);
		break;

	    case 'save':
		// Set the record data in the session.
		$recordId = $model->getState($this->context . '.id');
		$this->holdEditId($context, $recordId);
		$app->setUserState($context . '.data', null);
		$model->checkout($recordId);

		// Redirect to the list screen.
		if ($configs->get('auto_approve')) {
		    $this->setRedirect(
			    JRoute::_(
				    'index.php?option=' . $this->option . '&view=myrealty&tab=available', false
			    )
		    );
		} else {
		    $this->setRedirect(
			    JRoute::_(
				    'index.php?option=' . $this->option . '&view=myrealty&tab=pending', false
			    )
		    );
		}
		break;
	}

	// Invoke the postSave method to allow for the child class to access the model.
	$this->postSaveHook($model, $validData);
	$post = JRequest::get('post');

	if (!$data['id'] && isset($data['isagent'])) {
	    $model->updateCount();
	}

	// notification email when adding new property
	if ($configs->get('new_property_inform') && !$data['id']) {
	    // send notify email to admin
	    $email = $this->_sendEmailNewProperty($validData);
	    $from = (isset($post['jp_email'])) ? $post['jp_email'] : $user->email;
	    $fromname = (isset($post['jp_name'])) ? $post['jp_name'] : $user->name;
	    $recipient = $configs->get('notify_email', $from);
	    $subject = str_replace($email['search'], $email['replace'], $configs->get('email_new_property_inform_subject'));
	    $body = str_replace($email['search'], $email['replace'], $configs->get('email_new_property_inform_body'));

	    $mail = new JMail;
	    $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
	}
	if ($configs->get('new_property_confirmation') && !$data['id']) {
	    // send notify email to user
	    $email = $this->_sendEmailNewProperty($validData);
	    $from = $configs->get('store_email', $app->getCfg('mailfrom'));
	    $fromname = $configs->get('store_email_name', $app->getCfg('fromname'));
	    $recipient = (isset($post['jp_email'])) ? $post['jp_email'] : $user->email;
	    
	    $subject = str_replace($email['search'], $email['replace'], $configs->get('email_new_property_confirmation_subject'));
	    $body = str_replace($email['search'], $email['replace'], $configs->get('email_new_property_confirmation_body'));

	    $mail = new JMail;
	    $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
	}

	return true;
    }

    /**
     * Override Method to edit an existing record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key
     * (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if access level check and checkout passes, false otherwise.
     *
     * @since   11.1
     */
    public function getRedirectToItemAppend($recordId = null, $urlVar = 'id') {
	$tmpl = $this->input->get('tmpl');
	$append = '';

	// Setup redirect info.
	if ($tmpl) {
	    $append .= '&tmpl=' . $tmpl;
	}

	if ($recordId) {
	    $append .= '&' . $urlVar . '=' . $recordId;
	}

	return $append;
    }

    public function edit($key = null, $urlVar = null) {
	$result = parent::edit($key, $urlVar);

	return $result;
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param    string    $name    The model name. Optional.
     * @param    string    $prefix    The class prefix. Optional.
     * @param    array    $config    Configuration array for model. Optional.
     *
     * @return    object    The model.
     * @since    1.5
     */
    public function &getModel($name = '', $prefix = '', $config = array('ignore_request' => true)) {
	$model = parent::getModel($name, $prefix, $config);
	return $model;
    }

    /**
     * Function that allows child controller access to model data after the data has been saved.
     *
     * @param    JModel    $model        The data model object.
     * @param    array    $validData    The validated data.
     *
     * @return    void
     * @since    1.6
     */
    protected function postSaveHook(JModelLegacy $model, $validData = array()) {
	$model->saveRealtyAmid($validData);
    }

    /**
     * Publish the selected realties
     *
     */
    function sold() {
	$id = JRequest::getInt('id');
	$model = $this->getModel('form');
	$ret = $model->sold($id, 1);
	if ($ret) {
	    echo 'unsold';
	}
	exit();
    }

    /**
     * Unpublish the selected realties
     *
     */
    function unsold() {
	$id = JRequest::getInt('id');
	$model = $this->getModel('form');
	$ret = $model->sold($id, 0);
	if ($ret) {
	    echo 'sold';
	}
	exit();
    }

    /**
     * Publish the selected realties
     *
     */
    function publish() {
	$id = JRequest::getInt('id');
	$model = $this->getModel('form');
	$ret = $model->publish($id, 1);
	if ($ret) {
	    echo 'unpublish';
	}
	exit();
    }

    /**
     * Unpublish the selected realties
     *
     */
    function unpublish() {
	$id = JRequest::getInt('id');
	$model = $this->getModel('form');
	$ret = $model->publish($id, 0);
	if ($ret) {
	    echo 'publish';
	}
	exit();
    }

    /**
     * Approve the selected realties
     *
     */
    function approve() {
        $id = JRequest::getInt('id');
	$model = $this->getModel('form');
	$ret = $model->approve($id, 1);
	if ($ret) {
	    echo 'reject';
	}
	exit();
    }

    /**
     * Rejected the selected realties
     */
    function reject() {
	$id = JRequest::getInt('id');
	$model = $this->getModel('form');
	$ret = $model->approve($id, -1);
	if ($ret) {
	    echo 'approve';
	}
	exit();
    }

    function delete() {
	global $Itemid;

	$id = JRequest::getVar('id');

	$model = $this->getModel('form');
	$del = $model->delete($id);
	if ($del) {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_DELETE_REALTY_SUCCESSFULLY');
	    $link = "index.php?option=com_jux_real_estate&view=myrealty&layout=user&Itemid=$Itemid";
	    $link = JRoute::_($link);
	} else {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_ERROR_REMOVING_REALTY');
	    $link = "index.php?option=com_jux_real_estate&view=myrealty&layout=user&Itemid=$Itemid";
	    $link = JRoute::_($link);
	}
	$this->setRedirect($link, JText::_($msg));
    }

    function imageDelete() {
	global $Itemid;
	$id = JRequest::getVar('id');
	$imageid = JRequest::getVar('imageid');
	$model = $this->getModel('form');
	$del = $model->imageDelete($imageid);
	if ($del) {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_DELETE_IMAGE_SUCCESSFULLY');
	    $link = "index.php?option=com_jux_real_estate&view=form&id=$id&amp;Itemid=$Itemid#imageslide";
	    $link = JRoute::_($link);
	} else {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_ERROR_REMOVING_IMAGE');
	    $link = "index.php?option=com_jux_real_estate&view=form&id=$id&Itemid=$Itemid#imageslide";
	    $link = JRoute::_($link);
	}
	$this->setRedirect($link, JText::_($msg));
    }

    function _sendEmailNewProperty($item = array()) {
	$user = JFactory::getUser();
	$description = substr(strip_tags($item->description), 0, 300) . '...';
	$details = '<b>' . JText::_('COM_JUX_REAL_ESTATE_TITLE') . ':</b>&nbsp;&nbsp;' . $item->title . '<br />'
		. '<b>' . JText::_('COM_JUX_REAL_ESTATE_PRICE') . ':</b>&nbsp;&nbsp;' . $item->price . '<br />'
		. '<b>' . JText::_('COM_JUX_REAL_ESTATE_DESCRIPTION') . ':</b>&nbsp;&nbsp;' . $description . '<br />'
		. '<br/>';

	$link = '<a href="' . JURI::base() . '/index.php?option=com_jux_real_estate&view=realty&id=' . $item->id . '&Itemid=' . $_REQUEST['Itemid'] . '">'
		. JURI::base() . '/index.php?option=com_jux_real_estate&view=realty&id=' . $item->id . '&Itemid=' . $_REQUEST['Itemid'] . '</a>';
	$ret['search'] = array('{customer}', '{details}', '{link}');
	$ret['replace'] = array($user->name, $details, $link);
	return $ret;
    }

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
			$data['realty_id'] = $files_realty;
			// Attempt to save the data.
			$model = $this->getModel('File');
			if ($model->save($data)) {
			    $response->delete_url = JURI::base() . 'index.php?option=com_jux_real_estate&task=realty.delete&id=' . $model->getState($model->getName() . '.id', '');
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

    protected function _uploadFile(&$file) {
	$configs = JUX_Real_EstateFactory::getConfiguration();
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
	$allowed_exts = explode(',', str_replace(' ', '', $configs->image_exts));
	if (!in_array($file['ext'], $allowed_exts)) {
	    $file['error'] = JText::_('COM_JUX_REAL_ESTATE_MSG_UPLOAD_NOT_ALLOWED_EXTENSION_ERROR');
	    return false;
	}
	// Build the appropriate paths
	$realty_id = JRequest::getInt('realty_id');
	$base_path = JPATH_ROOT . '/' . 'media' . '/' . 'com_jux_real_estate' . '/' . 'realty_image' . '/' . $realty_id . '/';
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

    /**
     * Returns a Table object, always creating it
     *
     * @param	type	$type	The table type to instantiate
     * @param	string	$prefix	A prefix for the table class name. Optional.
     * @param	array	$config	Configuration array for model. Optional.
     *
     * @return	JTable	A database object
     * @since	1.6
     */
//    public function getTable($type = 'File', $prefix = 'JUX_Real_EstateTable', $config = array()) {
//        return JTable::getInstance($type, $prefix, $config);
//    }

    /**
     * Function to get existed files.
     * Call by AJAX.
     */
    public function getFiles() {
	$realty_id = JRequest::getInt('realty_id');

	$model = $this->getModel('File');
	$files = $model->getUploadFiles($realty_id);
	$this->generate_response(array('files' => $files));
	jexit();
    }

    /**
     * Function to delete uploaded files.
     * Call by AJAX.
     */
    public function deleteimage() {
	$id = JRequest::getInt('id');
	if (empty($id)) {
	    jexit();
	}

	$model = $this->getModel('File');
	$files = $model->deleteimage($id);
	jexit();
    }

    protected function generate_response($content) {
	echo json_encode($content);
    }

}
