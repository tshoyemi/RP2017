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
?>
<div class="tab-content">
    <div id="categories">
	<fieldset>
	    <div class="span12">
		<div class="adminform">
		    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIGURATION_PERMISSION_LEGEND'); ?></legend>
		    <div class="control-group">
			<?php echo $this->form->getInput('rules'); ?></div>
		</div>
	    </div>
	</fieldset>
    </div>
</div>
