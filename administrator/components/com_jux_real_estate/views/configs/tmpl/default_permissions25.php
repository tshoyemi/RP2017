<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div id="page-permissions">
    <table class="noshow">
        <tr>
            <td width="65%">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIGURATION_PERMISSION_LEGEND'); ?></legend>
                    <?php foreach ($this->form->getFieldset('permissions') as $field): ?>
                    <?php echo $field->label; ?>
                    <div class="clr"> </div>
                    <?php echo $field->input; ?>
                    <?php endforeach; ?>
                </fieldset>
            </td>
            <td valign="top">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_WHO_CAN_POST_REALTY'); ?></legend>
                    <table class="admintable">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('user_access'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('user_access'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</div>