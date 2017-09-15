<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */


// No direct access
defined('_JEXEC') or die;
// Base this model on the backend version.
require_once JPATH_ADMINISTRATOR . '/components/com_jux_real_estate/models/company.php';

/**
 * @package        Joomla.Site
 * @subpackage    com_jux_real_estate
 * @since 3.0
 */
class JUX_Real_EstateModelCompanyform extends JUX_Real_EstateModelCompany
{

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_COMPANYFORM';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState()
    {
        $app = JFactory::getApplication();

        // Load state from the request.
        $pk = JRequest::getInt('id');
        $this->setState('company.id', $pk);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        $this->setState('layout', JRequest::getCmd('layout'));
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     * @since    1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_jux_real_estate.edit.companyform.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }


    /**
     * Method to get the row form.
     *
     * @param    array    $data        Data for the form.
     * @param    boolean    $loadData    True if the form is to load its own data (default case), false if not.
     *
     * @return    mixed    A JForm object on success, false on failure
     * @since    1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        $app = JFactory::getApplication();
        $form = $this->loadForm('com_jux_real_estate.companyform', 'companyform', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }


    /**
     * Method to test whether a record can be deleted.
     *
     * @param    object    $record    A record object.
     *
     * @return    boolean    True if allowed to delete the record. Defaults to the permission set in the component.
     * @since    1.6
     */
    protected function canDelete($record)
    {
        $user = JFactory::getUser();

        if ($record->id) {
            return $user->authorise('core.delete', 'com_jux_real_estate.companyform.' . (int)$record->id);
        } else {
            return parent::canDelete($record);
        }
    }

    /**
     * Method to test whether a record can have its state edited.
     *
     * @param    object    $record    A record object.
     *
     * @return    boolean    True if allowed to change the state of the record. Defaults to the permission set in the component.
     * @since    1.6
     */
    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if ($record->id) {
            return $user->authorise('core.edit.state', 'com_jux_real_estate.companyform.' . (int)$record->id);
        } else {
            return parent::canEditState($record);
        }
    }

    /**
     * Method to get realty data.
     *
     * @param    integer    The id of the realty.
     *
     * @return    mixed    Content item data object on success, false on failure.
     */
    public function &getItem($itemId = null)
    {
        // Initialise variables.
        $itemId = (int)(!empty($itemId)) ? $itemId : $this->getState('company.id');

        // Get a row instance.
        $table = $this->getTable();

        // Attempt to load the row.
        $return = $table->load($itemId);

        // Check for a table object error.
        if ($return === false && $table->getError()) {
            $this->setError($table->getError());
            return false;
        }
			// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');

        // Compute selected asset permissions.
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $asset = 'com_jux_real_estate.companyform.' . $item->id;
        $params = $this->getState('params');

        // Check general edit permission first.
        if ($user->authorise('core.edit', $asset)) {
            $params->set('access-edit', true);
        }

        // Now check if edit.own is available.
        elseif (!empty($userId) && $user->authorise('core.edit.own', $asset)) {
            // Check for a valid user and that they are the owner.
            if ($userId == $item->user_id) {
                $params->set('access-edit', true);
            }
        }

        return $item;

    }

}
