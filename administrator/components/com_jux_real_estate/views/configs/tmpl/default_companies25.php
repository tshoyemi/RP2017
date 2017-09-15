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

<div id="page-companies">
    <table class="noshow">
        <tr>
            <td width="100%" align="left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_COMPANIES_CONFIGURATION'); ?></legend>
                    <div class="fltlft">
                        <ul class="adminformlist">
                            <li><?php echo $this->form->getLabel('com_show_image'); ?>
                                <?php echo $this->form->getInput('com_show_image'); ?></li>
                            <li><?php echo $this->form->getLabel('com_show_address'); ?>
                                <?php echo $this->form->getInput('com_show_address'); ?></li>
                            <li><?php echo $this->form->getLabel('com_show_email'); ?>
                                <?php echo $this->form->getInput('com_show_email'); ?></li>
                            <li><?php echo $this->form->getLabel('com_show_phone'); ?>
                                <?php echo $this->form->getInput('com_show_phone'); ?></li>
                            <li><?php echo $this->form->getLabel('com_show_fax'); ?>
                                <?php echo $this->form->getInput('com_show_fax'); ?></li>
                            <li><?php echo $this->form->getLabel('com_show_website'); ?>
                                <?php echo $this->form->getInput('com_show_website'); ?></li>
                            
                            <li><?php echo $this->form->getLabel('num_intro_companies'); ?>
                                <?php echo $this->form->getInput('num_intro_companies'); ?></li>
                            <li><?php echo $this->form->getLabel('com_show_featured'); ?>
                                <?php echo $this->form->getInput('com_show_featured'); ?></li>
                            <li><?php echo $this->form->getLabel('com_feat_num'); ?>
                                <?php echo $this->form->getInput('com_feat_num'); ?></li>
                            <li><?php echo $this->form->getLabel('com_feat_position'); ?>
                                <?php echo $this->form->getInput('com_feat_position'); ?></li>
                        </ul>
                    </div>
                </fieldset>
            </td>
        </tr>
    </table>
</div>