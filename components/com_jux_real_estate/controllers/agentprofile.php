<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

/**
 * JUX_Real_Estate Component - Agent Controller
 * @package        JUX_Real_Estate
 * @subpackage    Controller
 */
class JUX_Real_EstateControllerAgentprofile extends JControllerForm {

    public function getModel($name = 'agentprofile', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, array('ignore_request' => false));
    }

    public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $user = JFactory::getUser();
        // Initialise variables.
        $app = JFactory::getApplication();
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $lang = JFactory::getLanguage();
        $model = $this->getModel('agentprofile');
        $table = $model->getTable();
        $data = JRequest::getVar('jform', array(), 'post', 'array');
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
        $data[$key] = $_POST['agent_id'];
        $form = $model->getForm($data, false);
        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        // Test whether the data is valid.
        $validData = $model->validate($form, $data);

        // Check for validation errors.
        // Save the data in the session.
        $app->setUserState($context . '.data', $data);

        // Redirect back to the edit screen.
        // Attempt to save the data.
        if (!$model->save($data)) {
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
        $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_item
                            . $this->getRedirectToItemAppend($recordId, $key), true
                    )
            );

        return true;
    }

}