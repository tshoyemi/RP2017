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

<div id="page-realties">
    <table class="noshow">
        <tr>
            <td width="100%" align="left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTIES_CONFIGURATION'); ?></legend>
                    <div class="fltlft">
                        <ul class="adminformlist">
                             <li><?php echo $this->form->getLabel('show_agent'); ?>
                                <?php echo $this->form->getInput('show_agent'); ?></li>
                            <li><?php echo $this->form->getLabel('show_featured'); ?>
                                <?php echo $this->form->getInput('show_featured'); ?></li>
                            <li><?php echo $this->form->getLabel('num_featured_realty'); ?>
                                <?php echo $this->form->getInput('num_featured_realty'); ?></li>
                            <li><?php echo $this->form->getLabel('featured_position'); ?>
                                <?php echo $this->form->getInput('featured_position'); ?></li>
                            <li><?php echo $this->form->getLabel('featured_accent'); ?>
                                <?php echo $this->form->getInput('featured_accent'); ?></li>
                            <li><?php echo $this->form->getLabel('num_intro_realties'); ?>
                                <?php echo $this->form->getInput('num_intro_realties'); ?>
                            <li><?php echo $this->form->getLabel('num_columns_realties'); ?>
                                <?php echo $this->form->getInput('num_columns_realties'); ?>
                            <li><?php echo $this->form->getLabel('max_desc'); ?>
                                <?php echo $this->form->getInput('max_desc'); ?>
                        </ul>
                    </div>
                </fieldset>
            </td>
        </tr>
    </table>
</div>