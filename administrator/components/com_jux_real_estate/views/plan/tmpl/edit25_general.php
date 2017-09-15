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
<fieldset class="adminform">
    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_PLAN_INFORMATION'); ?></legend>
    <table class="admintable" width="100%">

        <tr>
            <td nowrap="nowrap" class="key" width="15%">
                <?php echo $this->form->getLabel('name'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('name'); ?>
            </td>
        </tr>

        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('ordering'); ?>
            </td>
            <td colspan="3">
                <?php echo $this->form->getInput('ordering'); ?>
            </td>
        </tr>

        <tr>

            <td class="key" width="15%">
                <?php echo $this->form->getLabel('price'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('price'); ?>
                <?php echo $this->form->getInput('currency_id'); ?>
                &nbsp;&nbsp;(<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_0_FOR_FREE'); ?></strong>)
            </td>

        </tr>
        <tr>
            <td class="key" width="15%">
                <?php echo $this->form->getLabel('days'); ?>
            </td>
            <td>

                <?php echo $this->form->getInput('days'); ?>
                <?php echo $this->form->getInput('days_type'); ?>
                &nbsp;&nbsp;(<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_0_FOR_UNLIMITED'); ?></strong>)
            </td>
        </tr>
        <tr>
            <td class="key" width="15%">
                <?php echo $this->form->getLabel('count_limit'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('count_limit'); ?>
                &nbsp;&nbsp;(<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_0_FOR_UNLIMITED'); ?></strong>)
            </td>

        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('published'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('published'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('featured'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('featured'); ?>
            </td>
        </tr>

        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('date_created'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('date_created'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('publish_up'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('publish_up'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('publish_down'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('publish_down'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('image'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('image'); ?>
            </td>
        </tr>
      
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('description'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('description'); ?>
            </td>
        </tr>
    </table>
</fieldset>