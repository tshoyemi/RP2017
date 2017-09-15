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
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/addagent.css');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

?>

<form method="post" action="" id="addagentForm" name="addagentForm" class="form-validate" enctype="multipart/form-data">

    <?php
    if (isset($_POST['task']) && $_POST['task'] == 'confirm_agent') {
	echo $this->loadtemplate('confirmagent');
    } else {

	echo $this->loadtemplate('form');
    }
    ?>


</form>