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

class JUX_Real_EstateControllerOpenhouse extends JControllerForm
{
	protected $text_prefix = 'COM_JUX_REAL_ESTATE';

    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowAdd($data = array())
    {
        $user = JFactory::getUser();
        $allow = null;
        $allow = $user->authorise('core.create', 'com_jux_real_estate.openhouse');
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
    protected function allowEdit($data = array(), $key = 'id')
    {
        $user = JFactory::getUser();
        // Initialise variables.
        $recordId = (int)isset($data[$key]) ? $data[$key] : 0;

        // Check general edit permission first.
        if ($user->authorise('core.edit', 'com_jux_real_estate.openhouse.' . $recordId)) {
            return true;
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }
}
?>
