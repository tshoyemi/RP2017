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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>

<table class="adminlist">
    <tr>
	<td valign="middle" style="width:30%;"><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_INSTALLED_VERSION') ?></strong></td>
	<td><strong><?php echo @$this->xml->version; ?></strong></td>
    </tr>

    <tr>
	<td valign="middle"><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_COPYRIGHT') ?></strong></td>
	<td><?php echo @$this->xml->copyright; ?></td>
    </tr>

    <tr>
	<td valign="middle"><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_LICENSE') ?></strong></td>
	<td><?php echo @$this->xml->license; ?></td>
    </tr>

    <tr>
	<td valign="middle"><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_CREDITS') ?></strong></td>
	<td>
	    <?php
		if (isset($this->xml->juxmember) && !empty($this->xml->juxmember)) {
	    ?>
    	    <ul style="margin: 0; padding-left: 15px;">
		    <?php
		    foreach ($this->xml->juxmember as $k => $v) {
			echo '<li>' . '<strong>' . $v['data'] . '</strong>' . ' (' . $v['name'] . ')' . '</li>';
		    }
		    ?>
    	    </ul>
	    <?php } ?>
	</td>
    </tr>
    <tr>
	<td colspan="2"><a href="<?php echo @$this->xml->jed_link; ?>" target="_blank"><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_JED_LINK'); ?></strong></a></td>
    </tr>
</table>