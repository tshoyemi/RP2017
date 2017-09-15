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

jimport('joomla.application.component.controllerform');

/**
 * @package     Joomla.Administrator
 * @subpackage  com_jux_real_estate
 * @since       1.6
 */
class JUX_Real_EstateControllerPlan extends JControllerForm {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_PLAN';

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
	$allow = $user->authorise('core.create', 'com_jux_real_estate.plan');
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
	// Initialise variables.
	$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

	// Check general edit permission first.
	if ($user->authorise('core.edit', 'com_jux_real_estate.plan.' . $recordId)) {
	    return true;
	}

	// Since there is no asset tracking, revert to the component permissions.
	return parent::allowEdit($data, $key);
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
    public function save($key = null, $urlVar = null) {
	// Check for request forgeries.
	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	// Initialise variables.
	$app = JFactory::getApplication();
	$lang = JFactory::getLanguage();
	$model = $this->getModel();
	$table = $model->getTable();
	$data = JRequest::getVar('jform', array(), 'post', 'array');
	$configs = JUX_Real_EstateFactory::getConfigs();
	$checkin = property_exists($table, 'checked_out');
	$context = "$this->option.edit.$this->context";
	$task = $this->getTask();

	// Determine the name of the primary key for the data.
	if (empty($key)) {
	    $key = $table->getKeyName();
	}

	// To avoid data collisions the urlVar may be different from the primary key.
	if (empty($urlVar)) {
	    $urlVar = $key;
	}

	$recordId = JRequest::getInt($urlVar);

	if (!$this->checkEditId($context, $recordId)) {
	    // Somehow the person just went to the form and tried to save it. We don't allow that.
	    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
	    $this->setMessage($this->getError(), 'error');

	    $this->setRedirect(
		    JRoute::_(
			    'index.php?option=' . $this->option . '&view=' . $this->view_list
			    . $this->getRedirectToListAppend(), false
		    )
	    );

	    return false;
	}

	// Populate the row id from the session.
	$data[$key] = $recordId;

	// Access check.
	if (!$this->allowSave($data, $key)) {
	    $this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
	    $this->setMessage($this->getError(), 'error');

	    $this->setRedirect(
		    JRoute::_(
			    'index.php?option=' . $this->option . '&view=' . $this->view_list
			    . $this->getRedirectToListAppend(), false
		    )
	    );

	    return false;
	}

	// Validate the posted data.
	// Sometimes the form needs some posted data, such as for plugins and modules.
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
			    . $this->getRedirectToItemAppend($recordId, $key), false
		    )
	    );

	    return false;
	}

	// upload images
	$err = false;
	$image_types = explode('|', $configs->get('image_exts'));
	$file = JRequest::getVar('imagefile', '', 'files', 'array');
	$file['ext'] = strtolower(end(explode('.', $file['name'])));
	jimport('joomla.filesystem.file');
	if (!empty($file['name'])) {
	    if (in_array($file['ext'], $image_types)) {
		$base_dir = JPATH_SITE . DS . 'images' . DS . 'jux_real_estate' . DS;
		if (JUX_Real_EstateClassImage::check($file, $configs->images_zise)) {
		    // sanitize the image filename
		    $filename = JUX_Real_EstateClassImage::sanitize($base_dir, $file['name']);
		    $filepath = $base_dir . $filename;
		    // upload the image
		    if (JFile::upload($file['tmp_name'], $filepath)) {
			// resize image
			$validData['image'] = $filename;
			//remove old image
			$old_image = $base_dir . $validData['old_image'];
			if (is_file($old_image))
			    @unlink($old_image);
		    } else {
			$err = true;
			$msg = JText::_('COM_JUX_REAL_ESTATE_UPLOAD_IMAGE_FAILED');
		    }
		} else {
		    $msg = JText::_('COM_JUX_REAL_ESTATE_IMAGE_SIZE_IS_TOO_BIG');
		    $err = true;
		}
	    } else {
		$err = true;
		$msg = JText::_('COM_JUX_REAL_ESTATE_INVALID_FILE_TYPE_PLEASE_UPLOAD_IMAGE_FILE_ONLY');
	    }
	}

	if (!$err) {

	    // delete image
	    if (JRequest::getBool('delete_image')) {
		$dir = JPATH_SITE . DS . 'images' . DS . 'jux_real_estate' . DS;
		if (JFile::exists($dir . $validData['old_image'])) {
		    JFile::delete($dir . $validData['old_image']);
		}
		$validData['image'] = '';
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
				. $this->getRedirectToItemAppend($recordId, $key), false
			)
		);

		return false;
	    }
	} else {
	    $this->setError($msg);
	    $this->setMessage($this->getError(), 'error');
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

	// Redirect the user and adjust session state based on the chosen task.
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
				'index.php?option=' . $this->option . '&view=' . $this->view_item
				. $this->getRedirectToItemAppend($recordId, $key), false
			)
		);
		break;

	    case 'save2new':
		// Clear the record id and data from the session.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		// Redirect back to the edit screen.
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item
				. $this->getRedirectToItemAppend(null, $key), false
			)
		);
		break;

	    default:
		// Clear the record id and data from the session.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		// Redirect to the list screen.
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_list
				. $this->getRedirectToListAppend(), false
			)
		);
		break;
	}

	// Invoke the postSave method to allow for the child class to access the model.
	$this->postSaveHook($model, $validData);

	return true;
    }

}