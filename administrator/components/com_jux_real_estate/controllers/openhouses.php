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
jimport('joomla.application.component.controlleradmin');

class JUX_Real_EstateControllerOpenhouses extends JControllerAdmin
{
	protected $text_prefix = 'COM_JUX_REAL_ESTATE_OPENHOUSES';

	function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Proxy for getModel.
     *
     * @param    string    $name    The name of the model.
     * @param    string    $prefix    The prefix for the PHP class name.
     *
     * @return    JModel
     */
    public function getModel($name = 'Openhouse', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
}
?>
