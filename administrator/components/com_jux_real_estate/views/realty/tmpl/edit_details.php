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
<fieldset>
    <div class="adminform">
        <div class="span6">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_DETAILS'); ?></legend>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('beds'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('beds'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('baths'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('baths'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('sqft'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('sqft'); ?></div>
            </div>
        </div>

        <div class="span6">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_ADDITION_INFORMATION'); ?></legend>
            <table>
                <?php echo $this->form->getInput('additions'); ?>
            </table>
        </div>
    </div>
</fieldset>