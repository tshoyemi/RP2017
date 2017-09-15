<?php

/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

/**
 * FileHandler list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @since	1.6
 */
class JUX_Real_EstateControllerFile extends JControllerForm {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_FILE';

    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JControllerLegacy
     * @since   12.2
     * @throws  Exception
     */
//	public function __construct($config = array())
//	{
//		parent::__construct($config);
//
//		$this->registerTask('cancelupload', 'cancel');
//	}

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
        $allow = $user->authorise('core.create', 'com_jux_real_estate.file');
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
        if ($user->authorise('core.edit', 'com_jux_real_estate.file.' . $recordId)) {
            return true;
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }

    /**
     * Method to run batch operations.
     *
     * @param   object  $model  The model.
     *
     * @return  boolean   True if successful, false otherwise and internal error is set.
     *
     * @since   1.6
     */
    /* 	public function batch($model = null)
      {
      JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

      // Set the model
      $model = $this->getModel();

      // Preset the redirect
      $this->setRedirect(JRoute::_('index.php?option=com_jux_real_estate&view=files' . $this->getRedirectToListAppend(), false));

      return parent::batch($model);
      }
     */

    /**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   12.2
     */
    public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel();
        $table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
        //$checkin = property_exists($table, 'checked_out');
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

        $recordId = $this->input->getInt($urlVar);

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

        // Get the uploaded file information
        $file = JRequest::getVar('jform_file_upload', null, 'files', 'array');

        // If there is uploaded file, process it
        if (is_array($file) && isset($file['name']) && !empty($file['name'])) {
            $file = $this->_uploadFile($file);
            if ($file) {
                $data['file_name'] = $file['name'];
                $data['size'] = $file['size'];
                $data['ext'] = $file['ext'];
            } else {
                $this->setRedirect(
                        JRoute::_(
                                'index.php?option=' . $this->option . '&view=' . $this->view_item
                                . $this->getRedirectToItemAppend($recordId, $urlVar), false
                        )
                );

                return false;
            }
        }

        // Get change logs
        $data['changelogs'] = json_encode($this->input->post->get('changelogs', array(), 'array'));

        // The save2copy task needs to be handled slightly differently.
        if ($task == 'save2copy') {
            // Check-in the original row.
            if ($checkin && $model->checkin($data[$key]) === false) {
                // Check-in failed. Go back to the item and display a notice.
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
                $this->setMessage($this->getError(), 'error');

                $this->setRedirect(
                        JRoute::_(
                                'index.php?option=' . $this->option . '&view=' . $this->view_item
                                . $this->getRedirectToItemAppend($recordId, $urlVar), false
                        )
                );

                return false;
            }

            // Reset the ID and then treat the request as for Apply.
            $data[$key] = 0;
            $task = 'apply';
        }

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
                            . $this->getRedirectToItemAppend($recordId, $urlVar), false
                    )
            );

            return false;
        }

        if (!isset($validData['metadata']['tags'])) {
            $validData['metadata']['tags'] = null;
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
                            . $this->getRedirectToItemAppend($recordId, $urlVar), false
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
                            . $this->getRedirectToItemAppend($recordId, $urlVar), false
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
                                . $this->getRedirectToItemAppend($recordId, $urlVar), false
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
                                . $this->getRedirectToItemAppend(null, $urlVar), false
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

    /**
     * Works out an installation package from a HTTP upload
     *
     * @return package definition or false on failure
     */
    protected function _uploadFile(&$file) {
        // Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            JError::raiseWarning('', JText::_('COM_JUX_FILESELLER_MSG_UPLOAD_WARN'));
            return false;
        }

        // Check if there was a problem uploading the file.
        if ($file['error'] || $file['size'] < 1) {
            JError::raiseWarning('', JText::_('COM_JUX_FILESELLER_MSG_UPLOAD_WARNUPLOADERROR'));
            return false;
        }

        $params = JComponentHelper::getParams('com_jux_real_estate');

        jimport('joomla.filesystem.file');

        // Check extensions:
        $file['ext'] = strtolower(JFile::getExt($file['name']));
        $allowed_exts = explode(',', str_replace(' ', '', $params->get('file_allowed_ext', 'jpg, png, jpeg')));

        if (!in_array($file['ext'], $allowed_exts)) {
            JError::raiseWarning('', JText::_('COM_JUX_FILESELLER_MSG_UPLOAD_NOT_ALLOWED_EXTENSION_ERROR'));
            return false;
        }

        // Build the appropriate paths
        //	$base_path = $params->get('file_upload_path');
        $base_path = '/www/jux_real_estate/Ver1/media/jux_real_estate/files';
        $base_path = str_replace('{root}', JPATH_ROOT, $base_path);

        $file_dest = $base_path . '/' . $file['name'];
        $file_src = $file['tmp_name'];

        // Uploaded file
        $uploaded = JFile::upload($file_src, $file_dest);

        // Unpack the downloaded package file
        if ($uploaded) {
            return $file;
        }

        JError::raiseWarning('', JText::_('COM_JUX_REAL_ESTATE_MSG_UPLOAD_WARNUPLOADERROR'));
        return false;
    }

}

?>